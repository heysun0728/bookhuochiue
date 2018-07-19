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
<title>修改使用者</title>
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
        //沒有權限
        $( "#failure_c" ).dialog({ 
          modal:true,
          buttons: { 
            "沒有權限": function() { 
              $(this).dialog("close");
              $(this).onClick(location='../index.php');
            } 
          }  
        });
});
</script>
</head>
<body>
<?php include '../need.php';?> 
<?php
  //判斷權限
  if(isset($_SESSION['myID'])){
    $myID=$_SESSION['myID'];
    $vNumber=$_SESSION['vNumber'];
    require "../DbConnect.php";

    $sql = 'SELECT role.RoleID, comptence.usergroup_update
            FROM role, member, comptence
            WHERE role.RoleID = member.RoleID AND role.Roleindex = comptence.Roleindex AND member.vNumber=:vNumber';

    $rs=$link->prepare($sql);
    $rs->bindValue(':vNumber',$vNumber);
    $rs->execute();
    $row=$rs->fetch();
    $RoleID=$row['RoleID'];
    $usergroup_update= $row['usergroup_update'];
    
  }//end of comptence if
?>
<div class="zone1">
	<div id="user_info">
	    <div id="circle"></div>
	    <p><?php  echo $_SESSION['name']?><br/><?php echo$_SESSION['RoleName'] ?></p>
	    <img src="../image/poster.png" alt="icon"></img>
	</div>		
</div>
<div class="zone2">
<?php
  if(isset($usergroup_update))if($usergroup_update=='1'){
    $updateNumber = $_GET['value'];

    $sql = 'SELECT * FROM comptence WHERE Roleindex=:updateNumber';
    $rs=$link->prepare($sql);
    $rs->bindValue(':updateNumber',$updateNumber);
    $rs->execute();
    $rows=$rs->fetchAll(PDO::FETCH_ASSOC);
    $rowCount=count($rows);
    if($rowCount){
      foreach ($rows as $rst) {
        //讀入修改的表單
        include 'usergroupform_update.php';
        usergroupform_update($rst,$updateNumber);
                
      }//end of foreach
      if($_SERVER["REQUEST_METHOD"]=='POST'){
        //讀取資料
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
            }//end of foreach
        }//end of volunteer
        
        //館室
        if(isset($_POST['room_comptence'])){
          $c=$_POST['room_comptence'];
          foreach ($c as $value) {
            if($value=='room_add') $comptence[4]=1;
            else if($value=='room_update') $comptence[5]=1;
            else if($value=='room_del') $comptence[6]=1;
            }//end of foreach
        }//end of room
        
        //時段
        if(isset($_POST['i_comptence'])){
          $c=$_POST['i_comptence'];
          foreach ($c as $value) {
            if($value=='interval_add') $comptence[7]=1;
            else if($value=='interval_update') $comptence[8]=1;
            else if($value=='interval_del') $comptence[9]=1;
          }//end of foreach
        }//end of if

        //學校
        if(isset($_POST['school_comptence'])){
          $c=$_POST['school_comptence'];
          foreach ($c as $value) {
            if($value=='school_add') $comptence[10]=1;
            else if($value=='school_update') $comptence[11]=1;
            else if($value=='school_del') $comptence[12]=1;
            }   //end of foreach
        }//end of school if
        
        //公告
        if(isset($_POST['announce_comptence'])){
          $c=$_POST['announce_comptence'];
          foreach ($c as $value) {
            if($value=='announce_add') $comptence[13]=1;
            else if($value=='announce_update') $comptence[14]=1;
            else if($value=='announce_del') $comptence[15]=1;
          }//end of foreach
        }//end of announce if
        
        //權限
        if(isset($_POST['usergroup_comptence'])){
          $c=$_POST['usergroup_comptence'];
          foreach ($c as $value) {
            if($value=='usergroup_add') $comptence[16]=1;
            else if($value=='usergroup_update') $comptence[17]=1;
            else if($value=='usergroup_del') $comptence[18]=1;
          }//end of foreach
        }//end of usergroup if
        
        //TODO update data
        $comptence[0]=$_GET['value'];
        $RoleID=$_POST['groupid-input'];
        $RoleID=trim($RoleID);
        $sql = 'UPDATE comptence,role,member
                SET role.RoleID=:RoleID,
                    member.RoleID=:RoleID,
                    comptence.volunteer_checkin=:volunteer_checkin,
                    comptence.volunteer_checked=:volunteer_checked,
                    comptence.volunteer_editinf=:volunteer_editinf,
                    comptence.room_add=:room_add,
                    comptence.room_update=:room_update,
                    comptence.room_del=:room_del,
                    comptence.interval_add=:interval_add,
                    comptence.interval_update=:interval_update,
                    comptence.interval_del=:interval_del,
                    comptence.school_add=:school_add,
                    comptence.school_update=:school_update,
                    comptence.school_del=:school_del,
                    comptence.announce_add=:announce_add,
                    comptence.announce_update=:announce_update,
                    comptence.announce_del=:announce_del,
                    comptence.usergroup_add=:usergroup_add,
                    comptence.usergroup_update=:usergroup_update,
                    comptence.usergroup_del=:usergroup_del
                WHERE comptence.Roleindex = role.Roleindex AND comptence.Roleindex=:Roleindex AND member.RoleID=role.RoleID';
        $rs=$link->prepare($sql);
        $rs->bindValue(':RoleID',$RoleID);
        $rs->bindValue(':volunteer_checkin',$comptence[1]);
        $rs->bindValue(':volunteer_checked',$comptence[2]);
        $rs->bindValue(':volunteer_editinf',$comptence[3]);
        $rs->bindValue(':room_add',$comptence[4]);
        $rs->bindValue(':room_update',$comptence[5]);
        $rs->bindValue(':room_del',$comptence[6]);
        $rs->bindValue(':interval_add',$comptence[7]);
        $rs->bindValue(':interval_update',$comptence[8]);
        $rs->bindValue(':interval_del',$comptence[9]);
        $rs->bindValue(':school_add',$comptence[10]);
        $rs->bindValue(':school_update',$comptence[11]);
        $rs->bindValue(':school_del',$comptence[12]);
        $rs->bindValue(':announce_add',$comptence[13]);
        $rs->bindValue(':announce_update',$comptence[14]);
        $rs->bindValue(':announce_del',$comptence[15]);
        $rs->bindValue(':usergroup_add',$comptence[16]);
        $rs->bindValue(':usergroup_update',$comptence[17]);
        $rs->bindValue(':usergroup_del',$comptence[18]);
        $rs->bindValue(':Roleindex',$comptence[0]);
        try{
          if($rs->execute()){
            echo"<div id=\"success\" title=\"修改成功\">修改成功<br/></div>";
            echo "<script>delay_success();</script>";
          }else{
            echo"<div id=\"failure\" title=\"修改失敗\">修改失敗<br/></div>";
            echo "<script>delay();</script>";
          }//end of if
        }catch (PDOException $e){
          echo"<div id=\"failure\" title=\"修改失敗\">修改失敗<br/></div>";
          echo "<script>delay();</script>";
          printf("DataBaseError %s",$e->getMessage());
        }//end of try...catch
      }
    }else{
            echo '沒有資料';
        }//end of if
  }else{
    echo "<div id=\"failure_c\" title=\"沒有權限瀏覽\">沒有權限瀏覽<br/></div>";
    echo "<script>delay();</script>";
  }

  ?>
</div>
</body>
</html>