<!DOCTYPE html>
<html>
<head>
<!--JQuery-->
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery.validate.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script>
$(function() {
    $("#logout_sucess").dialog({ 
        autoOpen:false,
        modal:true,
        buttons: { 
          "OK":function() { 
              $(this).dialog("close");
              location ='../index.php';
          } 
        }  
    });
    $("#logout_ahref").click(function(){
        $("#logout_sucess").dialog("open");
    });
});
</script>
</head>
<body>
<div id='logout_sucess' title='登出成功' style="display:none;">登出成功<br/></div>
<div id='logo'><h1>book<br/>或缺</h1></div>
<ul>
<?php
  if(isset($_SESSION['myID'])){
    $myID=$_SESSION['myID'];
    $vNumber=$_SESSION['vNumber'];
  require "DbConnect.php";

  $sql = 'SELECT * FROM comptence_view
          WHERE comptence_view.vNumber=:vNumber';

  $rs = $link->prepare($sql);
  $rs->bindValue(':vNumber',$vNumber);
  $rs->execute();
  $rst = $rs->fetch();
        
  $RoleID=$rst['RoleID'];
  $RoleName=$rst['RoleName'];
  $volunteer_checkin=$rst['volunteer_checkin'];
  $volunteer_checked=$rst['volunteer_checked'];
  $volunteer_editinf=$rst['volunteer_editinf'];
  $room_add = $rst['room_add'];
  $room_update = $rst['room_update'];
  $room_del = $rst['room_del'];
  $interval_add = $rst['interval_add'];
  $interval_update = $rst['interval_update'];
  $interval_del = $rst['interval_del'];
  $school_add = $rst['school_add'];
  $school_del = $rst['school_del'];
  $school_update = $rst['school_update'];
  $announce_add = $rst['announce_add'];
  $announce_update = $rst['announce_update'];
  $announce_del = $rst['announce_del'];
  $usergroup_add= $rst['usergroup_add'];
  $usergroup_update= $rst['usergroup_update'];
  $usergroup_del= $rst['usergroup_del'];
          
}//end of comptence if
     
if(!isset($_SESSION['vNumber'])){
    //訪客
    echo "<li>Hello, guest!!</li><br/>";
    echo "<li><a href='../index.php'>首頁</a></li>
          <li><a href='../index.php'>登入</a></li>
          <li><a href='../view/register.php'>註冊</a></li>";
}else{
    if($RoleID =='volunteer'){
    //志工
    echo "<li>Hello, ".$_SESSION['name'].$RoleName."!!</li><br/>";
    echo "<li><a href='../model/calendar.php'>預約</a></li>
          <li><a href='../model/userinfoupdate.php'>個人資料</a></li>
          <li><a href='../model/service_record.php?page=1'>服務紀錄</a></li>";
    }else{
      echo "<li>Hello, ".$_SESSION['name'].$RoleName."!!</li><br/>";
      //echo "<li><a href='../model/userinfoupdate.php'>個人資料</a></li>";
      if(isset($volunteer_checkin))if($volunteer_checkin=='1')
          echo "<li><a href='../model/ChangeApplyState.php'>更改申請狀態</a></li>
                <li><a href='../model/choose_room.php'>設定服務館室</a></li>
                <li><a href='../model/check_in.php'>志工報到</a></li>
                <li><a href='../model/verify.php'>時數核定</a></li>
                <li><a href='../model/apply_confirm.php'>申請確認</a></li>
                <li><a href='../model/calendar.php'>預約查詢</a></li>
                <li><a href='../model/infoqueryform.php?page=1'>志工查詢</a></li>
                <li><a href='../model/memberview.php?page=1'>志工管理</a></li>
                ";
      if(isset($usergroup_add)||isset($usergroup_update)||isset($usergroup_del))if($usergroup_add=='1'||$usergroup_update=='1'||$usergroup_del=='1') {
        echo "<li><a href='../model/usergroup.php?page=1'>使用者管理</a></li>";
      }
      if(isset($room_add)||isset($room_update)||isset($room_del))if($room_add=='1'||$room_update=='1'||$room_del=='1'){
        echo "<li><a href='../model/RoomMangerment.php?page=1'>館室管理</a></li>";
      }

      if(isset($school_add)||isset($school_del)||isset($school_update))if($school_del=='1'||$school_update=='1'){
          echo "<li><a href='../model/SchoolMangerment.php?page=1'>學校管理</a></li>";}
      if(isset($interval_add)||isset($interval_update)||isset($interval_del))if($interval_add=='1'||$interval_update=='1'||$interval_del='1'){
        echo "<li><a href='../model/TimeintervalMangerment.php?page=1'>時段管理</a></li>";
      }
      if(isset($announce_add)||isset($announce_update)||isset($announce_del))if($announce_add=='1'||$announce_update=='1'||$announce_del=='1')
        echo "<li><a href='../model/AnnounceManagement.php?page=1'>公告管理</a></li>";
      }
}
?>
<li><a id='logout_ahref' style="cursor: pointer;">登出</a></li>
</ul>
</body>
</html>