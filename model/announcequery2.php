<!DOCTYPE html>
<?php
  session_start(); 
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--CSS-->
<link href="../css/style.css" rel="stylesheet" type="text/css" >
<link href="../css/table_style.css" rel="stylesheet" type="text/css" >
<link href="../css/form_style.css" rel="stylesheet" type="text/css" >
<title>查詢公告</title>
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

	echo "<form id='form_type2' name=\"queryform\" method=\"post\"action=\"../model/announcequery.php?page=1\">";
    echo "<input name=\"inputcon\" =\"inputcon\" type=\"text\">";
	echo "<input name=\"submit\" type=\"submit\" value=\"查詢\"/>";
	echo "<button type=\"submit\" formmethod=\"post\" formaction=\"../model/announce_add.php\">新增公告</button>";
	echo "</form><br/>";

	$sql='SELECT * FROM announcement
	      WHERE AType LIKE :atype
	      OR ATitle LIKE :atitle
	      OR ASubtitle LIKE :asubtitle
	      OR AContext LIKE :acontext
	      ORDER BY ADate DESC';
	//prepare() 預處理操作
	$rs=$link->prepare($sql);
	$rs->bindValue(':atype','%'.$con.'%');
	$rs->bindValue(':atitle','%'.$con.'%');
	$rs->bindValue(':asubtitle','%'.$con.'%');
	$rs->bindValue(':acontext','%'.$con.'%');
	//execute() 執行預處理裡面的SQL > 綁定參數
	$rs->execute();
	$rows=$rs->fetchAll(PDO::FETCH_ASSOC);
	echo "<form id='form_type3'>";
    include "../model/apage.php";
	if($rows)
	{
		echo "<table>
		      <tr>
			  <th>發布日期</th>
			  <th>圖片</th>
			  <th>標題</th>
			  <th>類型</th>
			  <th>修改</th>
			  <th>刪除</th>
			  </tr>";
   
		if($rows)
		{
			for($i=$min;$i<$max;$i++){
				$rst=$rows[$i];
				echo "<tr>";
				echo "<td><a href='../model/announce_detail.php?A_No=".$rst["A_No"]."'>".$rst["ADate"]."</a></td>";
				echo "<td><a href='../model/announce_detail.php?A_No=".$rst["A_No"]."'><img src=../upload/".$rst["AImage"]." width=\"200\" height=\"150\" \></a></td>";
				echo "<td><a href='../model/announce_detail.php?A_No=".$rst["A_No"]."'>".$rst["ATitle"]."</a></td>";
				echo "<td>".$rst["AType"]."</td>";
				echo "<td><input type=\"button\" onclick=\"updateannounce(".$rst['A_No'].")\" value=\"修改\"  /></td>";
       			echo "<td><input type=\"button\" onclick=\"deleteannounce(".$rst['A_No'].")\" value=\"刪除\"  /></td>";
       			echo "</tr>";
       		}
		}
		echo "</table>";
	}
	else
	{
		echo"<div>無相符資料</div>";

	}
	//echo "</div>";//end main
	//頁數
		echo "<div id='page'>第";
		$nowpage=$_GET["page"];
	    for($i=(($pagesum>5&&$nowpage>2)?$nowpage-2:1);$i<=($nowpage<=2?6:($pagesum>5?$nowpage+3:$pagesum));$i++)
	    {
	    	if($i==$nowpage){
                echo "<a href='../model/announcequery.php?page=".$i."&inputcon=".$con."' style='background-color:#ffe66f;'>".$i."</a>";
	    	}
	    	else{
	    	    echo "<a href='../model/announcequery.php?page=".$i."&inputcon=".$con."'>".$i."</a>";
	    	}
	    }
        echo "頁</div>";
?>
</form>
</article>
</body>
<script>
function updateannounce(v){
  location.href="announce_update.php?ano=" + v;
}
function deleteannounce(v){
  location.href="announce_del.php?ano=" + v;
}
</script>
</html>