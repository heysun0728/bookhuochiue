<!DOCTYPE html>
<html>
<head>
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/jquery.validate.js"></script>
<link href="../css/jquery-ui.css" rel="stylesheet" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>使用者管理</title>
<script>
tr{
  width:100%;
}
</script>
</head>
<body>
<?php

function usergroupform_update($rst,$updateNumber){
    require "../DbConnect.php";
    $sql = 'SELECT * FROM role WHERE Roleindex=:updateNumber';
    $rs=$link->prepare($sql);
    $rs->bindValue(':updateNumber',$updateNumber);
    $rs->execute();
    $rows=$rs->fetch();
        ?>
       
    <form method="post" action="">
        <div  id="form_type2" style="position:absolute;top:3%;">
            <label for="group-input">群組名稱</label>
            <input id="group-input" name="group-input" type="text" value="<?php echo $rows['RoleName'];?>"/>
            <label for="groupid-input">群組ID  </label>
            <input id="groupid-input" name="groupid-input" type="text" value="<?php echo $rows['RoleID'];?> " />
        </div>
        <div id="form-type3" style="position:absolute;top:8%;left:5%;width:105%;">

        <H3>權限設定</H3>
        <table border="1" rules="none" style="width:100%; border-style: none">
            <!--志工-->
            <tr>
                <td>
                    <input id="volunteer" type="checkbox" name="v_comptence" value="volunteer" onclick="check(this,'v_comptence[]')">
                    <label for="volunteer" >志工設定</label>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr style="border-bottom:1px solid #5dc2d0;">
                <td>
                    <input id="volunteer_checkin" type="checkbox" name="v_comptence[]" 
                           value="volunteer_checkin"  onclick="check_all(getElementById('volunteer'))"
                            <?php if($rst['volunteer_checkin'])echo "checked=\"checked\"";?>
                           >
                    <label for="volunteer_checkin">志工報到</label>
                </td>
                <td>
                    <input id="volunteer_checked" type="checkbox" name="v_comptence[]" value="volunteer_checked" onclick="check_all(getElementById('volunteer'))"<?php if($rst['volunteer_checked'])echo "checked=\"checked\"";?>>
                    <label for="volunteer_checked">志工核定</label>
                </td>
                <td>
                    <input id="volunteer_editinf" type="checkbox" name="v_comptence[]" value="volunteer_editinf" onclick="check_all(getElementById('volunteer'))" <?php if($rst['volunteer_editinf'])echo "checked=\"checked\"";?> >
                <label for="volunteer_editinf">修改志工資料</label>
                </td>
            </tr>

            <!--館室-->
            <tr>
                <td>
                    <input id="room" type="checkbox" name="room_comptence" value="room" onclick="check(this,'room_comptence[]')">
                    <label for="room">館室設定</label>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr style="border-bottom:1px solid #5dc2d0;" >
                <td>
                     <input id="room_add" type="checkbox" name="room_comptence[]" value="room_add" onclick="check_all(getElementById('room'))"  
                     <?php if($rst['room_add'])echo "checked=\"checked\"";?>  >
                    <label for="room_add">新增服務館室</label>
                </td>
                <td>
                    <input id="room_update" type="checkbox" name="room_comptence[]" value="room_update" onclick="check_all(getElementById('room'))"
                     <?php if($rst['room_update'])echo "checked=\"checked\"";?> >
                    <label for="room_update">修改服務館室</label>
                </td>
                <td>
                    <input id="room_del" type="checkbox" name="room_comptence[]" value="room_del" onclick="check_all(getElementById('room'))"
                    <?php if($rst['room_del'])echo "checked=\"checked\"";?>>
                    <label for="room_del">刪除服務館室</label>
                </td>

            </tr>

            <!--時段管理-->
            <tr>
                <td>
                    <input id="interval" type="checkbox" name="i_comptence" value="interval" onclick="check(this,'i_comptence[]')">
                    <label for="interval">時段設定</label>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr style="border-bottom:1px solid #5dc2d0;" >
                <td>
                    <input id="interval_add" type="checkbox" name="i_comptence[]" value="interval_add" onclick="check_all(getElementById('interval'))"
                    <?php if($rst['interval_add'])echo "checked=\"checked\"";?> >
                    <label for="interval_">新增時段</label>
                </td>
                <td>
                    <input id="interval_update" type="checkbox" name="i_comptence[]" value="interval_update" onclick="check_all(getElementById('interval'))" <?php if($rst['interval_update'])echo "checked=\"checked\"";?>>
                    <label for="interval_update">編輯時段</label>
                </td>
                <td>
                    <input id="interval_del" type="checkbox" name="i_comptence[]" value="interval_del" onclick="check_all(getElementById('interval'))" <?php if($rst['interval_del'])echo "checked=\"checked\"";?>>
                    <label for="interval_del">刪除時段</label>
                </td>
            </tr>

            <!--學校-->
            <tr>
                <td>
                    <input id="school" type="checkbox" name="school_comptence" value="school" onclick="check(this,'school_comptence[]')">
                    <label for="school" >學校設定</label>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr style="border-bottom:1px solid #5dc2d0;" >
                <td>
                    <input id="school_add" type="checkbox" name="school_comptence[]" value="school_add" onclick="check_all(getElementById('school'))" <?php if($rst['school_add'])echo "checked=\"checked\"";?> >
                    <label for="school_add">新增學校</label>
                </td>
                <td>
                    <input id="school_update" type="checkbox" name="school_comptence[]" value="school_update" onclick="check_all(getElementById('school'))" <?php if($rst['school_update'])echo "checked=\"checked\"";?> >
                    <label for="school_update">修改學校</label>
                </td>
                <td>
                    <input id="school_del" type="checkbox" name="school_comptence[]" value="school_del" onclick="check_all(getElementById('school'))" <?php if($rst['school_del'])echo "checked=\"checked\"";?>>
                    <label for="school_del">刪除學校</label>
                </td>
            </tr>
            <!--公告-->
            <tr>
                <td>
                    <input id="announce" type="checkbox" name="announce_comptence" value="announce" onclick="check(this,'announce_comptence[]')">
                    <label for="room">公告設定</label>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <input id="announce_add" type="checkbox" name="announce_comptence[]" value="announce_add" onclick="check_all(getElementById('announce'))" <?php if($rst['announce_add'])echo "checked=\"checked\"";?>>
                    <label for="announce_add">新增公告</label>
                </td>
                <td>
                    <input id="announce_update" type="checkbox" name="announce_comptence[]" value="announce_update" onclick="check_all(getElementById('announce'))" 
                    <?php if($rst['announce_update'])echo "checked=\"checked\"";?>>
                    <label for="announce_update">修改公告</label>
                </td>
                <td>
                    <input id="announce_del" type="checkbox" name="announce_comptence[]" value="announce_del" onclick="check_all(getElementById('announce'))" 
                    <?php if($rst['announce_del'])echo "checked=\"checked\"";?>>
                    <label for="announce_del">刪除公告</label>
                </td>
            </tr>
            <!--使用者管理-->
            <tr>
                <td>
                    <input  id="usergroup" type="checkbox" name="usergroup_comptence" value="usergroup" 
                            onclick="check(this,'usergroup_comptence[]')">
                    <label  for="usergroup">權限設定</label>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <input  id="usergroup_add" type="checkbox" name="usergroup_comptence[]" value="usergroup_add" 
                            onclick="check_all(getElementById('usergroup'))"
                            <?php if($rst['usergroup_add'])echo "checked=\"checked\"";?>>
                    <label  for="usergroup_add">新增權限</label>
                </td>
                <td>
                    <input  id="usergroup_update" type="checkbox" name="usergroup_comptence[]" value="usergroup_update"
                            onclick="check_all(getElementById('usergroup'))"
                            <?php if($rst['usergroup_update'])echo "checked=\"checked\"";?>>
                    <label  for="usergroup_update">修改權限</label>
                </td>
                <td>
                    <input  id="usergroup_del" type="checkbox" name="usergroup_comptence[]" value="usergroup_del" 
                            onclick="check_all(getElementById('usergroup'))"
                            <?php if($rst['usergroup_del'])echo "checked=\"checked\"";?>>
                    <label  for="usergroup_del">刪除權限</label>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <br/>
                    <input type="button" onclick="send(<?php echo $updateNumber; ?>)" style="margin:auto auto;" value="修改"/>
                </td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td><br/></td>
                <td></td>
            </tr>
        </table>

    </div>
    </form>
<?php } ?>
</body>
</html>