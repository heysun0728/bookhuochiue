<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/form_style.css">
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery.validate.js"></script>
<style>
article{
   background-image:url('../image/book.jpg');
}
form p {
	color: #000000;
	font-size: 20pt;
}
form label{
	font-size: 12pt;
}
</style>

<!--jQuery 驗證內容-->
<script type="text/javascript">
	$(function () {
		// body...
		$("#loginform").validate({
			rules:{
				id_input:{
					required:true,
					minlength:2,
					maxlength:10
				},
				pwd_input:{
					required:true
				}
			}

		});
	});
</script>

</head>
<body>
<nav>
<!--匯入左邊索引欄-->
<?php include '../nav_control.php';?>
</nav>
<article>
	<div class="block2">
    <p>book<br>或缺</p>
</div>
<div class="block3">
</div>

<form role="form" id = "loginform" method="post" action="../model/check.php">
	<div class="loginform_p">
		<label for="id_input">帳號</label>
		<input id="id_input" name="id_input" class="form-control required" type="text" >
		<br/>
		<label for="pwd_input">密碼</label>
		<input id="pwd_input" name="pwd_input" class="form-control required" minlength="3" type="password" >
		<br/>
		<input class="btn btn-default submit" type="submit" value="登入">
		<br/>
		
    </div>
</form>

</article>
</body>
</html>