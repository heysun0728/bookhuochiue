<!DOCTYPE>
<?php
  session_start(); 
  $vNumber=$_SESSION['vNumber'];
  $name=$_SESSION['name'];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--CSS-->
<link href="../css/style.css" rel="stylesheet" type="text/css" >
<link href="../css/table_style.css" rel="stylesheet" type="text/css" >
<link href="../css/form_style.css" rel="stylesheet" type="text/css" >
<title>查詢學校</title>
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
	page_set($pagesum);

	function page_set($pagesum){
		echo "<div id='page'>第";
		$nowpage=$_GET["page"];

	    $start;
	    $end;

	    if($pagesum>5&&$nowpage>2){
	    	$start=$nowpage-2;
	    }
	    else{
	    	$start=1;
	    }
	     
	    if(($nowpage+3>$pagesum)||$pagesum<6){
	    	$end=$pagesum;
	    }
	    else{
	        if($nowpage<=2){
	        	$end=6;
	        }
	        else{
	        	if($pagesum>5){
	                $end=$nowpage+3;
	        	}
	        	else{
	                $end=$pagesum;
	        	}
	        }
	    }
	    for($i=$start;$i<=$end;$i++)
	    {
	    	if($i==$nowpage){
	            echo "<a href='../model/timeintervalquery.php?page=".$i."' style='background-color:#ffe66f;'>".$i."</a>";
	    	}
	    	else{
	    	    echo "<a href='../model/timeintervalquery.php?page=".$i."'>".$i."</a>";
	    	}
	    }
	    echo "頁</div>";
	}
?>
</form>
</article>
</body>
<script>
function updateinterval(v){
  location.href="interval_update.php?value=" + v;
}
function deleteinterval(v){
  location.href="interval_del.php?value=" + v;
}
</script>
</html>