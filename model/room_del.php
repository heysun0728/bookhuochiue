<?php
  require "../DbConnect.php";
  //接收admin資料
  session_start();
  $id = $_SESSION['myID'];//接收目前登入身分ID
  $vNumber = $_SESSION['vNumber'];//讀取目前進入者的(志工)編號
  $name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html>
<head>
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/jquery.validate.js"></script>
<link href="../css/jquery-ui.css" rel="stylesheet" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>刪除館室資料</title>
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
      $sql = 'SELECT r.RoleID, c.room_del
              FROM role r, member m, comptence c
              WHERE r.RoleID = m.RoleID AND r.Roleindex = c.Roleindex AND m.id=:myID';
  
    $rs=$link->prepare($sql);
    $rs->bindValue(':myID',$myID);
    $rs->execute();
    $rst = $rs->fetch();
    $RoleID=$rst['RoleID'];
    $room_del=$rst['room_del'];
          
  }//end of comptence if
  if(isset($room_del))if($room_del=='1'){
    $updateNumber = $_GET['value'];
    $sql='SELECT * FROM serviceroom WHERE serviceroomID=:updateNumber';
    $rs=$link->prepare($sql);
    $rs->bindValue(':updateNumber',$updateNumber);
    $rs->execute();
    $rst = $rs->fetch();
   ?>
    <form id="form_type1" name="form" method="post" action="">
        <input name="inputindex" id="input-index" type="hidden"  value="<?php echo $rst["serviceroomID"];?>"><br/>
        <label for="RoomName">館室名稱：</label>
        <lable for="RoomName"><?php echo $rst["serviceroomName"]; ?></lable><br/>
        <label for="RoomAdd">館室位置：</label>
        <lable for="RoomAdd"><?php echo $rst["floor"];?></lable><br/>
      
        <input type="submit" name="button" value="確定刪除" /><br/><br/>
    </form>
<?php
    if($_SERVER["REQUEST_METHOD"]=='POST'){
      $sindex = $_POST['inputindex'];

      //缺少其他資料表需要刪除的資料SQL

  $sql = 'DELETE FROM serviceroom WHERE serviceroomID = :updateNumber';
  $rs=$link->prepare($sql);
  $rs->bindValue(':updateNumber',$updateNumber);

  try{
    if($rs->execute()){
      echo "<div id=\"success\" title=\"刪除成功\">刪除成功<br/></div>";
    }else{
      echo "<div id=\"failure\" title=\"刪除失敗\">刪除失敗<br/></div>";
    }
  }catch (PDOException $e){
    echo "<div id=\"failure\" title=\"刪除失敗\">刪除失敗<br/></div>";
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