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
<title>新增時段</title>
<script>
function setusergroup_update(v){
  location.href="setusergroup_update.php?value=" + v;
}
function setusergroup_del(v){
  location.href="setusergroup_del.php?value=" + v;
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
require "../DbConnect.php";
//判斷權限
	if(isset($_SESSION['myID'])){
    	$myID=$_SESSION['myID'];
    	$vNumber=$_SESSION['vNumber'];
  		require "../DbConnect.php";

  		$sql = 'SELECT r.RoleID, c.usergroup_add, c.usergroup_update,
  						c.usergroup_del
				FROM role r, member m, comptence c
				WHERE r.RoleID = m.RoleID AND r.Roleindex = c.Roleindex AND 
						m.vNumber=:vNumber';

        $rs=$link->prepare($sql);
        $rs->bindValue(':vNumber',$vNumber);
        $rs->execute();
        $rst=$rs->fetch();
        $RoleID=$rst['RoleID'];
        $usergroup_add= $rst['usergroup_add'];
        $usergroup_update= $rst['usergroup_update'];
        $usergroup_del= $rst['usergroup_del'];
    }//end of comptence if


$sql='SELECT m.vNumber,m.ID,m.Name,m.RoleID
	      FROM member m,role r
	      WHERE m.RoleID=r.RoleID
	      order by vNumber';
	//prepare() 預處理操作
	$rs=$link->prepare($sql);
	$rs->execute();
	$rows=$rs->fetchAll(PDO::FETCH_ASSOC);
	echo "<form id='form_type1'>";
    include "../model/page.php";
	if($rows)
	{
		echo 	"<table>
				<tr>
				<th>使用者編號</th>
				<th>帳號</th>
				<th>姓名</th>
				<th>群組ID</th>";
		if(isset($usergroup_update))if($usergroup_update=='1')echo  "<th>修改</th>";
		echo "</tr>";
			 
		for($i=$min;$i<$max;$i++){
		//foreach ($rows as $rst) {
			$rst=$rows[$i];
			echo "<tr>";
			echo "<td>".$rst["vNumber"]."</td>";
		    echo "<td>".$rst["ID"]."</td>";
			echo "<td>".$rst["Name"]."</td>";
			echo "<td>".$rst["RoleID"]."</td>";
			if(isset($usergroup_update))if($usergroup_update=='1')
				echo "<td><input type=\"button\" onclick=\"setusergroup_update(".$rst['vNumber'].")\" value=\"修改\"  /></td>";
       		echo "</tr>";
		}
		echo "</table>";
	}
	else
	{
		echo"<div>無相符資料</div>";

	}
	//頁數
	$url="../model/SetUsergroup3.php";
    page_set($pagesum,$url);
?>	
</form>
</div>
</body>
</html>