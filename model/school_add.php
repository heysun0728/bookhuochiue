<?php 
  session_start(); 
  require "../DbConnect.php";
?>
<!DOCTYPE html>
<html>
<head>
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/jquery.validate.js"></script>
<link href="../css/jquery-ui.css" rel="stylesheet" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新增學校</title>
<script>
 $(function() {
      //成功動作
       $( "#success" ).dialog({ 
          modal:true,
          buttons: { 
            "OK": function() { 
               $(this).dialog("close");
               $(this).onClick(location='../model/SchoolManagement.php?page=1');
                   
            } 
          }  
        });
       //失敗動作
        $( "#failure" ).dialog({ 
          modal:true,
          buttons: { 
            "重新新增": function() { 
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
<form method="post" id="form_type1">
  <label for="schoolname">學校名稱：</laberl>
  <input id="schoolname" name="schoolname" class="form-control required" type="text" required/>
  <br/><br/>
  
  <label for="schoolid">學校編號：</laberl>
  <input id="schoolid" name="schoolid" class="form-control required" type="text" required/>
  <br/><br/>
  
  <label for="schoolcity">學校區域：</laberl>
  <select name="schoolcity">
  <?php
	   $sql = 'SELECT CityName FROM city';
	   $rs=$link->prepare($sql);
	   $rs->execute();
	   while($row=$rs->fetch()){
	     echo "<option value=".$row["CityName"].">".$row["CityName"]."</option>";
	   }//end of while
  ?>
  </select><br/>
  <label for="schooltype">學校類型：</laberl>
  <?php
   echo "<select name=\"schooltype\">";
          $sql = 'SELECT * FROM SchoolLevel';
          $rs=$link->prepare($sql);
          $rs->execute();
          while($row=$rs->fetch()){
            echo "<option value=".$row["SLtype"].">".$row["SLtype"]."</option>";
          }//end of while
          echo "</select><br/>";
  ?>

  
  <input type="submit" value="新增"/>
</form>
<?php
   //接收form傳過來的資料
   if (isset($_POST['schoolname'])) {
      if(isset($_POST['schoolid']) && isset($_POST['schoolcity']) && isset($_POST['schooltype'])){
        $schoolid=$_POST['schoolid']; 
        $schoolname=$_POST['schoolname'];
        $schoolcity=$_POST['schoolcity'];
        $schooltype=$_POST['schooltype']; 
        //新增資料
        $insertData=array($schoolid,$schoolname,$schoolcity,$schooltype);
        $sql3='INSERT INTO school (schoolid, schoolName, City, Level) VALUES (?,?,?,?)';
        $sth=$link->prepare($sql3);
        try{
           if($sth->execute($insertData)){
              echo "<div id=\"success\" title=\"新增成功\">新增成功<br/></div>";
           }else{
              echo "<div id=\"failure\" title=\"新增失敗\">新增失敗<br/></div>";
              echo "<script>delay();</script>";
           }
        }catch (PDOException $e){
          echo "<div id=\"failure\" title=\"新增失敗\">新增失敗<br/></div>";
          echo "<script>delay();</script>";
        }        
      }
      else{
        echo "接收資料失敗<br/><br/>";
      }
   } 
?>



</div>
</body>
</html>