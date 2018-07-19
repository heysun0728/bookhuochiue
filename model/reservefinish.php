<?php 
   session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--CSS-->
<link href="../css/style.css" rel="stylesheet" type="text/css" >
<link href="../css/table_style.css" rel="stylesheet" type="text/css" >
<link href="../css/form_style.css" rel="stylesheet" type="text/css" >
<!--jQuery-->
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery.validate.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script>
 $(function() {
      //成功動作
       $( "#success" ).dialog({ 
          modal:true,
          buttons: { 
            "預約成功": function() { 
               $(this).dialog("close");
               $(this).onClick(location='../model/TimeintervalMangerment.php?page=1');
                   
            } 
          }  
        });
       //失敗動作
        $( "#failure" ).dialog({ 
          modal:true,
          buttons: { 
            "預約失敗": function() { 
               $(this).dialog("close");
               $(this).onClick(location='../model/TimeintervalMangerment.php?page=1'); 
            } 
          }  
        });

});
</script>
</head>
<body>
<nav>
<!--匯入左邊索引欄-->
<?php include '../nav_control.php';?> 
</nav>
<article>
<?php  
    require "../DbConnect.php";

	//新增服務紀錄
	$insertData=array($_SESSION["vNumber"],$_POST["Tinterval"],'','','',0,'','預約');
    $sql='INSERT INTO servicerecord(vNumber,Time_No,ServiceRoom, StartTime, EndTime, ServiceHour, ServiceState, ReserverState) VALUES (?,?,?,?,?,?,?,?)';
	$rs=$link->prepare($sql);

	if($rs->execute($insertData)){//判斷交易是否成功建立
		//將目前預約人數新增一人
		$sql = 'UPDATE timeinterval SET NumberOfPeople =:NumberOfPeople WHERE Time_No=:Time_No';
        $rs=$link->prepare($sql);
        $rs->bindValue(':NumberOfPeople',$_POST["NumOfPeople"]+1);
        $rs->bindValue(':Time_No',$_POST["Time_No"]);
        $rs->execute();
		echo '預約成功';
	}else{
		echo "<div id=\"failure\" title=\"新增失敗\">預約失敗<br/></div>";			
	}
?>


</article>
</body>
</html>