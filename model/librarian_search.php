<?php 
    session_start(); 
    $vNumber=$_SESSION['vNumber'];
    $name=$_SESSION['name'];
?>
<!DOCTYPE html>
<html>
<head>
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/jquery.validate.js"></script>
<link href="../css/jquery-ui.css" rel="stylesheet" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>查詢志工狀態</title>
<script>
$(document).ready(function(){
	//沒有權限
    $( "#failure" ).dialog({ 
     modal:true,
      buttons: { 
      	"沒有權限": function() { 
      		$(this).dialog("close");
      		$(this).onClick(location='../index.php');
      	} 
      }  
    });
    //成功動作
   $( "#success" ).dialog({ 
      modal:true,buttons: { 
        "OK": function() { 
           $(this).dialog("close");
        } 
      }  
    });
   //失敗動作
    $( "#failure" ).dialog({ 
                buttons: { 
        "修改失敗": function() { 
           $(this).dialog("close");
        } 
      }  
    });
});
</script>
</head>
<body>
<!--
1)匯need黨
2)Css檔need裡有匯,所以不用再加
3)要套有頭像的版本->複製zone1                                                     
4)複製zone2->裡面放form語法
-->
<?php include '../need.php';?> 
<div class="zone1">
	<div id="user_info">
	    <div id="circle"></div>
	    <p><?php  echo $_SESSION['name']?><br/><?php echo$_SESSION['RoleName'] ?></p>
	    <img src="../image/poster.png" alt="icon"></img>
	</div>		
</div>
<div class="zone2">
<form id='form_type2' style="top:5%">
<?php
if(isset($_POST['search'])){
    search($_POST['ServiceDate'],$_POST['timeid']);
  }
  function search($datetime,$timeid){
  	 
     require "../DbConnect.php";

     //查詢時段名稱
     $sql='SELECT * FROM tinterval WHERE timeid = :timeid';
     $rs=$link->prepare($sql);
	 $rs->bindValue(':timeid',$timeid);
     
     if($rs->execute()){
     	$row=$rs->fetchAll();
        echo "日期:".$datetime." 時段:".$row[0]["tName"];
     }
     echo "</form>";
     echo "<form id='form_type3'>";
	//sql查詢 顯示某日某時段的預約表格
	$sql='SELECT m.vNumber,m.Name,m.ApplyState,s.*
          FROM servicerecord s, member m,timeinterval t
          WHERE s.vNumber = m.vNumber
		  AND s.Time_No=t.Time_No
          AND t.ServiceDate =:ServiceDate
          AND t.timeid=:timeid';
    $rs=$link->prepare($sql);
	$rs->bindValue(':ServiceDate',$datetime);
	$rs->bindValue(':timeid',$timeid);
    if($rs->execute()){

        //用php排列html的form及table
		echo "<table><tr>";
		//表頭名稱陣列
		$header=array("志工編號","姓名","申請狀態","服務館室","開始時間",
		              "結束時間","預約狀態","服務時數","服務狀況");
        foreach($header as $h){
			echo "<th>".$h."</th>";
		}
        echo "</tr>";
		$rowname=array("vNumber","Name","ApplyState","ServiceRoom","StartTime",
		               "EndTime","ReserverState","ServiceHour","ServiceState");
		//顯示表單每列內容
		$row=$rs->fetchAll();
		for($i=0;$i<count($row);$i++){
			echo"<tr>";
			//排文字內容
		    for($j=0;$j<=8;$j++){
			   echo "<td>".$row[$i][$rowname[$j]]."</td>";
		    }
			
			echo "</tr>";
		}
		echo "</table></form>";
	}
 }	
?>
</div>
</body>
</html>