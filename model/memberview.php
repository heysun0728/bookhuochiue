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
<title>查詢志工狀態</title>
<script>
function express(v){
	location.href="update.php?value=" + v;
}
</script>
</head>
<body>
<!--
1)匯need黨
2)Css檔need裡有匯,所以不用再加
3)要套有頭像的版本->複製zone1                                                     
4)複製zone2->裡面放form語法
-->
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
	require "../DbConnect.php";
	//判斷權限
    if(isset($_SESSION['vNumber'])){
        $vNumber=$_SESSION['vNumber'];
        $sql = 'SELECT * FROM comptence_view WHERE comptence_view.vNumber=:vNumber';
        $rs=$link->prepare($sql);
        $rs->bindValue(':vNumber',$vNumber);
        $rs->execute();
        $rst = $rs->fetch();
        $RoleID = $rst['RoleID'];
        $volunteer_editinf = $rst['volunteer_editinf'];
        
    }//end of comptence if
	$id = $_SESSION['myID'];//讀取館員ID
	//全部志工資料
	$sql = 'SELECT * FROM member m, school s 
			WHERE RoleID="volunteer" AND m.school=s.schoolid ORDER BY vNumber';
	$rs = $link->prepare($sql);
	//execute() 執行預處理裡面的SQL > 綁定參數
	$rs->execute();
	$rows = $rs->fetchAll(PDO::FETCH_ASSOC);
	include "../model/page.php";
	echo "<table style='position:relative;left:5%;'>
			<tr>
				<th>姓名</th>
				<th>性別</th>
				<th>學校</th>
				<th>電話</th>
				<th>年齡</th>
				<th>申請狀態</th>
				<th>服務總時數</th>";
	if(isset($volunteer_editinf)&&$volunteer_editinf=='1')
		echo "<th>修改</th>";
	echo "</tr>";
    
	if($rows)
	{
		for($i=$min;$i<$max;$i++){
			$rst=$rows[$i];
				echo "<tr>";
				echo "<td>".$rst["Name"]."</td>";
				echo "<td>".$rst["Sex"]."</td>";
				echo "<td>".$rst["schoolName"]."</td>";
				echo "<td>".$rst["Phone"]."</td>";
				echo "<td>".$rst["Age"]."</td>";
				echo "<td>".$rst["ApplyState"]."</td>";
				echo "<td>".$rst["ServiceHours"]."</td>";
				
				echo "<td><input type=\"button\" onclick=\"express(".$rst["vNumber"].")\" value=\"修改\"  /></td>";
        		echo "</tr>";
        	
		}
		echo"</table><br/>";
	}else{
		echo "
			<div class=\"alert alert-info\" role=\"alert\">無資料</div>
		";
	}
	//頁數
	
    $url="../model/memberview.php";
	page_set($pagesum,$url);


	
?>
</form>
</div>
</body>
</html>