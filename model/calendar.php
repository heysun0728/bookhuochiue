<?php
  session_start(); 
?>
<?php //這段不要往下移動

    $HaveCompetence;
	competence();

	//把預約往上移->一按按鈕就可以顯示已預約
	if(isset($_POST['reserve'])){
		reserve();
	}
	function competence(){
		global $HaveCompetence;
		if(isset($_SESSION['competence'])){
			foreach($_SESSION['competence'] as $a){
		        if($a=="預約"||$a="預約查詢"){
		        	$HaveCompetence='1';
		        }              
			}
		}
	}
	function reserve(){
	  require "../DbConnect.php";

	  //新增服務紀錄
	  $insertData=array($_SESSION["vNumber"],$_POST["Time_No"],'',NULL,NULL,0,'','預約');
	  $sql='INSERT INTO servicerecord(vNumber,Time_No,ServiceRoom, StartTime, EndTime, ServiceHour, ServiceState, ReserverState) VALUES (?,?,?,?,?,?,?,?)';
	  $rs=$link->prepare($sql);
	  if($rs->execute($insertData)){//判斷交易是否成功建立
	    //將目前預約人數新增一人
	    $sql = 'UPDATE timeinterval 
	            SET NumberOfPeople =:NumberOfPeople 
	            WHERE Time_No=:Time_No';
	        $rs=$link->prepare($sql);
	        $rs->bindValue(':NumberOfPeople',$_POST["NumOfPeople"]+1);
	        $rs->bindValue(':Time_No',$_POST["Time_No"]);
	        $rs->execute();
	    echo '預約成功';
	  }else{
	    echo '預約失敗';        
	  }
	}

  //設定好哪年哪月的月曆
  //未設定就設為本月份
  //thisyear是今年year是被選的年分,避免select產生問題所以分開
  $thisyear;
  $year=$month=$day=$today=0;
  get_today();
  if(isset($_POST["year-input"]) && isset($_POST["month-input"])){
    $year=$_POST["year-input"];
    $month=$_POST["month-input"];
  }
  //取得每日選擇哪個選項 若沒設定就設定為顯示第一個選項
  if(!isset($_COOKIE["mon"])){
      $choose=array(0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
      setcookie('mon', serialize($choose), time()+3600);
  }
  else{
      $choose=unserialize($_COOKIE["mon"]);
  }
  //將選擇的月份更改並重設cookie
  if(isset($_GET["tinterval"])){
    $c=explode("%",$_GET["tinterval"]);
    $choose[$c[0]]=$c[1];
    setcookie('mon', serialize($choose), time()+3600);
  }
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/jquery.validate.js"></script>
<title>預約時段</title>
<script>
$(document).ready(function(){
    //成功動作
   $( "#success" ).dialog({ 
      modal:true,
      buttons: { 
        "OK": function() { 
            $(this).dialog("close");
        } 
      }  
    });
   //失敗動作
    $( "#failure" ).dialog({ 
      modal:true,
      buttons: { 
        "再次修改": function() { 
            $(this).dialog("close");
        } 
      }  
    });
});
</script>
</head>
<body>
<?php include "../need.php"; ?>
<div class="zone1">
	<div id="user_info">
	    <div id="circle"></div>
	    <p><?php  echo $_SESSION['name']?><br/><?php echo$_SESSION['RoleName'] ?></p>
	    <img src="../image/poster.png" alt="icon"></img>
	</div>		
</div>
<div class="zone2">

	<form method="post" action="" id="form_type2">
	  年:
	  <select name="year-input">
	    <?php
	       $start=$thisyear-5;//選項從五年前開始
	       //若今年已過9月就顯示明年月曆
	       if($month>=10){
	          $end=$thisyear+1;
	       }
	       else{
	          $end=$thisyear;
	       }
	       for($i=$start;$i<=$end;$i++){
	          if($i==$year){
	            //selected顯示目前選擇年份為預設值
	            echo "<option selected value=".$i.">".$i."</option>";
	          }
	          else{
	            echo "<option value=".$i.">".$i."</option>";
	          }
	          
	       }
	    ?>
	  </select>
	  月:
	  <select name="month-input">
	    <?php 
	       for($i=1;$i<=12;$i++){
	          if($i==$month){
	            //selected顯示目前選擇月份為預設值
	            echo "<option selected value=".$i.">".$i."</option>";
	          }
	          else{
	            echo "<option value=".$i.">".$i."</option>";
	          }
	       }
	    ?>
	  </select>
	  <input type="submit" value="查詢"/>  
	</form>
	<div id="form_type3" style="left:5%">
	<table id="calendar" >
	  <tr><th>一</th><th>二</th><th>三</th><th>四</th><th>五</th><th>六</th><th>日</th></tr>
	<?php
	if($HaveCompetence=='1'){
	   //顯示月曆
	   $february=get_february_day($year,$month);
	   $monthday=array(0,31,$february,31,30,31,30,31,31,30,31,30,31);
	   $FirstDayWeek=get_DayOfWeek();
	   $timer=1;
	   for($i=1;$i<=$monthday[$month];$i++){
	      if($i==1){
	        echo "<tr>";
	        $timer=$FirstDayWeek;
	        for($j=1;$j<$timer;$j++){
	          echo "<td></td>";
	        }
	      }
	      if($timer==1){echo "<tr>";}
	      echo "<td>";
	      get_IntervalInfo($i); 
	      echo "</td>";

	      if($timer==7){
	        echo "</tr>";
	        $timer=0;
	      }
	      $timer++;       
	   }
	   echo "</tr>";
	}
	   function get_DayOfWeek(){//計算每月第一天是星期幾
	      //wiki上的簡單方法一
	      //計算每月第一天是星期幾
	      $monthcode=array(0,5,1,1,4,6,2,4,0,3,5,1,3);
	      global $year, $month,$day;
	      $y=$year-2000;
	      if($month>=3||($month==2&& $day==29)){
	        $l=($year-2000)/4+1;
	      }
	      else{
	        $l=($year-2000)/4;
	      }
	      $m=$monthcode[$month];
	      $d=1;
	      $week=($y+$l+$m+$d)%7;
	      if($week==0){$week=7;}//若第一日為星期日餘數為0改回傳7
	      return $week;
	   }
	   function get_february_day($year,$month){
	      if ((($year%4==0)&&($year%100!=0))||($year%400==0)){
	         $day=29; 
	      } 
	      else{
	         $day=28;
	      } 
	      return $day;//計算二月有幾天
	   }
	   function get_today(){//取得目前年月日
	      global $year,$month,$day,$thisyear,$today;//使用全域變數

	      $today = getdate();
	      date("Y/m/d H:i");  //日期格式化
	      $thisyear=$year=$today["year"]; //年 
	      $month=$today["mon"]; //月
	      $day=$today["mday"];  //日
	   }
	   function get_IntervalInfo($day){//取得當日資料
	      global $year, $month, $choose;
	      echo "<form id='calendar_form' method='get' action=''>";
	      echo "<div id='monthday'>".$day."</div>";
	      echo "<select name='tinterval' onchange='this.form.submit();' style='width:70px;padding:2px;margin:2px'>";
	      require "../DbConnect.php";
	      $sql = 'SELECT * FROM tinterval';
	      $rs=$link->prepare($sql);
	      $rs->execute();
	      while($row = $rs->fetch()){
	        if($row["timeid"]==$choose[$day]){
	          echo "<option value=".$day."%".$row["timeid"]." selected>".$row["tName"]."</option>";
	        }
	        else{
	          echo "<option value=".$day."%".$row["timeid"].">".$row["tName"]."</option>";
	        }
	      }
	      echo "</select>";
	      echo "</form>";
	      //不要移動這行,不然會影響前後端順序
	      $RoleID=$_SESSION['RoleID'];
	      if(isset($RoleID))if($RoleID=='volunteer'){
	        echo "<form id='calendar_form' method='POST' action=''>";
	      }else{
	        echo "<form id='calendar_form' method='POST' action='librarian_search.php'>"; 
	      }
	      show_interval($year,$month,$day,$choose[$day]);
	      echo "</form>";
	   }
	  

	function show_interval($year,$month,$day,$timeid){
	  global $today;
	  require "../DbConnect.php";
	  $NumOfPeople=$ReserveAmount=$note=$ServiceDate="";
	  //連接好時段
	  $ServiceDate=$year;
	  if($month>=10){
	    $ServiceDate.="-".$month;
	  }
	  else{
	    $ServiceDate.="-0".$month;
	  }
	  if($day<=10){
	    $ServiceDate.="-0".$day;
	  }
	  else{
	    $ServiceDate.="-".$day;
	  }
	  //SQL指令-顯示符合日期時段的資料
	  $sql = 'SELECT t.*,s.vNumber  
	          FROM timeinterval t,servicerecord s
	          Where t.ServiceDate=:ServiceDate
	          and t.timeid=:timeid';
	  $rs=$link->prepare($sql);
	  $rs->bindValue(':ServiceDate',$ServiceDate);
	  $rs->bindValue(':timeid',$timeid);
	  $rs->execute();
	   
	  //將查詢到的資料存在變數裡
	  $row=$rs->fetch();
	  $Time_No=$row["Time_No"]; 
	  $ServiceDate=$row["ServiceDate"];
	  $timeid=$row["timeid"]; 
	  $NumOfPeople=$row["NumberOfPeople"];
	  $ReserveAmount=$row["ReserveAmount"];
	  $note=$row["ReserveNote"];
	       
	  //顯示預約人數、人數上限、備註
	  echo $NumOfPeople."/".$ReserveAmount;

	  //查找目前登入者是否已預約過此時段
	  $sql = 'SELECT s.vNumber,s.Time_No
	          FROM servicerecord s
	          WHERE s.Time_No=:Time_No
	          AND s.vNumber=:vNumber';
	  $rs=$link->prepare($sql);
	  $rs->bindValue(':vNumber',$_SESSION["vNumber"]);
	  $rs->bindValue(':Time_No',$Time_No);
	  $rs->execute();
	  $row=$rs->fetchall();
	  $IsReserved=(count($row)==0)?FALSE:TRUE;//資料筆數是否為0
	  $row=NULL;
	  //查找目前使用者是否被停權
	  $sql = 'SELECT ApplyState
	          From member
	          Where vNumber=:vNumber';
	  $rs=$link->prepare($sql);
	  $rs->bindValue(':vNumber',$_SESSION["vNumber"]);
	  $rs->execute();
	  $row=$rs->fetchall();
	  $CanReserve=($row[0]["ApplyState"]=="停權")?FALSE:TRUE;
	  //查找目前日子是否已經過了
	  $DayIsNotPast=FALSE;
	  if($year>=$today["year"]){
	    if($month>$today["mon"]){         
	      $DayIsNotPast=TRUE;
	    }
	    elseif(($month==$today["mon"])&&($day>=$today["mday"])){
	      $DayIsNotPast=TRUE;
	    }
	  }
	  //開始來判斷並顯示按鈕
	  $RoleID=$_SESSION['RoleID'];
	  if(isset($RoleID))if($RoleID=='volunteer'){
	      if((int)$NumOfPeople<(int)$ReserveAmount){
	          if(!$CanReserve){
	            echo "您已被停權";
	          }
	          elseif($IsReserved){ 
	            echo "已預約";
	          }
	          elseif($CanReserve&&$DayIsNotPast){
	            //用隱藏文字方塊來傳遞資料
	            echo "<input type='text' name='NumOfPeople' value='".$NumOfPeople."' style='display:none;'/>";
	            echo "<input type='text' name='Time_No' value='".$Time_No."' style='display:none;'/>";
	            echo "<input type='text' name='timeid' value='".$timeid."' style='display:none;'/>";

	            echo "<input name='reserve' type='submit' value='預約' style='margin:10px 3px;'/>";
	          }
	      }  
	      elseif(!empty($NumOfPeople)){//若資料存在並且預約人數=人數上限
	          echo "人數已滿";
	      }
	  }//顯示時段資料

	  if(isset($RoleID))if($RoleID!='volunteer'){
	    //用隱藏文字方塊來傳遞資料
	    echo "<input type='text' name='ServiceDate' value='".$ServiceDate."' style='display:none;'/>";
	    echo "<input type='text' name='Time_No' value='".$Time_No."' style='display:none;'/>";
	    echo "<input type='text' name='timeid' value='".$timeid."' style='display:none;'/>";
	    echo "<input name='search' type='submit' value='查詢' style='margin:10px 3px;'/>";
	  }

	}
?>
</table>
</div>
</div>
</body>
</html>