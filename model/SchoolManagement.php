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
<title>學校管理</title>
<script>
function updateschool(v){
  location.href="school_update.php?value=" + v;
}
function deleteschool(v){
  location.href="school_del.php?value=" + v;
}
$(function() {
    $( "#inputcon" ).autocomplete({
        source: 'schoolsource.php'
    });
});
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
		$sql = 'SELECT r.RoleID, c.school_add, c.school_del, c.school_update
				FROM role r, member m, comptence c
				WHERE r.RoleID = m.RoleID AND r.Roleindex = c.Roleindex AND m.id=:myID';
  
		$rs=$link->prepare($sql);
		$rs->bindValue(':myID',$myID);
		$rs->execute();
		$rst=$rs->fetch();
		$RoleID=$rst['RoleID'];
		$school_add=$rst['school_add'];
		$school_del=$rst['school_del'];
		$school_update=$rst['school_update'];
	}//end of comptence if
	if(isset($school_add)||isset($school_del)||isset($school_update))if($school_del=='1'||$school_update=='1'){
         //$volunteer_checkin=='1'||
		$id = $_SESSION['myID'];//讀取館員ID
		?>
		<div class="ui-widget"><input type="text" id="inputcon" name="inputcon"></input>
		<input type="submit" value="查詢" formmethod="post" formaction="../model/schoolquery.php?page=1"></input>
		<?php
		if(isset($school_add))if($school_add=='1'){
			echo "<button type=\"submit\" formmethod=\"post\" formaction=\"../model/school_add.php\">新增學校</button>";
		}
		echo "</div></form>";
        echo "<form id='form_type3'>";
		//全部資料
		$sql='SELECT s.sindex, s.schoolid, s.schoolName, c.CityName, sl.SLtype
			  FROM school s, city c, schoollevel sl
		      WHERE s.City=c.cityid AND s.Level = sl.SLID
		      ORDER BY schoolid';
		$rs=$link->prepare($sql);
		//execute() 執行預處理裡面的SQL > 綁定參數
		$rs->execute();
		$rows=$rs->fetchAll(PDO::FETCH_ASSOC);
		include "../model/page.php";

		echo "<table class=\"table table-striped table-hover\">
			  <tr>
			  <th>學校編號</th>
			  <th>校名</th>
			  <th>區域</th>
			  <th>類型</th>";
		if(isset($school_update))if($school_update=='1')echo "<th>修改</th>";
		//if(isset($school_del))if($school_del=='1')echo "<th>刪除</th>";
		echo "</tr>";
   
		if($rows)
		{
			for($i=$min;$i<$max;$i++){
				$rst=$rows[$i];
				echo "<tr>";
				echo "<td>".$rst["schoolid"]."</td>";
				echo "<td>".$rst["schoolName"]."</td>";
				echo "<td>".$rst["CityName"]."</td>";
				echo "<td>".$rst["SLtype"]."</td>";
				if(isset($school_update))if($school_update=='1')
				echo "<td><input type=\"button\" onclick=\"updateschool(".$rst['sindex'].")\" value=\"修改\"  /></td>";
       			//if(isset($school_del))if($school_del=='1')
       			//echo "<td><input type=\"button\" onclick=\"deleteschool(".$rst['sindex'].")\" value=\"刪除\"  /></td>";
       			echo "</tr>";
			}
		}
		else{
			echo"<tr>無資料</tr>";
		}
		echo"</table><br/>";
		$url="../model/SchoolManagement.php";
	    page_set($pagesum,$url);

	}else{
		echo "<div id=\"failure\" title=\"沒有權限瀏覽\">
   			沒有權限瀏覽<br/>
   		  </div>
		 ";
		echo "<script>delay();</script>";

	}// end of comptence
		//頁數

?>

</form>
</div>
</body>
</html>