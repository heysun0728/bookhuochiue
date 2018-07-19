<!DOCTYPE html >
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/form_style.css">
    <link href="../css/style.css" rel="stylesheet" type="text/css">
    <title>編輯個人資料</title>
    <script type="text/javascript">
function hideOverflow(){
    document.getElementById("nac").style.overflow="hidden";
}
</script>
<!--讓學校下拉選單預設值是原本的選項-->
<script type="text/javascript">document.form.select_school.value = '<?php echo $rst["School"]?>';</script>
</head>

<body>
    <?php
	require "../DbConnect.php";
	session_start();
 	$vNumber=$_SESSION['vNumber'];
  	$name=$_SESSION['name'];
?>
<!--匯入左邊索引欄-->
<nav id="nav"><?php include '../nav_control.php';?></nav>
<article>
    <div class="reg_pic1">
        <img src="../image/reading.png" alt="reading"></img>
    </div>
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
    echo "<form name=\"form\" method=\"post\" action=\"update.php\" id='register_form'>";
    echo "<div class='form-group1'>";
    echo "<b>帳號：</b> <br/>";
    echo "". $rst["ID"] ."<br/><br/>";
	echo "<b>姓名：</b><br/>";
    echo "". $rst["Name"] ."<br/><br/>";
    echo "<b>電話</b>：<br/>";
    echo "".$rst["Phone"]."<br/><br/>";
    echo "<b>電子郵件：</b><br/>";
    echo "".$rst["Email"]."<br/><br/>";
    echo "<b>生日：</b><br/>";
	echo "".$rst["Birthday"]."<br/><br/>";
	echo "<b>身分證字號：</b><br/>";
	echo "".$rst["IDNumber"]."<br/><br/>";
    echo "</div>";

    if($rst["RoleID"]=='volunteer'){
	    echo "<div class='form-group2'>";
	    echo "<b>就讀學校：</b><br/>";
	    echo "".$rst["schoolName"]."<br/><br/>";
	    echo "<b>監護人姓名：</b><br/>";
		echo "".$rst["ParentName"]."<br/><br/>";
		echo "<b>監護人電話：</b><br/>";
		echo "".$rst["ParentPhone"]."<br/><br/>";
		echo "<b>與監護人關係：</b><br/>";
		echo "".$rst["ParentRelationship"]."<br/><br/>";
		echo "</div>";
	}
  
	echo "<div class='form-group3'>";
	echo "<input type=\"submit\" name=\"button\" value=\"編輯\" onclick=\"toggle_visibility('MSG')\"/><br/><br/>";
	echo "</div>";
	echo "</form>";
		
	
?>
        </article>
</body>

</html>