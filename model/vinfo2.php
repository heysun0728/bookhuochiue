<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
  session_start();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/style.css" rel="stylesheet" type="text/css" >
<link href="../css/table_style.css" rel="stylesheet" type="text/css" >
<link href="../css/form_style.css" rel="stylesheet" type="text/css" >
<title>志工詳細資料(包含服務紀錄)</title>
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
<form id='form_type1'>
<?php
    //顯示志工基本資料、服務紀錄
    require "../DbConnect.php";
	$vNumber = $_GET['vNumber'];
	$sql='SELECT * FROM member WHERE vNumber=:vNumber';
	//prepare() 預處理操作
	$rs=$link->prepare($sql);
	$rs->bindValue(':vNumber',$vNumber);

	//execute() 執行預處理裡面的SQL > 綁定參數
	$rs->execute();
	$rows=$rs->fetchAll(PDO::FETCH_ASSOC);
	$rowCount=count($rows);
	//基本資料
	if($rowCount==1){
		foreach ($rows as $rst ) {
			echo "<table class=\"table table-striped table-hover\">";
			echo "<tr>";
			echo "<td>志工編號:".$rst["vNumber"]."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>姓名：".$rst["Name"]."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>申請狀態：".$rst["ApplyState"]."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>就讀學校：".$rst["School"]."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>服務總時數：".$rst["ServiceHours"]."</td>";
			echo "</tr></table><br/><br/>";
		}
	}
	//服務紀錄
	$sql='SELECT t.ServiceDate, t.Tinterval, s.* , m.ServiceHours
          FROM servicerecord s, timeinterval t, member m
          WHERE s.Time_No = t.Time_No
          AND s.vNumber = m.vNumber
          AND m.vNumber =:vNumber';
	//prepare() 預處理操作
	$rs=$link->prepare($sql);
	$rs->bindValue(':vNumber',$vNumber);

	//execute() 執行預處理裡面的SQL > 綁定參數
	$rs->execute();
	$rows=$rs->fetchAll(PDO::FETCH_ASSOC);
	$rowCount=count($rows);

	if($rowCount)
	{
		echo "<table >";
		echo "<tr>";
		echo "<td>日期</td><td>時段名稱</td><td>服務館室</td><td>開始時間</td><td>結束時間</td><td>服務時數</td><td>預約狀態</td>";//<td>服務狀況</td><td>館員編號</td>";
		echo "</tr><br/>";
		foreach($rows as $rst){
			echo "<tr>";
			echo "<td>".$rst["ServiceDate"]."</td>";
			echo "<td>".$rst["Tinterval"]."</td>";
			echo "<td>".$rst["ServiceRoom"]."</td>";
			echo "<td>".$rst["StartTime"]."</td>";
			echo "<td>".$rst["EndTime"]."</td>";
			echo "<td>".$rst["ServiceHour"]."</td>";
			//echo "<td>".$rst["Service"]."</td>";
			echo "<td>".$rst["ReserverState"]."</td>";
			//echo "<td>".$rst["vNumber"]."</td>";
      		echo "</tr>";
		}
		echo "</table><br/>";
	}
	else
	{
		echo "無服務紀錄<br/><br/>";
	}
	echo "</div>";//end main
?>
</form>
</article>
</body>
</html>