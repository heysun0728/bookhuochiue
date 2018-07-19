<?php
  require "../DbConnect.php";
  session_start(); 
  //$MemberLevel = $_SESSION['MemberLevel'];
  $vNumber=$_SESSION['vNumber'];
  $name=$_SESSION['name'];
?>
<!DOCTYPE html>
<html>
<head>
<title>服務紀錄</title>
<!--CSS-->
<link href="../css/style.css" rel="stylesheet" type="text/css" >
<link href="../css/table_style.css" rel="stylesheet" type="text/css" >
<link href="../css/form_style.css" rel="stylesheet" type="text/css" >
<!--jQuery-->
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery.validate.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<!--dialog-->
<script>
$(function() {
   //成功動作
   $( "#success" ).dialog({ 
      modal:true,
      buttons: { 
        "OK": function() { 
            $(this).dialog("close");
            $(this).onClick(location='../model/service_record.php?page=1');
        } 
      }  
    });
   //失敗動作
    $( "#failure" ).dialog({ 
      modal:true,
      buttons: { 
        "再次修改": function() { 
            $(this).dialog("close");
            $(this).onClick(history.go(-2)); 
        } 
      }  
    });
    //沒有權限
    $( "#failure_c" ).dialog({ 
     modal:true,
       buttons: { 
        "沒有權限": function() { 
          $(this).dialog("close");
          $(this).onClick(location='../index.php');
        } 
      }  
    });
    //計算總勾選時數
    $(".check_box").click(function(){
       //$("#total_checktime")
       var index="time_"+$(this).val();
       var time=parseInt($("#"+index).val());
       var ptime=parseInt($("#total_checktime").text());
       if($(this).prop('checked')==true){
         time+=ptime;
       }
       else{
         time=ptime-time;
       }
       $("#total_checktime").text(time+"");
       if(time>=6){
         $("#apply_btn").css("display","inline");
       }
       else{
         $("#apply_btn").css("display","none");
       }
    });
});
</script>
<!--倒數秒數控制-->
<script type ="text/javascript">
    function delay(){
      var speed = 5000;
      setTimeout("history.back()", speed);
    }
    
</script>
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
<article>
<form id="form_type1" method="post" action="">
<?php
    //判斷有post紀錄接收資料
   if (isset($_POST['btn_delete'])) {
		//按照$row_timer取得主鍵
	   	Delete_Record($_POST['btn_delete']);
   }
   
  $row_timer=0;//計數器,用於命名
	//sql查詢 顯示記錄表格
	$sql='SELECT t.ServiceDate, t2.tName, s.* , m.ServiceHours
        FROM servicerecord s, timeinterval t, member m,tinterval t2
        WHERE s.Time_No = t.Time_No
        AND s.vNumber = m.vNumber
        AND m.vNumber =:vNumber
        AND t2.timeid=t.timeid
        ORDER BY t.ServiceDate DESC';
    $rs=$link->prepare($sql);
    $rs->bindValue(':vNumber',$_SESSION['vNumber']);
    if($rs->execute()){
		//用php排列html的form及table
		//設定表頭
	    echo "<table class=\"table table-striped table-hover\">
	    	  <tr><th></th><th>日期</th><th>時段</th><th>開始時間</th><th>結束時間</th>
		      <th>服務館室</th><th>服務時數</th><th>預約狀態</th><th></th></tr>";
		  
	    while($row=$rs->fetch()){//用while設定表單每行內容
		      $tr_id="tr".$row_timer;//row id名稱
		      echo "<tr id='".$tr_id ."'>";
		      if($row["ReserverState"]=="核定"){
				   //將checkbox value設為主鍵
				    echo "<td>
                  <input class='check_box' type='checkbox' name='chk_".$row_timer."' value='".$row["ServiceRecord_No"]."' />
                  <input id='time_".$row["ServiceRecord_No"]."' type='text' name='time' value='".$row["ServiceHour"]."' style='display:none'>
                  </td>";//隱藏text來傳遞資料
			    }//ServiceRecord_No
			    else{
				    echo "<td></td>";
			    }
			    echo "<td>".$row["ServiceDate"]."</td><td>".$row["tName"]."</td>".
			      "<td>".$row["StartTime"]."</td><td>".$row["EndTime"]."</td>".
				  "<td>".$row["ServiceRoom"]."</td><td>".$row["ServiceHour"]."</td>".
		          "<td>".$row["ReserverState"]."</td>";
			    //若預約狀態=預約則顯示刪除按鈕
			    if($row["ReserverState"]=="預約"){
				    //將checkbox value設為主鍵
				    echo "<td><button type='submit' name='btn_delete' value='".$row["ServiceRecord_No"]."' formmethod='post' formaction=''>刪除</button></td></tr>";
			    }
			    else{
				    echo "<td></td>";
			    }
			    //把button的value設成主鍵值    

          //雖然servicehours每列都一樣,但用fetch還是得先在裡面求值 
          //servicehours!=servicehour
		      $s_hours=$row["ServiceHours"];
          
			    $row_timer++;
	    }
	    echo "</table>";//表格設定結束
      $rowCount=$row_timer;
      include "../model/page.php";
	}
	//刪除紀錄函式
	function Delete_Record($Service_no){
		require "../DbConnect.php";
		//將預約人數減一
		$sql='UPDATE timeinterval SET NumberOfpeople=NumberOfpeople-1
              WHERE timeinterval.Time_No=
			(SELECT Time_No FROM servicerecord WHERE servicerecord.ServiceRecord_No=:ServiceRecord_No)';
        $rs=$link->prepare($sql);
        $rs->bindValue(':ServiceRecord_No',$Service_no);
        if($rs->execute()){
		//刪除預約紀錄
		    $sql='DELETE FROM servicerecord
		          WHERE ServiceRecord_No =:ServiceRecord_No';
		    $rs=$link->prepare($sql);
            $rs->bindValue(':ServiceRecord_No',$Service_no);
		    if($rs->execute()){
			   	echo "<div id=\"success\" title=\"刪除成功\">刪除成功<br/></div>";
      			echo "<script>delay_success();</script>";
		    }
		}
	}
  //更改狀態為申請
  function ChangeServiceState($ServiceRecord_No){
     require "../DbConnect.php";
     $sql='UPDATE servicerecord SET ReserverState="申請" 
           WHERE ServiceRecord_No=:ServiceRecord_No';
     $rs=$link->prepare($sql);
     $rs->bindValue(':ServiceRecord_No',$ServiceRecord_No);
     if($rs->execute()){
       echo "<div id=\"success\" title=\"已送出申請\">已送出申請<br/></div>";
     }
     else{
      echo "<div id=\"failure\" title=\"接收資料失敗\">接收資料失敗<br/></div>";
     }
   } 
  //申請函式
  function apply(){
    global $row_timer;
    //判斷哪些checkbox被勾選
    for($i=0;$i<$row_timer;$i++){
      $checkboxname="chk_".$row_timer;
      if(isset($_POST[$checkboxname])){//有被勾選的checkbox會被post進來
        ChangeServiceState($_POST[$checkboxname]);
        echo $checkboxname;
      }
    }
  }
   
  //申請按鈕若按下
   if (isset($_POST["btn_apply"])) {
     apply();
   }

	//若沒有任何紀錄,時間自動設成0
	if(!isset($s_hours)){
		$s_hours=0;
	}

  //頁數
    echo "<div id='page'>第";
    $nowpage=$_GET["page"];
      for($i=1;$i<=$pagesum;$i++)
      {
        if($i==$nowpage){
                echo "<a href='../model/service_record.php?page=".$i."' style='background-color:#ffe66f;'>".$i."</a>";
        }
        else{
            echo "<a href='../model/service_record.php?page=".$i."'>".$i."</a>";
        }
      }
        echo "頁</div>";
?>


<br/><br/>
<div id="service_box">
   總服務時數: <?php echo $s_hours;?> 小時</br>
   選取服務時數: <span id="total_checktime">0</span> 小時
   <input id="apply_btn" name="btn_apply" type="submit" value="申請" style="display:none;"> (超過6小時才可申請)
</div>
</form>
</article>
<!--<?php //將$row_timer丟給cookie
   $cookie_name = "row_timer";
   $cookie_value = $row_timer;
   setcookie($cookie_name, $cookie_value, time() + (86400)*30, "/"); // 86400 = 1 day
?>-->
</body>
</html>


