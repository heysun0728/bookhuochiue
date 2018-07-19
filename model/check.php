
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Bootstrap -->
<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" >
<!--jQuery-->
<script src="../js/jquery-2.1.4.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<!--dialog-->
<script>
$(function() {
    //失敗動作
    $( "#failure" ).dialog({ 
        buttons: { 
        "重新登入": function() { 
           $(this).dialog("close");
           $(this).onClick(location='../index.php'); 
        } 
      }  
    });

});
</script>
<!--倒數秒數控制-->
<script type ="text/javascript">
	function delay(){
		var speed = 5000;
		setTimeout(location='../index.php', speed);
	}
</script>
<title>登入</title>
<?php
	require "../DbConnect.php";

	//接收login傳過來的資料
	$id = $_POST['id_input'];//帳號
	$pwd = $_POST['pwd_input'];//密碼

	//SQL指令
	$sql = 'SELECT * FROM member WHERE ID = :id and Password = :pwd';
	$rs=$link->prepare($sql);

	//bindValue是與變數當時的值有關，即使變動後，綁定的值也不會變動
	$rs->bindValue(':id',$id);
	$rs->bindValue(':pwd',$pwd);
	$rs->execute();//預處理操作 來執行預處理裡面的SQL語法>可以綁定參數

	$rst = $rs->fetch();
	
	if($rst){ //有資料筆數則不為0
		//給變數值並記錄在伺服器上
		
		session_start();//啟動session
		$_SESSION['myID'] = $id;//設定ID
		$_SESSION['vNumber'] = $rst["vNumber"];//將vNumber(志工編號)先寫進session
	   	$_SESSION['name'] = $rst["Name"];//設定名字
	   	$url="../index_old.php";
		echo "<script type='text/javascript'>window.location.href='$url'</script>";
	}else{
		echo "<div id=\"failure\" title=\"登入失敗\">帳號密碼有誤<br/></div>";
   	}
?>
</head>
<body>
</body>
</html>