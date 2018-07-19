<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	$dsn='mysql:dbname=ntcustsa;host:127.0.0.1';
	$user='root';
	$password='';
	try{
		//PDO的連結語法
		$link=new PDO($dsn,$user,$password);
		$link->exec('SET CHARACTER SET utf8');
		
	}catch(PDOException $e){
		
		//接收錯誤訊號
		printf("DataBaseError %s",$e->getMessage());
		}
?>
</head>

<body>
</body>
</html>