<?php 
	require "../DbConnect.php";
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
<title>時段查詢</title>
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
<?php
    require "../DbConnect.php";
	$con="";
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
	  	$con=test_input($_GET['inputcon']);
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	  	$con=test_input($_POST['inputcon']);
	}
    //去除特殊字元和空白
	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
    }

	echo "<form id='form_type2' name=\"queryform\" method=\"post\"action=\"../model/schoolquery.php?page=1\">";
    echo "<input name=\"inputcon\" =\"inputcon\" type=\"text\">";
	$disabled= "disabled=\"disabled\"";

	echo "<input name=\"submit\" type=\"submit\" value=\"查詢\"/>";
	echo "<button type=\"submit\" formmethod=\"post\" formaction=\"../model/school_add.php\">新增學校</button>";
	echo "</form><br/>";

	$sql='SELECT t1.*, t2.tName  FROM timeinterval t1, tinterval t2
	      WHERE t1.timeid = t2.timeid
	      OR schoolid LIKE :schoolid
	      OR schoolName LIKE :schoolname
	      OR City LIKE :city
	      OR Level LIKE :level
	      ORDER BY ServiceDate DESC';
	//prepare() 預處理操作
	$rs=$link->prepare($sql);
	$rs->bindValue(':schoolid','%'.$con.'%');
	$rs->bindValue(':schoolname','%'.$con.'%');
	$rs->bindValue(':city','%'.$con.'%');
	$rs->bindValue(':level','%'.$con.'%');
	//execute() 執行預處理裡面的SQL > 綁定參數
	$rs->execute();
	$rows=$rs->fetchAll(PDO::FETCH_ASSOC);
	echo "<form id='form_type3'>";
    include "../model/page.php";
	if($rows)
	{
		echo "<table>
		<tr>
			 <th>時段編號</th>
			 <th>日期</th>
			 <th>時段</th>
			 <th>人數</th>
			 <th>人數上限</th>
			 <th>備註</th>";
		if(isset($interval_update))if($interval_update=='1')
			echo  "<th>修改</th>";
		if(isset($interval_del))if($interval_del=='1')
			echo  "<th>刪除</th>";
		echo "</tr>";
			 
		for($i=$min;$i<$max;$i++){
			$rst=$rows[$i];
			echo "<tr>";
			echo "<td>".$rst["Time_No"]."</td>";
			echo "<td>".$rst["ServiceDate"]."</td>";
			echo "<td>".$rst["tName"]."</td>";
			echo "<td>".$rst["NumberOfPeople"]."</td>";
			echo "<td>".$rst["ReserveAmount"]."</td>";
			echo "<td>".$rst["ReserveNote"]."</td>";
			echo "<td><input type=\"button\" onclick=\"updateinterval(".$rst['Time_No'].")\" value=\"修改\"  /></td>";
       		echo "<td><input type=\"button\" onclick=\"deleteinterval(".$rst['Time_No'].")\" value=\"刪除\"  /></td>";
       		echo "</tr>";
		}
		echo "</table>";
	}
	else
	{
		echo"<div>無相符資料</div>";

	}
	echo "</div>";//end main
	//頁數
	$url="../model/timeintervalquery.php";
	page_set($pagesum,$url);
?>
</div>
</body>
</html>