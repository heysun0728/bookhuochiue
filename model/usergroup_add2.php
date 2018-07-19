<!DOCTYPE html>
<?php
  session_start();
?>
<html>
<head>
<title>使用者管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!--CSS-->  
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/form_style.css">
<!--jQuery-->
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery.validate.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>

<style>
article{
   background-image:url('../image/book.jpg');
}
</style>
<script>
 $(function() {
      //成功動作
       $( "#success" ).dialog({ 
          modal:true,buttons: { 
            "OK": function() { 
               $(this).dialog("close");
               $(this).onClick(location='../model/usergroup.php?page=1');
                   
            } 
          }  
        });
       //失敗動作
        $( "#failure" ).dialog({ 
                    buttons: { 
            "重新新增": function() { 
               $(this).dialog("close");
               $(this).onClick(location='../model/usergroup.php?page=1'); 
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
<?php
	$comptence=Array(0,
					 0,0,0,
					 0,0,0,
					 0,0,0,
					 0,0,0,
					 0,0,0,
					 0,0,0);
	//志工
	if(isset($_POST['v_comptence'])){
		$c=$_POST['v_comptence'];
		foreach ($c as $value) {
			if($value=='volunteer_checkin') $comptence[1]=1;
			else if($value=='volunteer_checked') $comptence[2]=1;
			else if($value=='volunteer_editinf') $comptence[3]=1;
		}
	}
	//館室
	if(isset($_POST['room_comptence'])){
		$c=$_POST['room_comptence'];
		foreach ($c as $value) {
			if($value=='room_add') $comptence[4]=1;
			else if($value=='room_update') $comptence[5]=1;
			else if($value=='room_del') $comptence[6]=1;
		}
	}
	//時段
	if(isset($_POST['i_comptence'])){
		$c=$_POST['i_comptence'];
		foreach ($c as $value) {
			if($value=='interval_add') $comptence[7]=1;
			else if($value=='interval_update') $comptence[8]=1;
			else if($value=='interval_del') $comptence[9]=1;
		}
	}
	//學校
	if(isset($_POST['school_comptence'])){
		$c=$_POST['school_comptence'];
		foreach ($c as $value) {
			if($value=='school_add') $comptence[10]=1;
			else if($value=='school_update') $comptence[11]=1;
			else if($value=='school_del') $comptence[12]=1;
		}	
	}
	//公告
	if(isset($_POST['announce_comptence'])){
		$c=$_POST['announce_comptence'];
		foreach ($c as $value) {
			if($value=='announce_add') $comptence[13]=1;
			else if($value=='announce_update') $comptence[14]=1;
			else if($value=='announce_del') $comptence[15]=1;
		}
	}
	//權限
	if(isset($_POST['usergroup_comptence'])){
		$c=$_POST['usergroup_comptence'];
		foreach ($c as $value) {
			if($value=='usergroup_add') $comptence[16]=1;
			else if($value=='usergroup_update') $comptence[17]=1;
			else if($value=='usergroup_del') $comptence[18]=1;
		}
	}
	
	//print_r($comptence);
	
	//寫進資料庫
	require "../DbConnect.php";
   	if(isset($_POST['group-input'])) $RoleName=$_POST['group-input'];
   	if(isset($_POST['groupid-input'])) $RoleID=$_POST['groupid-input'];
   	$insertRole=array($RoleID,$RoleName);
    $sql='INSERT INTO role (RoleID, RoleName) VALUES (?,?)';
   	$sth=$link->prepare($sql);
      	try{
         	if($sth->execute($insertRole)){
         		echo "success";
         		//搜尋編號
         		$sql='SELECT Roleindex FROM role WHERE RoleID=:RoleID AND RoleName=:RoleName';
         		$rs=$link->prepare($sql);
				$rs->bindValue(':RoleID',$RoleID);
				$rs->bindValue(':RoleName',$RoleName);
				$rs->execute();
				$rst=$rs->fetch();
				$Roleindex=$rst['Roleindex'];
				$comptence[0]=$Roleindex;
				echo $Roleindex;
				
				//寫入comptence資料表
				$insertComptence=$comptence;
         		$sql='INSERT INTO comptence VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
         		$sth=$link->prepare($sql);
         		try{
         			if($sth->execute($insertComptence)){
						echo "<div id=\"success\" title=\"新增成功\">新增成功</div>";
   					}else{
						echo "<div id=\"failure\" title=\"新增失敗\">新增失敗</div>";
   					}
         		}catch (PDOException $e){
         			echo "<div id=\"failure\" title=\"新增失敗\">新增失敗</div>";
               	}

        }else{
         	echo "failure";

         }
      }catch (PDOException $e){
        echo "<div id=\"failure\" title=\"新增失敗\">新增失敗</div>";
      }




?>
</article>
</body>
</html>