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
<title>修改時段資料</title>
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
    //判斷權限
      if(isset($_SESSION['myID'])){
        $myID=$_SESSION['myID'];
        $vNumber=$_SESSION['vNumber'];
        require "../DbConnect.php";
        $sql = 'SELECT c.room_update FROM role r, member m, comptence c
                WHERE  r.RoleID = m.RoleID AND r.Roleindex = c.Roleindex AND m.vNumber=:vNumber';
        $rs=$link->prepare($sql);
        $rs->bindValue(':vNumber',$vNumber);
        $rs->execute();
        $rst=$rs->fetch();
        $room_update = $rst['room_update'];
}//end of comptence if

  if(isset($room_update))if($room_update=='1'){
    $updateNumber = $_GET['value'];
    $sql = ' SELECT * FROM  serviceroom WHERE  serviceroomID =:updateNumber';
    $rs=$link->prepare($sql);
    $rs->bindValue(':updateNumber',$updateNumber);
    $rs->execute();
    $rst=$rs->fetch();
    
    ?>
        <form style="width:30%" name="form" method="post" id="form_type1" >
          <label for="serviceroomName">館室名稱</label><br/>
          <input name="serviceroomName" id ="serviceroomName"type="text" value="<?php echo $rst["serviceroomName"];?>"/><br/>
          <label for="floor">館室位置</label><br/>
          <input name="floor" id ="floor"type="text" value="<?php echo $rst["floor"];?>"/><br/>
          
        <input type="submit" name="button" value="確定修改" /><br/><br/>
        </form>
 <?php
  //room
  if($_SERVER["REQUEST_METHOD"]=='POST'){
    $serviceroomName = $_POST['serviceroomName'];
    $floor = $_POST['floor'];
    $sql = 'UPDATE serviceroom SET serviceroomName=:serviceroomName,floor=:floor WHERE serviceroomID=:updateNumber';
    $rs=$link->prepare($sql);
    $rs->bindValue(':serviceroomName',$serviceroomName);
    $rs->bindValue(':floor',$floor);
    $rs->bindValue(':updateNumber',$updateNumber);

  try{
    if($rs->execute()){
      echo "<div id=\"success\" title=\"修改成功\">修改成功<br/></div>";
      echo "<script>delay_success();</script>";
    }else{
      echo "<div id=\"failure\" title=\"修改失敗\">修改失敗<br/></div>";
      echo "<script>delay();</script>";
    }
  }catch (PDOException $e){
    echo "<div id=\"failure\" title=\"修改失敗\">修改失敗<br/></div>";
    echo "<script>delay();</script>";
    printf("DataBaseError %s",$e->getMessage());

  }

        }
  }else{
    echo "<div id=\"failure_c\" title=\"沒有權限瀏覽\">沒有權限瀏覽<br/></div>";
    echo "<script>delay();</script>";
  }
  
  ?>
</div>
</body>
</html>