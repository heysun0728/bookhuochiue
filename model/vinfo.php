<?php 
	require "../DbConnect.php";
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/jquery.validate.js"></script>
<link href="../css/jquery-ui.css" rel="stylesheet" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>志工詳細資料(包含服務紀錄)</title>
</head>
<body>
<?php include '../need.php';?> 
<div class="zone1">
	<div id="user_info">
	    <div id="circle"></div>
	    <p><?php  echo $_SESSION['name']?><br/><?php echo$_SESSION['RoleName'] ?></p>
	    <img src="../image/poster.png" alt="icon"></img>
	</div>		
</div>
<div class="zone2">
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
			echo "志工編號:".$rst["vNumber"]." |   ";
			echo "姓名：".$rst["Name"]." |   ";
			echo "申請狀態：".$rst["ApplyState"]." |   ";
			echo "就讀學校：".$rst["School"]." |   ";
			echo "服務總時數：".$rst["ServiceHours"]."<br/>";
		}
	}
	//服務紀錄
	$sql='SELECT t.ServiceDate, t2.tName, s.* , m.ServiceHours
          FROM servicerecord s, timeinterval t, member m,tinterval t2
          WHERE s.Time_No = t.Time_No
          AND t.timeid=t2.timeid
          AND s.vNumber = m.vNumber
          AND m.vNumber =:vNumber';
	//prepare() 預處理操作
	$rs=$link->prepare($sql);
	$rs->bindValue(':vNumber',$vNumber);
	//execute() 執行預處理裡面的SQL > 綁定參數
	$rs->execute();
	$rows=$rs->fetchAll(PDO::FETCH_ASSOC);
	$rowCount=count($rows);
    include "../model/page.php";
	if($rowCount)
	{
		echo "<table>";
		echo "<tr>";
		echo "<td>日期</td><td>時段名稱</td><td>服務館室</td><td>開始時間</td><td>結束時間</td><td>服務時數</td><td>預約狀態</td>";//<td>服務狀況</td><td>館員編號</td>";
		echo "</tr><br/>";
		for($i=$min;$i<$max;$i++){//用while設定表單每行內容
			 $rst=$rows[$i];
			echo "<tr>";
			echo "<td>".$rst["ServiceDate"]."</td>";
			echo "<td>".$rst["tName"]."</td>";
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
	    //頁數
    	$url="../model/vinfo.php?vNumber=".$vNumber."&";
		page_set2($pagesum,$url);
	}
	else
	{
		echo "無服務紀錄<br/><br/>";
	}
	echo "</div>";//end main
?>
</form>
</div>
</body>
</html>