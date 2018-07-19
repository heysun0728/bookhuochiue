<!DOCTYPE html>
<?php
  require "../DbConnect.php";
  session_start();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新增時段</title>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/form_style.css">
</head>
<body>
<nav>
<!--匯入左邊索引欄-->+
<?php include '../nav_control.php';?> 
</nav>
<article>
  <div class="reg_pic1">
     <img src="../image/reading.png" alt="reading" ></img>
  </div>
<form method="post" action="../model/interval_add.php" id="register_form" >
  <label for="date-input">日期:</label>
  <input id="date-input" name="date-input" value="<?php echo date('Y-m-d'); ?>" type="date"/>
  <br/>
  
  <label for="TIntervalList">時段:</label>
  <select id="TIntervalList" name="Tinterval-input">
    <?php
      $sql = 'SELECT * FROM tinterval';
      $rs = $link->prepare($sql);
      $rs -> execute();
      while($row = $rs -> fetch()){
        echo "<option value=".$row[tName]." >".$row[tName]."</option>";
      }
    ?>
  </select>
  <br/>
    <label for="input-reserveAmount">預約人數上限:</label>
    <select id="input-reserveAmount" name="input-reserveAmount">
    <?php
      for($i = 1;$i<=20;$i++){
        echo "<option value=$i>$i</option>"; 
      }
    ?>
    </select>
    
  <br/>
    <label for="input_note">時段備註:</label><br/>
    <textarea id="input_note" name="input-note" value="" rows="10" cols="30"></textarea>
    <br/>
    
    <input type="submit" value="新增"/>
</form>
</article>
</body>



</html>