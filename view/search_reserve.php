<!DOCTYPE html>
<?php
 session_start();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="../css/style.css" rel="stylesheet" type="text/css" >
<link rel="stylesheet" href="../css/form_style.css">
<style>
article{
   background-image:url('../image/book.jpg');
}
</style>
</head>
<body>
<nav>
<?php include '../nav_control.php';?> 
</nav>
<article>
<div class="block2">
    <p>book<br>或缺</p>
</div>
<div class="block3"></div>
<form method="post" action="../model/search_reserve.php" id ="loginform">
<?php
   $NowTime=time();//取得時間
$NowTime=gmdate("Y-m-d H:i:s",$NowTime+8*3600);
?>	
  日期:<input name="date-input" type="date" min=<?php echo $NowTime;?> value=<?php echo $NowTime;?>/></br>
  時段:
  <select name="Tinterval-input">
     <option value="1">上午</option>
	 <option value="2">下午</option>
  </select><br><br>
  <input type="submit" value="查詢"/>
</form>
</article>
</body>
</html>