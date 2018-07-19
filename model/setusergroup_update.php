<?php 
    session_start(); 
    $id = $_SESSION['myID'];//接收目前登入身分ID
    $vNumber=$_SESSION['vNumber'];//讀取目前進入者的(志工)編號
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
<title>設定使用者權限</title>
<script>
$(function() {
      //成功動作
       $( "#success" ).dialog({ 
          modal:true,buttons: { 
            "OK": function() { 
               $(this).dialog("close");
               $(this).onClick(location='../model/SetUsergroup.php?page=1');
                   
            } 
          }  
        });
       //失敗動作
        $( "#failure" ).dialog({ 
                    buttons: { 
            "修改失敗": function() { 
               $(this).dialog("close");
               $(this).onClick(location='../model/SetUsergroup.php?page=1'); 
            } 
          }  
        });

});</script>
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
        $sql = 'SELECT r.RoleID, c.usergroup_update 
                FROM role r, member m, comptence c
                WHERE r.RoleID = m.RoleID AND r.Roleindex = c.Roleindex AND m.vNumber=:vNumber';
        $rs=$link->prepare($sql);
        $rs->bindValue(':vNumber',$vNumber);
        $rs->execute();
        $rst=$rs->fetch();
        $RoleID=$rst['RoleID'];
        $usergroup_update=$rst['usergroup_update'];
    }//end of comptence if

 ?>

<?php
  if(isset($usergroup_update))if($usergroup_update=='1'){
    $updateNumber = $_GET['value'];
    $sql = 'SELECT vNumber, ID, Name, RoleID FROM member WHERE vNumber=:updateNumber';
    $rs=$link->prepare($sql);
    $rs->bindValue(':updateNumber',$updateNumber);
    $rs->execute();
    $rst=$rs->fetch();
    ?>
        <form id='form_type1' name='form' method='post'>
          <div class="form-group1">
            <div class=\"itemicon\"></div><b>帳號：</b><?php echo $rst["ID"] ;?><br/>
            <div class=\"itemicon\"></div><label for="name">姓名</label>
              <input name="name" id="name" type="text"  value="<?php echo $rst["Name"];?>" ReadOnly/> <br/>
            <div class=\"itemicon\"></div><label for="RoleID">群組ID</label>
          <?php
          $roleid=$rst['RoleID'];
          echo "<select name=\"select_RoleID\">";
          $sql = 'SELECT * FROM role';
          $rs=$link->prepare($sql);
          $rs->execute();
          while($row=$rs->fetch()){
            if($row["RoleID"]==$rst["RoleID"])echo "<option selected value='".$row["RoleID"]."'>".$row["RoleID"]."</option>";
            else echo "<option>".$row["RoleID"]."</option>";
          }//end of while
          echo "</select><br/>";
          ?>
          <br/><br/><br/>
    
        </div>
          <input class ='form-group3' type="submit" name="button" value="確定修改" /><br/><br/>
          
        </form>
        <?php
        
  }
?>

<?php
  //更新
  if ($_SERVER["REQUEST_METHOD"]=='POST') {
      //$_POST[name]取得post來的資料
      if(isset($_POST['select_RoleID']))$RoleID = $_POST['select_RoleID'];          
      $sql = 'UPDATE member SET RoleID=:RoleID WHERE vNumber=:vNumber';
      $rs=$link->prepare($sql);
      $rs->bindValue(':RoleID',$RoleID);
      $rs->bindValue(':vNumber',$updateNumber);
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
      }//end of try...catch
  }//end of if
?>
</div>
</body>
</html>