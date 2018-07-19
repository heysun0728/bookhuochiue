<?php 
   session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--CSS-->
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/form_style.css">
<!-- Bootstrap -->
<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="../css/style.css" rel="stylesheet" type="text/css">
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
          modal:true,
          buttons: { 
            "OK": function() { 
              $(this).dialog("close");
              $(this).onClick(location='../model/SchoolMangerment.php?page=1'); 
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
<!--倒數秒數控制-->
<script type ="text/javascript">
  function delay(){
    var speed = 5000;
    setTimeout("history.back()", speed);
  }
</script>
<style>
label{
  width:500px;
}

</style>
<title>刪除學校資料</title>
</head>
<!--匯入左邊索引欄-->
<nav>
  <?php include '../nav_control.php';?>
</nav>
<article>
  <div class="reg_pic1">
      <img src="../image/reading.png" alt="reading"></img>
  </div>
<?php
  require "../DbConnect.php";
  //判斷權限
  if(isset($_SESSION['myID'])){
      $myID=$_SESSION['myID'];
      $sql = 'SELECT r.RoleID, c.school_del
              FROM role r, member m, comptence c
              WHERE r.RoleID = m.RoleID AND r.Roleindex = c.Roleindex AND m.id=:myID';
  
    $rs=$link->prepare($sql);
    $rs->bindValue(':myID',$myID);
    $rs->execute();
    $rst=$rs->fetch();
    
    $RoleID=$rst['RoleID'];
    $school_del=$rst['school_del'];
                
  }//end of comptence if
  if(isset($school_del))if($school_del=='1'){
    $updateNumber = $_GET['value'];
    $sql='SELECT * FROM school WHERE sindex=:updateNumber';
    $rs=$link->prepare($sql);
    $rs->bindValue(':updateNumber',$updateNumber);
    $rs->execute();
    $rst=$rs->fetch();
    if($rst){
        echo   "
              <form id=\"register_form\" name=\"form\" method=\"post\" action=\"\">
                <div class=\"form-group1\">
                  <input name=\"inputindex\" id=\"input-index\" type=\"hidden\"  value=". $rst["sindex"] ."><br/>
                    <label for=\"input-id\">學校編號：</label><br/>
                    <lable for=\"input-id\">".$rst["schoolid"]."</lable><br/>
                    <label for=\"input-name\">學校名稱：</label><br/>
                    <lable for=\"input-name\">".$rst["schoolName"]."</lable><br/>
                    <label for=\"input-city\">區域：</label><br/>
                    <lable for=\"input-city\">".$rst["City"]."</lable><br/>
                    <label for=\"input-type\">學校類型：</label><br/>
                    <lable for=\"input-type\">".$rst["Level"]."</lable><br/><br/>
                  <input type=\"submit\" name=\"button\" value=\"確定刪除\" /><br/><br/>
                </div>
              </form>
          ";//end main
    }//end of if
    if($_SERVER["REQUEST_METHOD"]=='POST'){
      if(isset($_POST['inputindex'])){
        $sindex = $_POST['inputindex'];
        $sql = 'SELECT school.schoolid
                FROM member,school
                WHERE member.School=school.schoolid
                AND sindex=:sindex';
        $rs=$link->prepare($sql);
        $rs->bindValue(':sindex',$sindex);
        $rs->execute();
        $rows=$rs->fetchAll(PDO::FETCH_ASSOC);
        $rowCount=count($rows);
        if($rowCount>0){
          echo "<div id=\"failure\" title=\"刪除失敗\">刪除失敗<br/>有學生就讀此學校<br/></div>";
          //echo "<script>delay();</script>";
        }else{
          $sql1= 'DELETE FROM school WHERE sindex=:sindex';
          $rs=$link->prepare($sql1);
          $rs->bindValue(':sindex',$sindex);
          try{
            if($rs->execute()){
              echo "<div id=\"success\" title=\"刪除成功\">刪除成功<br/></div>";
              //echo "<script>delay_success();</script>";
            }else{
             echo "<div id=\"failure\" title=\"刪除失敗\">刪除失敗<br/></div>";
             // echo "<script>delay();</script>";
            }
          }catch (PDOException $e){
            echo "<div id=\"failure\" title=\"刪除失敗\">刪除失敗<br/></div>";
           // echo "<script>delay();</script>";
            printf("DataBaseError %s",$e->getMessage());
          }
        }
      }
    }     
    
  }else{
    echo "<div id=\"failure_c\" title=\"沒有權限瀏覽\">沒有權限瀏覽<br/></div>";
    echo "<script>delay();</script>";
  }//echo "</div>";//end main
?>
</article>
</body>
</html>