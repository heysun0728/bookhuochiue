<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Bootstrap -->
<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" >
<!--jQuery-->
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery.validate.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>

<title>註冊</title>
<!--dialog-->
<script>
 $(function() {
 		//成功動作
       $( "#success" ).dialog({ 
          modal:true,
          buttons: { 
          	"ok": function() { 
          		$(this).dialog("close");
          		$(this).onClick(location='../view/login.php'); 
          	} 
          }  
        });
       //失敗動作
        $( "#failure" ).dialog({ 
          modal:true,
          buttons: { 
          	"重新註冊": function() { 
          		$(this).dialog("close");

          	} 
          }  
        });

});
</script>
<!--倒數秒數控制-->
<script type ="text/javascript">
	function delay(){
		var speed = 5000;
		setTimeout("history.back()", speed);
	}
	function delay_success(){
		var speed = 5000;
		setTimeout(location='../view/login.php', speed);
	}
</script>
<?php
	require "../DbConnect.php";
	//接收register傳過來的資料
	$id = $_POST['id'];
	$pwd = $_POST['pwd'];
	$rpwd = $_POST['chkpwd'];
	$name=$_POST['name'];
	$birthday=$_POST['birthday'];
	$sex=$_POST['sex'];
	$school=$_POST['school'];
	$IDNumber=$_POST['IDnumber'];
	$phone=$_POST['phone'];
	$email=$_POST['email'];
	$pname=$_POST['pname'];
	$pphone=$_POST['pphone'];
	$prelation=$_POST['relationship'];
	$age=round((time()-strtotime($birthday))/(24*60*60)/365.25,0);

	$sql = 'SELECT * FROM member WHERE ID = :id';
	$rs=$link->prepare($sql);

	//bindValue是與變數當時的值有關，即使變動後，綁定的值也不會變動
	$rs->bindValue(':id',$id);
	$rs->execute();//預處理操作 來執行預處理裡面的SQL語法>可以綁定參數
	$rows=$rs->fetchAll(PDO::FETCH_ASSOC);
	$rowCount=count($rows);
	
	if($rowCount){ //有資料筆數則不為0
		echo "<div id=\"failure\" title=\"註冊失敗\">帳號已經有人使用<br/>
   			  倒數計時<div id=\"redirect2\"></div>秒</font></div>";
		echo "<script>delay();</script>";
   			
	}else{

		//驗證身分證字號是否申請過
		$sql_IDN='SELECT * FROM member WHERE IDNumber = :IDNumber';
		$rs=$link->prepare($sql_IDN);
		$rs->bindValue(':IDNumber',$IDNumber);
		$rs->execute();
		$rows_IDN=$rs->fetchAll(PDO::FETCH_ASSOC);
		$rows_IDNCount=count($rows_IDN);
		if($rows_IDNCount){
			echo "
			<div id=\"failure\" title=\"註冊失敗\">
   				此組身分證字號已註冊過帳號<br/>
   				5秒後自動回到註冊頁面。。。<br/>
   			</div>";
   			echo "<script>delay();</script>";
   			exit();
		}

		if($pwd!=$rpwd){
			echo"
			<div id=\"failure\" title=\"註冊失敗\">
   				兩次密碼輸入不符!!請重新輸入<br/>
   				5秒後自動回到註冊頁面。。。<br/>
   			</div> ";
   			echo "<script>delay();</scripst>";
			exit;
			}
		//新增資料
		$insertData=array($id,$pwd,$name,$birthday,$sex,$school,$IDNumber,$phone,$email,$age,"申請",0,$pname,$prelation,$pphone,'volunteer');
		$sql='INSERT INTO member(ID, Password, Name, Birthday, Sex, School, IDNumber, Phone, Email, Age, ApplyState, ServiceHours, ParentName, ParentRelationship, ParentPhone, RoleID) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
		$sth=$link->prepare($sql);
		try{
			if($sth->execute($insertData)){
				echo "<div id=\"success\" title=\"新增成功\">
   					<a href=../view/login.php>連此登入</a><br/>5秒後自動回到登入頁面。。。<br/>
   				</div>";
   				echo "<script>delay_success();</script>";
			}else{
				echo "
				<div id=\"failure\" title=\"註冊失敗\">
   					5秒後自動回到註冊頁面。。。<br/>
   				</div>";
   				echo "<script>delay();</script>";
			}
		}catch (PDOException $e){
			echo "
				<div id=\"failure\" title=\"新增失敗\">
   					5秒後自動回到註冊頁面。。。<br/>
   				</div>";
   				echo "<script>delay();</script>";
		}
	}
?>
</head>

<body>
</body>
</html>