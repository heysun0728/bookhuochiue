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
<title>時段管理</title>
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
function updateinterval(v){
  location.href="interval_update.php?value=" + v;
}
function deleteinterval(v){
  location.href="interval_del.php?value=" + v;
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
		$sql = 'SELECT r.RoleID, c.interval_add, c.interval_update, c.interval_del
				FROM role r, member m, comptence c
				WHERE r.RoleID = m.RoleID AND r.Roleindex = c.Roleindex AND m.id=:myID';
  
		$rs=$link->prepare($sql);
		$rs->bindValue(':myID',$myID);
		$rs->execute();
		$rst=$rs->fetch();
		
		$RoleID=$rst['RoleID'];
		$interval_add = $rst['interval_add'];
        $interval_update = $rst['interval_update'];
        $interval_del = $rst['interval_del'];
	//end of if                
	}//end of comptence if
	if(isset($interval_add)||isset($interval_update)||isset($interval_del))if($interval_add=='1'||$interval_update=='1'||$interval_del=='1'){

		$id = $_SESSION['myID'];//讀取館員ID
		
		echo "<button type='submit' formmethod='post' formaction='../model/TintervalManagement.php?page=1'>區間時段管理</button>";
		if(isset($interval_add))if($interval_add=='1'){
			echo "<button type='submit' formmethod='post' formaction='../view/AddTimeInterval.php'>新增時段</button>";

		}
		echo "</form>";
        echo "<form id='form_type3'>";
		//全部資料
		$sql='SELECT t1.*, t2.tName 
		      FROM timeinterval t1, tinterval t2
		      WHERE t1.timeid = t2.timeid
		      ORDER BY ServiceDate DESC';
		      $rs=$link->prepare($sql);
		//execute() 執行預處理裡面的SQL > 綁定參數
		$rs->execute();
		$rows=$rs->fetchAll(PDO::FETCH_ASSOC);
	    include "../model/page.php";

		echo "<table class=\"table table-striped table-hover\">
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
   
		if($rows)
		{
			for($i=$min;$i<$max;$i++){
				$rst=$rows[$i];
				echo "<tr>";
				echo "<td>".$rst["Time_No"]."</td>";
				echo "<td>".$rst["ServiceDate"]."</td>";
				echo "<td>".$rst["tName"]."</td>";
				echo "<td>".$rst["NumberOfPeople"]."</td>";
				echo "<td>".$rst["ReserveAmount"]."</td>";
				echo "<td>".$rst["ReserveNote"]."</td>";
				if(isset($interval_update))if($interval_update=='1')
				echo "<td><input type=\"button\" onclick=\"updateinterval(".$rst['Time_No'].")\" value=\"修改\"  /></td>";
				if(isset($interval_del))if($interval_del=='1')
       			echo "<td><input type=\"button\" onclick=\"deleteinterval(".$rst['Time_No'].")\" value=\"刪除\"  /></td>";
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
	$url="../model/TimeintervalManagement.php";
	page_set($pagesum,$url);
?>
</form>
</div>
</body>
</html>