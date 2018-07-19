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
<title>查詢學校</title>
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
	  	if(isset($_GET['inputcon']))
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
	//$disabled= "disabled=\"disabled\"";

	echo "<input name=\"submit\" type=\"submit\" value=\"查詢\"/>";
	echo "<button type=\"submit\" formmethod=\"post\" formaction=\"../model/school_add.php\">新增學校</button>";
	echo "</form><br/>";

	$sql='SELECT * FROM school_view
	      WHERE schoolid LIKE :schoolid
	      OR schoolName LIKE :schoolname
	      OR CityName LIKE :city
	      OR slTYPE LIKE :level
	      ORDER BY schoolid';
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
			 <th>學校編號</th>
			 <th>校名</th>
			 <th>區域</th>
			 <th>類型</th>
			 <th>修改</th>
		</tr>";
			 
		for($i=$min;$i<$max;$i++){
			$rst=$rows[$i];
			echo "<tr>";
			echo "<td>".$rst["schoolid"]."</td>";
			echo "<td>".$rst["schoolName"]."</td>";
			echo "<td>".$rst["CityName"]."</td>";
			echo "<td>".$rst["SLtype"]."</td>";
			echo "<td><input type=\"button\" onclick=\"updateschool(".$rst['sindex'].")\" value=\"修改\"  /></td>";
       		//echo "<td><input type=\"button\" onclick=\"deleteschool(".$rst['sindex'].")\" value=\"刪除\"  /></td>";
       		echo "</tr>";
		}
		echo "</table>";
	}
	else
	{
		echo"<div>無相符資料</div>";

	}
	//頁數
	$url="../model/schoolquery.php";
	page_set($pagesum,$url);
?>
</form>
</div>
</body>
</html>