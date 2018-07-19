<?php 
    session_start(); 
    require "../DbConnect.php";
    $id = $_SESSION['myID'];//接收目前登入身分ID
    $vNumber=$_SESSION['vNumber'];
    $name=$_SESSION['name'];
?>
<!DOCTYPE html>
<html>
<head>
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/jquery.validate.js"></script>
<link href="../css/jquery-ui.css" rel="stylesheet" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>編輯個人資料</title>
<script>
function express(v){
	location.href="update.php?value=" + v;
}
$(document).ready(function(){
   	//成功動作(管理端修改志工資料)
    $( "#success_m" ).dialog({ 
      modal:true,
      show:true,
      closeOnBg: true,
      buttons: { 
        "OK": function() { 
           $(this).dialog("close");
           $(this).onClick(location='../model/memberview.php?page=1');
               
        } 
      }  
    });
    //成功動作(自行修改)
    $( "#success_v" ).dialog({ 
      modal:true,
      buttons: { 
        "OK": function() { 
           $(this).dialog("close");
           $(this).onClick(location='../model/update.php');
               
        } 
      }  
    });

  //錯誤
    $( "#failure" ).dialog({ 
      modal:true,
      buttons: { 
        "重新修改": function() { 
           $(this).dialog("close");
           $(this).onClick(go(-2));
               
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
    if(isset($_SESSION['vNumber'])){
        $vNumber=$_SESSION['vNumber'];
        $sql = 'SELECT role.RoleID FROM role, member, comptence
                WHERE  role.RoleID = member.RoleID AND role.Roleindex = comptence.Roleindex AND member.vNumber=:vNumber';

        $rs=$link->prepare($sql);
        $rs->bindValue(':vNumber',$vNumber);
        $rs->execute();
        $rst=$rs->fetch();
        $RoleID=$rst['RoleID'];//目前登入權限類別
       
    }//end of comptence if

    if(isset($RoleID)){
      //如果有get值
      if(isset($_SESSION['updateNumber'])){
        $updateNumber=$_SESSION['updateNumber'];
        unset($_SESSION['updateNumber']);
      }else if($_SERVER['REQUEST_METHOD']=="GET"&&isset($_GET['value'])){
	      	$updateNumber = $_GET['value'];
          $_SESSION['updateNumber']=$updateNumber;
	    }else if($RoleID == 'volunteer'){//志工
	      	$updateNumber = $vNumber;
          $_SESSION['updateNumber']=$updateNumber;
      }else{
        //管理端個人資料
	    	$updateNumber = $vNumber;
        $_SESSION['updateNumber']=$updateNumber;
      }
	    $sql = "SELECT * FROM member m WHERE m.vNumber=:updateNumber";
	    $rs=$link->prepare($sql);
	    $rs->bindValue(':updateNumber',$updateNumber);
	    $rs->execute();
	    $rst=$rs->fetch();
	    $update_RoleID=$rst["RoleID"];
	    echo "<form id='form_type1' name='form' method='post'>
	            <div class=\"form-group1\">
                <div class=\"itemicon\"></div><label>姓名：</label>".$rst["Name"]."<br/>
                
	              <div class=\"itemicon\"></div><label for=\"input-phone\">電話：</label>
	              <input name=\"inputphone\" id=\"input-phone\" type=\"tel\"  value=".$rst["Phone"]." /> <br/>
	              <div class=\"itemicon\"></div><label for=\"input-email\">電子郵件：</label>
	              <input name=\"inputemail\" id=\"input-email\" type=\"email\"  value=".$rst["Email"]." /><br/>
	            </div>";
        if($update_RoleID== 'volunteer'){
          ?>
          	<div class="form-group2">
          	<div class="itemicon"></div>就讀學校：
          	<select name="select_school">
          	<?php
            $school=$rst['School'];
            
            $sql = 'SELECT * FROM school';
          	$rs=$link->prepare($sql);
          	$rs->execute();
          	while($row=$rs->fetch()){
            	if($row["schoolid"]==$school)echo "<option selected value='".$row["schoolid"]."'>".$row["schoolName"]."</option>";
            	else echo "<option value='".$row["schoolid"]."'>".$row["schoolName"]."</option>";
          	}//end of while
          	echo "</select><br/>";
          	//監護人姓名
          	echo   "<div class=\"itemicon\"></div><label for=\"input-pname\">監護人姓名：</label>
                	<input name=\"inputpname\" id=\"input-pname\" type=\"text\"  value=".$rst["ParentName"]." /><br/>";
          	//監護人電話
          	echo   "<div class=\"itemicon\"></div><label for=\"input-pphone\">監護人電話：</label>
                	<input name=\"inputpphone\" id=\"input-pphone\" type=\"text\"  value=".$rst["ParentPhone"]." /><br/>";
          	//與監護人關係
          	echo 	"<div class=\"itemicon\"></div><label for=\"input-relationship\">監護人關係：</label>
                	<input name=\"inputprelationship\" id=\"input-relationship\" type=\"text\"  value=".$rst["ParentRelationship"]." />
                	</div>";
            echo 	"<input type = \"hidden\"name=\"RID\" value=\"volunteer\"/>";
        }//end of roleID=volunteer
        echo "<h3>如欲修改(無法修改)之項目，請洽館員!!</h3>";
        echo "<input class ='form-group3' type=\"submit\" name=\"button\" value=\"確定修改\" />";
        echo "</form>";

   }//end of isset roleID

    //TODO update form
  	if(isset($_POST['RID'])){
  		if(isset($_POST['RID']))$update_RoleID = trim($_POST['RID']);
	    if(isset($_POST['inputphone']))$phone = $_POST['inputphone'];
	    
	    if(isset($_POST['inputemail'])) $email = trim($_POST['inputemail']);
	    if(isset($_POST['select_school'])) $school = trim($_POST['select_school']);
	    if(isset($_POST['inputprelationship']))$prelation = trim($_POST['inputprelationship']);
	    if(isset($_POST['inputpname']))$pname = trim($_POST['inputpname']);
	    if(isset($_POST['inputpphone']))$pphone = trim($_POST['inputpphone']);
      //print_r($_POST);
	    /*
      //TODO:set schoolid to school
	      $sql = 'SELECT schoolid FROM school WHERE schoolName=:schoolName';
	      $rs = $link->prepare($sql);
	      $rs->bindValue(':schoolName',$school);
	      $rs->execute();
	      $row = $rs->fetch();
	      $school = $row["schoolid"];
*/
	    if($update_RoleID=='volunteer'){
	      
	      $sql='UPDATE member 
	            SET School=:school, Phone=:phone, Email=:mail, ParentName=:pname, ParentRelationship =:prelation, ParentPhone=:pphone 
	            WHERE vNumber=:updateNumber';
	      $rs=$link->prepare($sql);
	      $rs->bindValue(':school',$school);
	      $rs->bindValue(':phone',$phone);
	      $rs->bindValue(':mail',$email);
	      $rs->bindValue(':pname',$pname);
	      $rs->bindValue(':prelation',$prelation);
	      $rs->bindValue(':pphone',$pphone);
	      $rs->bindValue(':updateNumber',$updateNumber);
	      //print_r($phone);
	    }else{
	      $sql = 'UPDATE member SET Phone=:phone, Email=:mail WHERE vNumber=:updateNumber';
	      $rs=$link->prepare($sql);
	      $rs->bindValue(':updateNumber',$updateNumber);
	      $rs->bindValue(':phone',$phone);
	      $rs->bindValue(':mail',$email);
	     
	          
	    }//END OF IF
	    try{
	      if($rs->execute()){
	      	if($RoleID=='volunteer'){
	        	echo "<div id=\"success_v\" title=\"修改成功\">修改成功<br/></div>";
          }elseif($update_RoleID=='volunteer'){
	        	echo "<div id=\"success_m\" title=\"修改成功\">修改成功<br/></div>";
          }else{
            echo "<div id=\"success_v\" title=\"修改成功\">修改成功<br/></div>";
          }
	      }else{
	          echo "<div id=\"failure\" title=\"修改錯誤\">修改錯誤<br/></div>";
	      }

	    }catch (PDOException $e){
	      echo "<script>window.location.href='$url'</script>"; 
	      printf("DataBaseError %s",$e->getMessage());
	    }//END OF TRY
    
    }//end of if
?>
</div>
</body>
</html>