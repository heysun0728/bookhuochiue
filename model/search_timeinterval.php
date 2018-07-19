<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Bootstrap -->
<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" >
<link href="../css/style.css" rel="stylesheet" type="text/css" >
<title>管理端首頁</title>
</head>
<body>
<?php
	require "../DbConnect.php";
	session_start();
	$id = $_SESSION['myID'];//讀取館員ID
?>
<nav>
<!--匯入左邊索引欄-->
<?php include '../nav_control.php';?> 
</nav>
<article>
<?php
   echo "<div class=\"main\">
           <div>
	       <h1>Hi~".$_SESSION['name']."館員<h1>
		   </div>";

	//全部資料
	echo "<全部時段資料><br/>";
	$sql='SELECT * FROM timeinterval';
	$rs=$link->prepare($sql);
	//execute() 執行預處理裡面的SQL > 綁定參數
	$rs->execute();
	$rows=$rs->fetchAll(PDO::FETCH_ASSOC);
	$rowCount=count($rows);
	echo "<table class=\"table table-striped table-hover\">
		  <tr>
		  <td>時段編號</td>
		  <td>日期</td>
		  <td>時段</td>
		  <td>人數</td>
		  <td>人數上限</td>
		  <td>備註</td>
		  <td>修改</td>
		  <td>刪除</td>
		  </tr>";

	if($rowCount)
	{
		foreach($rows as $rst){
			echo "<tr>";
			echo "<td>".$rst["Time_No"]."</td>";
			echo "<td>".$rst["ServiceDate"]."</td>";
			echo "<td>".$rst["Tinterval"]."</td>";
			echo "<td>".$rst["NumberOfPeople"]."</td>";
			echo "<td>".$rst["ReserveAmount"]."</td>";
			echo "<td>".$rst["ReserveNote"]."</td>";
			echo "<td><input type=\"button\" onclick=\"updateinterval(".$rst['Time_No'].")\" value=\"修改\"  /></td>";
       		echo "<td><input type=\"button\" onclick=\"deleteinterval(".$rst['Time_No'].")\" value=\"刪除\"  /></td>";

       		echo "</tr>";

		}
	}else{
		echo"<tr>無資料</tr>";
	}
	echo"</table><br/>";
	echo "</div>";//end main
?>
<script>
function updateinterval(v){
	location.href="update_interval.php?value=" + v;
}
function deleteinterval(v){
location.href="delete_interval.php?value=" + v;
}
</script>
</article/article>
</body>
</html>
<!--
