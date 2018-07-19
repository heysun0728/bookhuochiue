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
<title>新增服務時段</title>
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
      $sql = 'SELECT r.RoleID, c.interval_add
              FROM role r, member m, comptence c
              WHERE r.RoleID = m.RoleID AND r.Roleindex = c.Roleindex AND m.vNumber=:vNumber';
      $rs=$link->prepare($sql);
      $rs->bindValue(':vNumber',$vNumber);
      $rs->execute();
      $rst=$rs->fetch();
      $RoleID=$rst['RoleID'];
      $interval_add=$rst['interval_add'];
  }//end of comptence if
?>

<?php
  if(isset($interval_add))if($interval_add=='1'){
?>
        <form id='form_type1' name='form' method='post' action="../model/interval_add.php">
          <div class="form-group1">
            <div class=\"itemicon\"></div><label for="date-input">日期:</label>
  <input id="date-input" name="date-input" value="<?php echo date('Y-m-d'); ?>" type="date"/>
  <br/>
  
  <div class=\"itemicon\"></div><label for="TIntervalList">時段:</label>
  <select id="TIntervalList" name="Tinterval-input">
    <?php
      $sql = 'SELECT * FROM tinterval';
      $rs = $link->prepare($sql);
      $rs -> execute();
      while($row = $rs -> fetch()){
        echo "<option value=".$row['timeid']." >".$row['tName']."</option>";
      }
    ?>
  </select>
  <br/>
    <div class=\"itemicon\"></div><label for="input-reserveAmount">預約人數上限:</label>
    <select id="input-reserveAmount" name="input-reserveAmount">
    <?php
      for($i = 1;$i<=20;$i++){
        echo "<option value=$i>$i</option>"; 
      }
    ?>
    </select>
    
  <br/>
    <div class=\"itemicon\"></div><label for="input_note">時段備註:</label><br/>
    <textarea id="input_note" name="input-note" value="" rows="10" cols="30"></textarea>
    <br/>
    
    <input type="submit" value="新增"/>
    
        </div>
          
        </form>
        <?php
        
  }
?>

</div>
</body>
</html>