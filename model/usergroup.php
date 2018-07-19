<?php
  require "../DbConnect.php";
  session_start();
?>
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
$(document).ready(function(){
	//沒有權限
    $( "#failure" ).dialog({ 
     modal:true,
      buttons: { 
      	"沒有權限": function() { 
      		$(this).dialog("close");
      		$(this).onClick(location='../index.php');
      	} 
      }  
    });
    //成功動作
   $( "#success" ).dialog({ 
      modal:true,buttons: { 
        "OK": function() { 
           $(this).dialog("close");
        } 
      }  
    });
   //失敗動作
    $( "#failure" ).dialog({ 
                buttons: { 
        "修改失敗": function() { 
           $(this).dialog("close");
        } 
      }  
    });
});
</script>
<script>
function usergroup_update(v){
  location.href="usergroup_update.php?value=" + v;
}
function usergroup_del(v){
  location.href="usergroup_del.php?value=" + v;
}
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
<form id="form_type2">
<?php
//目前人數的地方需要再做修改 沒有做SQL計算人數
  $id = $_SESSION['myID'];//讀取館員ID
  //判斷權限
  if(isset($_SESSION['myID'])){
      $myID=$_SESSION['myID'];
      $vNumber=$_SESSION['vNumber'];
      require "../DbConnect.php";

      $sql = 'SELECT role.RoleID, comptence.usergroup_add, comptence.usergroup_update, comptence.usergroup_del
        FROM role, member, comptence
        WHERE role.RoleID = member.RoleID AND  role.Roleindex = comptence.Roleindex AND  member.vNumber = :vNumber';

        $rs = $link->prepare($sql);
        $rs->bindValue(':vNumber',$vNumber);
        $rs->execute();
        $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = count($rows);
        if($rowCount==1){
          foreach ($rows as $rst) {
              $RoleID=$rst['RoleID'];
              $usergroup_add= $rst['usergroup_add'];
              $usergroup_update= $rst['usergroup_update'];
              $usergroup_del= $rst['usergroup_del'];
            }//end of foreach
        }//end of if
  }//end of comptence if
    if(isset($usergroup_add))if($usergroup_add=='1'){
      echo "<button type='submit' formmethod='post' formaction='../view/AddUsergroup.php'>新增權限</button>";
      //echo "<button type='submit' formmethod='post' formaction='../view/AddUsergroup.php'>新增使用者</button>";
      echo "<button type='submit' formmethod='post' formaction='../model/SetUsergroup.php?page=1'>設定使用者</button>";
    }
    echo "</form>";
        echo "<form id='form_type3'>";
    //全部資料
    $sql='SELECT * FROM role';
    $rs=$link->prepare($sql);
    //execute() 執行預處理裡面的SQL > 綁定參數
    $rs->execute();
    $rows=$rs->fetchAll(PDO::FETCH_ASSOC);
    include "../model/page.php";
    echo "<table class=\"table table-striped table-hover\">
        <tr>
        <th>群組編號</th>
        <th>群組帳號</th>
        <th>群組名稱</th>";
    if(isset($usergroup_update))if($usergroup_update=='1')echo  "<th>修改</th>";
    if(isset($usergroup_del))if($usergroup_del=='1')echo  "<th>刪除</th>";
    echo  "</tr>";
   
    if($rows)
    {
      for($i=$min;$i<$max;$i++){
        $rst=$rows[$i];
        echo "<tr>";
        echo "<td>".$rst["Roleindex"]."</td>";
        echo "<td>".$rst["RoleID"]."</td>";
        echo "<td>".$rst["RoleName"]."</td>";
        if(isset($usergroup_update))if($usergroup_update=='1')
          echo "<td><input type=\"button\" onclick=\"usergroup_update(".$rst['Roleindex'].")\" value=\"修改\"  /></td>";
            if($rst['RoleID']=='volunteer'||$rst['RoleID']=='admin')
          echo "<td>權限不能刪除</td>";
        else if(isset($usergroup_del))if($usergroup_del=='1')
          echo "<td><input type=\"button\" onclick=\"usergroup_del(".$rst['Roleindex'].")\" value=\"刪除\"  /></td>";
        echo "</tr>";
      }
    }
    else{
      echo"<tr>無資料</tr>";
    }
    echo"</table><br/>";

    $url="../model/usergroup.php";
    page_set($pagesum,$url);

?>
</div>
</body>
</html>