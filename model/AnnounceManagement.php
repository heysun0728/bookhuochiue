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
<title>公告管理</title>
<script>
function updateannounce(v){
  location.href="announce_update.php?ano="+v;
}
function deleteannounce(v){
  location.href="announce_del.php?ano="+v;
}
</script>
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
$announce_add=$announce_update=$announce_del=0;
function user(){
  global $announce_add,$announce_update,$announce_del;
  if(isset($_SESSION['myID'])){
    $myID=$_SESSION['myID'];
    $vNumber=$_SESSION['vNumber'];
  	require "../DbConnect.php";
	$sql = 'SELECT * FROM comptence_view WHERE comptence_view.vNumber=:vNumber';
    $rs=$link->prepare($sql);
    $rs->bindValue(':vNumber',$vNumber);
    $rs->execute();
    $row=$rs->fetch();
    $RoleID=$row['RoleID'];
    $announce_add=$row['announce_add'];
    $announce_update=$row['announce_update'];
    $announce_del=$row['announce_del'];
  }// end of comptence if
}
?>

<form id='form_type2'>
<?php
        user();
        require "../DbConnect.php";
		echo "<input type=\"text\" id=\"inputcon\" name=\"inputcon\"></input>";
		echo "<input type=\"submit\" value=\"查詢\" formmethod=\"post\" formaction=\"../model/announcequery.php?page=1\"></input>";
		if(isset($announce_add))if($announce_add=="1")
		echo "<button type=\"submit\" formmethod=\"post\" formaction=\"../model/announce_add.php?page=1\">新增公告</button>";
		
		echo "</form>";
        echo "<form id='form_type3'>";
		//全部資料
		$sql = 'SELECT * FROM announcement ORDER BY ADate DESC';
		$rs=$link->prepare($sql);
		//execute() 執行預處理裡面的SQL > 綁定參數
		$rs->execute();
		$rows=$rs->fetchAll(PDO::FETCH_ASSOC);
		include "../model/apage.php";
		echo "<table class=\"table table-striped table-hover\">
			  <tr>
			  <th>發布日期</th>
			  <th>圖片</th>
			  <th>標題</th>
			  <th>類型</th>";
		if(isset($announce_update))if($announce_update=='1')
			echo "<th>修改</th>";
		if(isset($announce_del))if($announce_del=='1')
			echo "<th>刪除</th>";	  
			  
		echo "</tr>";
   
		if($rows)
		{
			for($i=$min;$i<$max;$i++){
				$rst=$rows[$i];
				echo "<tr>";
				echo "<td><a href='../model/announce_detail.php?ano=".$rst["A_No"]."'>".$rst["ADate"]."</a></td>";
				echo "<td><a href='../model/announce_detail.php?ano=".$rst["A_No"]."'><img src=../upload/".$rst["AImage"]." width=\"200\" height=\"150\" \></a></td>";
				echo "<td><a href='../model/announce_detail.php?ano=".$rst["A_No"]."'>".$rst["ATitle"]."</a></td>";
				echo "<td>".$rst["AType"]."</td>";
				if(isset($announce_update))if($announce_update=='1'){
					echo "<td><input type=\"button\" onclick=\"updateannounce(".$rst['A_No'].")\" value=\"修改\"  /></td>";
				}
       			if(isset($announce_del))if($announce_del=='1'){
       				echo "<td><input type=\"button\" onclick=\"deleteannounce(".$rst['A_No'].")\" value=\"刪除\"  /></td>";
       			}
       			echo "</tr>";
			}
		}
		else{
			echo"<tr>無資料</tr>";
		}
		echo"</table><br/>";
		//頁數
		$url="../model/AnnounceManagement.php";
		page_set($pagesum,$url);

		
?>
</form>
</div>
</body>
</html>