<?php 
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
<title>個人資料</title>
<script>
$(document).ready(function(){
    $( "#success" ).dialog({ 
        modal:true,buttons: { 
            "OK": function() { 
               $(this).dialog("close");
            } 
        }  
    });
    $( "#failure" ).dialog({ 
        buttons: { 
            "修改失敗": function() { 
                $(this).dialog("close");
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
    //判斷權限
      if(isset($_SESSION['myID'])){
        $myID=$_SESSION['myID'];
        $vNumber=$_SESSION['vNumber'];
        require "../DbConnect.php";

        $sql = 'SELECT role.RoleID FROM role, member, comptence
                WHERE  role.RoleID = member.RoleID AND role.Roleindex = comptence.Roleindex AND member.vNumber=:vNumber';

        $rs=$link->prepare($sql);
        $rs->bindValue(':vNumber',$vNumber);
        $rs->execute();
        $rst=$rs->fetch();
        $RoleID=$rst['RoleID'];
      
    }//end of comptence if
    
    if($RoleID=='volunteer'){
    	$sql='SELECT * FROM member,school WHERE member.vNumber=:vNumber AND school.schoolid = member.School';
    }else{
    	$sql='SELECT * FROM member WHERE member.vNumber=:vNumber';
    }
	//prepare() 預處理操作
	$rs=$link->prepare($sql);
	$rs->bindValue(':vNumber',$vNumber);
	//execute() 執行預處理裡面的SQL > 綁定參數
	$rs->execute();
	$rst=$rs->fetch();

	$_SESSION['name']=$rst["Name"];
    echo "<form name=\"form\" method=\"post\" action=\"update.php\" id='form_type1'>";
    echo "<div class='form-group1'>";
    echo "<div class=\"itemicon\"></div><label>帳號：</label> ";
    echo "". $rst["ID"] ."<br/>";
	echo "<div class=\"itemicon\"></div><label>姓名：</label>";
    echo "". $rst["Name"] ."<br/>";
    echo "<div class=\"itemicon\"></div><label>電話</label>：";
    echo "".$rst["Phone"]."<br/>";
    echo "<div class=\"itemicon\"></div><label>電子郵件：</label>";
    echo "".$rst["Email"]."<br/>";
    echo "<div class=\"itemicon\"></div><label>生日：</label>";
	echo "".$rst["Birthday"]."<br/>";
	echo "<div class=\"itemicon\"></div><label>身分證字號：</label>";
	echo "".$rst["IDNumber"]."<br/>";
    echo "</div>";

    if($rst["RoleID"]=='volunteer'){
	    echo "<div class='form-group2'>";
	    echo "<div class=\"itemicon\"></div><label>就讀學校：</label>";
	    echo "".$rst["schoolName"]."<br/>";
	    echo "<div class=\"itemicon\"></div><label>監護人姓名：</label>";
		echo "".$rst["ParentName"]."<br/>";
		echo "<div class=\"itemicon\"></div><label>監護人電話：</label>";
		echo "".$rst["ParentPhone"]."<br/>";
		echo "<div class=\"itemicon\"></div><label>與監護人關係：</label>";
		echo "".$rst["ParentRelationship"]."<br/><br/>";
		echo "</div>";
	}
  
	echo "<div class='form-group3'>";
	echo "<input type=\"submit\" name=\"button\" value=\"編輯\" onclick=\"toggle_visibility('MSG')\"/><br/><br/>";
	echo "</div>";
	echo "</form>";
?>   
</div>
</body>
</html>