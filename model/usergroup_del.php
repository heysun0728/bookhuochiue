<?php 
    session_start(); 
    $id = $_SESSION['myID'];//接收目前登入身分ID
    $name=$_SESSION['name'];
    $vNumber=$_SESSION['vNumber'];//讀取目前進入者的(志工)編號
?>
<!DOCTYPE html>
<html>
<head>
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/jquery.validate.js"></script>
<link href="../css/jquery-ui.css" rel="stylesheet" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新增時段</title>
<script>
//全部選取
	function check(formObj,objname){
		var checkboxs = document.getElementsByName(objname);
    	for(var i=0;i<checkboxs.length;i++)
    	{
    			checkboxs[i].checked = formObj.checked;
    	}
	}
	//如果有取消則全選鍵取消
	function check_all(formObj){
		formObj.checked=false;
	}
	function send(){
		//checkbox
		document.getElementById('usergroup_form').action = "../model/usergroup_add.php";
		document.getElementById('usergroup_form').submit();

	}
	$(function() {
        //成功動作
       $( "#success" ).dialog({ 
          modal:true,
          buttons: { 
            "OK": function() { 
                $(this).dialog("close");
                $(this).onClick(location='../model/usergroup.php?page=1');
                     
            } 
          }  
        });
       //失敗動作
        $( "#failure" ).dialog({ 
          modal:true,
          buttons: { 
            "再次修改": function() { 
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
    require "../DbConnect.php";
   //判斷權限
  if(isset($_SESSION['myID'])){
    $myID=$_SESSION['myID'];
    $sql = 'SELECT role.RoleID, comptence.usergroup_del          
            FROM role, member, comptence
            WHERE role.RoleID = member.RoleID AND role.Roleindex = comptence.Roleindex AND member.id=:myID';
    $rs=$link->prepare($sql);
    $rs->bindValue(':myID',$myID);
    $rs->execute();
    $rows=$rs->fetchAll(PDO::FETCH_ASSOC);
    $rowCount=count($rows);
    if($rowCount==1){
        foreach ($rows as $rst) {
            $RoleID=$rst['RoleID'];
            $usergroup_del=$rst['usergroup_del'];
        }//end of foreach
    }//end of if                
  }//end of comptence if
  if(isset($usergroup_del))if($usergroup_del=='1'){
    $updateNumber = $_GET['value'];
    $sql='SELECT * FROM comptence, role WHERE role.Roleindex=:updateNumber AND comptence.Roleindex = role.Roleindex';
    $rs=$link->prepare($sql);
    $rs->bindValue(':updateNumber',$updateNumber);
    $rs->execute();
    $rows=$rs->fetchAll(PDO::FETCH_ASSOC);
    $rowCount=count($rows);
    if($rowCount==1){
      foreach ($rows as $rst) {
        include 'usergroupform_del.php';
        usergroupform_del($rst,$updateNumber);
      }//end of foreach
    }//end of if
    //接收資料
    if($_SERVER["REQUEST_METHOD"]=='POST'){
      $Roleindex = $updateNumber;
      $sql = 'DELETE FROM role WHERE Roleindex = :Roleindex';
      //$sql = 'DELETE FROM comptence WHERE Roleindex = :Roleindex';
      $rs=$link->prepare($sql);
      $rs->bindValue(':Roleindex',$Roleindex);

      try{
        if($rs->execute()){
          $sql = 'DELETE FROM comptence WHERE Roleindex = :Roleindex';
          $rs=$link->prepare($sql);
          $rs->bindValue(':Roleindex',$Roleindex);
          try{
            if($rs->execute()){
              echo "<div id=\"success\" title=\"刪除成功\">刪除成功<br/></div>";
              echo "<script>delay_success();</script>";
            }else{
              echo " <div id=\"failure\" title=\"刪除失敗\">刪除失敗<br/></div>";
              echo "<script>delay();</script>";
            }
          }catch (PDOException $e){
            echo "<div id=\"failure\" title=\"刪除失敗\">刪除失敗<br/></div>";
            echo "<script>delay();</script>";
            printf("DataBaseError %s",$e->getMessage());
          }
        }else{
          echo " <div id=\"failure\" title=\"刪除失敗\">刪除失敗<br/></div>";
          echo "<script>delay();</script>";
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