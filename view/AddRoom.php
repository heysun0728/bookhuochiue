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
  <form method="post" action="../model/room_add.php" id="form_type1">
    <label for="RoomName-input">館室名稱</label>
    <input id="RoomName-input" name="RoomName-input" type="text"/>
    <br/>
    <label for="RoomAdd-input">館室位置</label>
    <input id="RoomAdd-input" name="RoomAdd-input" type="text"/>
    <br/>
    
    <input type="submit" value="新增"/>
  </form>
</div>
</body>
</html>