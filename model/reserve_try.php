<?php 
   session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/form_style.css">
<style>
article{
   background-image:url('../image/book.jpg');
}
</style>
</head>
<body>
<nav>
<!--匯入左邊索引欄-->
<?php include '../nav_control.php';?> 
</nav>
<?php
  //設定好哪年哪月的月曆
  //未設定就設為本月份
  $year=$month=$day=0;
  if(isset($_POST["year-input"])&&isset($_POST["month-input"])){
    $year=$_POST["year-input"];
    $month=$_POST["month-input"];
  }
  else{
    get_today();
  }
?>
<article>
<form method="post" action="" id="form_type2" >
  <div style="color:white">  
  年:<input name="year-input" type=number value=<?php echo $year;?> min="2000" max="2016"/>
  月:<input name="month-input" type=number value=<?php echo $month;?> min="1" max="12"/>
  <input type="submit" value="查詢"/>  
  </div>
</form>
<br>
<div id="form_type3">
<table>
  <tr><th>一</th><th>二</th><th>三</th><th>四</th><th>五</th><th>六</th><th>日</th></tr>
<?php
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
   function get_DayOfWeek(){
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
      return $week;
   }
   function get_february_day($year,$month){
      if ((($year%4==0)&&($year%100!=0))||($year%400==0)){
         $day=29; 
      } 
      else{
         $day=28;
      } 
      return $day;
   }
   function get_today(){
      global $year, $month,$day;//使用全域變數

      $today = getdate();
      date("Y/m/d H:i");  //日期格式化
      $year=$today["year"]; //年 
      $month=$today["mon"]; //月
      $day=$today["mday"];  //日
   }
   function get_IntervalInfo($day){
      global $year, $month;
      echo "<form id='res_form2' method='post' action='reservefinish.php'>";
      echo "<div id='monthday'>".$day."</div>";
      echo "上午";
      show_interval($year,$month,$day,1);
      echo "</br>下午";
      show_interval($year,$month,$day,2);
      echo "</form>";
   }

function show_interval($year,$month,$day,$Tinterval){
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
          and t.Tinterval=:Tinterval';
  $rs=$link->prepare($sql);
  $rs->bindValue(':ServiceDate',$ServiceDate);
  $rs->bindValue(':Tinterval',$Tinterval);
  $rs->execute();
   
  //將查詢到的資料存在變數裡
  $row=$rs->fetch();
  $Time_No=$row["Time_No"]; 
  $ServiceDate=$row["ServiceDate"];
  $Tinterval=$row["Tinterval"]; 
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
  $rowcount=$rs->fetchall();
  $IsReserved=(count($rowcount)==0)?FALSE:TRUE;//資料筆數是否為0
  $row=NULL;

  if($_SESSION['MemberLevel']=='1'){
    if((int)$NumOfPeople<(int)$ReserveAmount){
      if($IsReserved){ 
        echo "已預約";
      }
      else{
        echo "<input name='submit' type='submit' value='預約'/></br>";
      }
    }  
    elseif(!empty($NumOfPeople)){//若資料存在並且預約人數=人數上限
        echo "人數已滿";
    }
  }

  echo "</br>".$note;
}

?>
</div>
</table>
</article>
</body>
</html>