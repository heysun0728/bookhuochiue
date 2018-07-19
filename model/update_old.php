<!DOCTYPE html>
<?php
  session_start();
  $vNumber = $_SESSION['vNumber'];//讀取目前進入者的(志工)編號
  $name = $_SESSION['name'];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--CSS-->
<link rel="stylesheet" type="text/css" href="../js/regcheck.js">
<link rel="stylesheet" type="text/css" href="../css/form_style.css">
<link href="../css/style.css" rel="stylesheet" type="text/css" >
<!--jQuery-->
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery.validate.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<!--dialog-->
<script>
$(function() {
      //成功動作
       $( "#success" ).dialog({ 
          modal:true,buttons: { 
            "OK": function() { 
               $(this).dialog("close");
               $(this).onClick(location='../index_old.php');
                   
            } 
          }  
        });
       //失敗動作
        $( "#failure" ).dialog({ 
          buttons: { 
            "修改失敗": function() { 
               $(this).dialog("close");
               $(this).onClick(location='../index_old.php'); 
            } 
          }  
        });
        //沒有權限
        $( "#failure_c" ).dialog({ 
          buttons: { 
            "沒有權限": function() { 
               $(this).dialog("close");
               $(this).onClick(location='../index.php'); 
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
</script>
<title>編輯個人資料</title>
</head>
<body>
<!--匯入左邊索引欄-->
<nav>
<?php include '../nav_control.php';?> 
</nav>
<article>
<div class="reg_pic1">
    <img src="../image/reading.png" alt="reading" ></img>
</div>
<?php

  //判斷權限
    if(isset($_SESSION['myID'])){
        $myID=$_SESSION['myID'];
        $vNumber=$_SESSION['vNumber'];
        $sql = 'SELECT role.RoleID FROM role, member, comptence
                WHERE  role.RoleID = member.RoleID AND role.Roleindex = comptence.Roleindex AND member.vNumber=:vNumber';

        $rs=$link->prepare($sql);
        $rs->bindValue(':vNumber',$vNumber);
        $rs->execute();
        $rst=$rs->fetch();
        $RoleID=$rst['RoleID'];
       
    }//end of comptence if

  if(isset($RoleID)){
    if($RoleID == 'volunteer'){
      $updateNumber = $vNumber;
    }else{
      $updateNumber = $_GET['value'];  
    }
    
    $sql = "SELECT * FROM member m
            WHERE m.vNumber=:updateNumber";
    $rs=$link->prepare($sql);
    $rs->bindValue(':updateNumber',$updateNumber);
    $rs->execute();
    $rst=$rs->fetch();
    
    echo "<form id='register_form' name='form' method='post'>
            <div class=\"form-group1\">
              <b>帳號：(無法修改)</b><br/>". $rst["ID"] ."<br/><br/>
              <label for=\"input-phone\">電話：</label><br/>
              <input name=\"inputphone\" id=\"input-phone\" type=\"tel\"  value=".$rst["Phone"]." /> <br/>
              <label for=\"input-email\">電子郵件：</label><br/>
              <input name=\"inputemail\" id=\"input-email\" type=\"email\"  value=".$rst["Email"]." /><br/>
              <label>生日(無法修改)</label><br/>".$rst["Birthday"] ."<br/><br/>
              <label>身分證字號(無法修改)</label><br/>". $rst["IDNumber"] ."<br/><br/>
            </div>";
        if($rst["RoleID"] == 'volunteer'){
          echo "<div class=\"form-group2\">";
          //學校
          echo "就讀學校：<br/>";
          $school=$rst['School'];
          echo "<select name=\"select_school\">";
          $sql = 'SELECT * FROM school';
          $rs=$link->prepare($sql);
          $rs->execute();
          while($row=$rs->fetch()){
            if($row["schoolid"]==$school)echo "<option selected value='".$row["schoolid"]."'>".$row["schoolName"]."</option>";
            else echo "<option value='".$row["schoolid"]."'>".$row["schoolName"]."</option>";
          }//end of while
          echo "</select><br/>";
          //監護人姓名
          echo "<label for=\"input-pname\">監護人姓名：</label><br/>
                <input name=\"inputpname\" id=\"input-pname\" type=\"text\"  value=".$rst["ParentName"]." /><br/>";
          //監護人電話
          echo "<label for=\"input-pphone\">監護人電話：</label><br/>
                <input name=\"inputpphone\" id=\"input-pphone\" type=\"text\"  value=".$rst["ParentPhone"]." /><br/>";
          //與監護人關係
          echo "<label for=\"input-relationship\">監護人關係：</label><br/>
                <input name=\"inputprelationship\" id=\"input-relationship\" type=\"text\"  value=".$rst["ParentRelationship"]." />
                </div>";
        
}//end of roleID=volunteer
        //訊息
        echo "<div style=\"padding-top: 150%;margin-left: -110px;\"><h3>如欲修改(無法修改)之項目，請洽館員!!</h3></div>";
        echo "<input class ='form-group3' type=\"submit\" name=\"button\" value=\"確定修改\" /><br/><br/>";
        echo "</form>";
        
    
   }//end of isset roleID

  //TODO update form
  if($_SERVER["REQUEST_METHOD"] == 'POST'){
    if(isset($_POST['inputphone'])){ 
      $phone = trim($_POST['inputphone']);
    }
    else{
      echo "123321";
    }
    if(isset($_POST['inputemail'])) $email = trim($_POST['inputemail']);
    if(isset($_POST['select_school'])) $school = trim($_POST['select_school']);
    if(isset($_POST['inputprelationship']))$prelation = trim($_POST['inputprelationship']);
    if(isset($_POST['inputpname']))$pname = trim($_POST['inputpname']);
    if(isset($_POST['inputpphone']))$pphone = trim($_POST['inputpphone']);

    //TODO:set schoolid to school
      $sql = 'SELECT schoolid FROM school WHERE schoolName=:schoolName';
      $rs = $link->prepare($sql);
      $rs->bindValue(':schoolName',$school);
      $rs->execute();
      $row = $rs->fetch();
      $school = $row["schoolid"];

    if(isset($ppname)&&isset($pphone)&&isset($prelation)){
      
      $sql='UPDATE member 
            SET School=:school, Phone=:phone, Email=:mail, ParentName=:pname, ParentRelationship=:prelation, ParentPhone=:pphone 
            WHERE vNumber=:updateNumber';
      $rs=$link->prepare($sql);
      $rs->bindValue(':school',$school);
      $rs->bindValue(':phone',$phone);
      $rs->bindValue(':mail',$mail);
      $rs->bindValue(':pname',$pname);
      $rs->bindValue(':prelation',$prelation);
      $rs->bindValue(':pphone',$pphone);
      $rs->bindValue(':updateNumber',$updateNumber);
    }else{
      $sql = 'UPDATE member SET Phone=:phone, Email=:mail 
              WHERE vNumber=:updateNumber';
      $rs=$link->prepare($sql);
      $rs->bindValue(':updateNumber',$updateNumber);
      $rs->bindValue(':phone',$phone);
      $rs->bindValue(':mail',$email);
          
    }//END OF IF
    try{
      if($rs->execute()){
        echo "<div id=\"success\" title=\"修改成功\">修改成功<br/></div>";
      }else{
          echo "<div id=\"failure\" title=\"修改錯誤\">修改錯誤<br/></div>";
          
      }

    }catch (PDOException $e){
      echo "<script>window.location.href='$url'</script>"; 
      printf("DataBaseError %s",$e->getMessage());
    }//END OF TRY
    
    echo "</div>";//end mian

    }//end of if
  
 
 ?>

</article>
</body>
</html>