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
$(document).ready(function(){
    //成功動作
    $( "#success" ).dialog({ 
      modal:true,
      buttons: { 
        "OK": function() { 
           $(this).dialog("close");
           $(this).onClick(location='../model/RoomManagement.php?page=1');
               
        } 
      }  
    });
   //失敗動作
    $( "#failure" ).dialog({ 
      modal:true,
      buttons: { 
        "重新新增": function() { 
           $(this).dialog("close");
           $(this).onClick(history.go(-2)); 
        } 
      }  
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
<?php
    require "../DbConnect.php";
    //接收form傳過來的資料
    if ($_SERVER["REQUEST_METHOD"]=='POST') {
      if(isset($_POST['RoomName-input']))$serviceroomName=$_POST['RoomName-input'];         
      if(isset($_POST['RoomAdd-input']))$floor=$_POST['RoomAdd-input'];         
    }else{
      echo "接收資料失敗<br/>";
    }
    echo "<br/>";
    //新增資料
      $insertData=array($serviceroomName,$floor);
      $sql='INSERT INTO serviceroom (serviceroomName, floor) VALUES (?,?)';
      $sth=$link->prepare($sql);
      try{
         if($sth->execute($insertData)){
            echo "<div id=\"success\" title=\"新增成功\">新增成功<br/></div>";
         }else{
            echo "<div id=\"failure\" title=\"新增失敗\">新增失敗<br/></div>";
            echo "<script>delay();</script>";
         }
      }catch (PDOException $e){
        echo "<div id=\"failure\" title=\"新增失敗\">新增失敗<br/></div>";
        echo "<script>delay();</script>";
      }
?>
</div>
</body>
</html>