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
<title>新增時段</title>
<script>
 $(function() {
	//沒有權限
    $( "#failure_c" ).dialog({ 
      modal:true,
      buttons: { 
      	"沒有權限": function() { 
      		$(this).dialog("close");
      		$(this).onClick(location='../index.php');
      	} 
      }  
    });
});
function updateroom(v){
  location.href="room_update.php?value=" + v;
}
function deleteroom(v){
  location.href="room_del.php?value=" + v;
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
<form id='form_type2'>
<?php
	//判斷權限
	if(isset($_SESSION['myID'])){
    	$myID=$_SESSION['myID'];
  		require "../DbConnect.php";
		$sql = 'SELECT r.RoleID, c.room_add, c.room_del, c.room_update
				FROM role r, member m, comptence c
				WHERE r.RoleID = m.RoleID AND r.Roleindex = c.Roleindex AND m.id=:myID';
  
		$rs=$link->prepare($sql);
		$rs->bindValue(':myID',$myID);
		$rs->execute();
		$rst=$rs->fetch();
		
		$RoleID=$rst['RoleID'];
		$room_add=$rst['room_add'];
		$room_update=$rst['room_update'];
		$room_del=$rst['room_del'];
	//end of if                
	}//end of comptence if
	if(isset($room_add)||isset($room_update)||isset($room_del))if($room_add=='1'||$room_update=='1'||$room_del=='1'){

		$id = $_SESSION['myID'];//讀取館員ID
		
		if(isset($room_add))if($room_add=='1'){
			echo "<button type=\"submit\" formmethod=\"post\" formaction=\"../view/AddRoom.php\">新增館室</button>";
		}
		echo "</form>";
        echo "<form id='form_type3'>";
		//全部資料
		$sql='SELECT * FROM serviceroom ORDER BY serviceroomID';
		$rs=$link->prepare($sql);
		//execute() 執行預處理裡面的SQL > 綁定參數
		$rs->execute();
		$rows=$rs->fetchAll(PDO::FETCH_ASSOC);
	    include "../model/page.php";

		echo "<table class=\"table table-striped table-hover\">
			  <tr>
			  <th>館室編號</th>
			  <th>館室名稱</th>
			  <th>館室位置</th>";
		if(isset($room_update))if($room_update=='1')echo "<th>修改</th>";
		if(isset($room_del))if($room_del=='1')echo "<th>刪除</th>";
		echo "</tr>";
   
		if($rows)
		{
			for($i=$min;$i<$max;$i++){
				$rst=$rows[$i];
				echo "<tr>";
				echo "<td>".$rst["serviceroomID"]."</td>";
				echo "<td>".$rst["serviceroomName"]."</td>";
				echo "<td>".$rst["floor"]."</td>";
				if(isset($room_update))if($room_update=='1')
				echo "<td><input type=\"button\" onclick=\"updateroom(".$rst['serviceroomID'].")\" value=\"修改\"  /></td>";
       			if(isset($room_del))if($room_del=='1')
       			echo "<td><input type=\"button\" onclick=\"deleteroom(".$rst['serviceroomID'].")\" value=\"刪除\"  /></td>";
       			echo "</tr>";
			}
		}
		else{
			echo"<tr>無資料</tr>";
		}
		echo"</table><br/>";
		
	}else{
		echo "<div id=\"failure_c\" title=\"沒有權限瀏覽\">沒有權限瀏覽<br/></div>";
		echo "<script>delay();</script>";
	}
	//頁數
	$url="../model/RoomMangerment2.php";
	page_set($pagesum,$url);
?>
</form>
</div>
</body>
</html>