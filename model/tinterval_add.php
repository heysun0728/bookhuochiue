<?php 
    session_start(); 
    $id = $_SESSION['myID'];//接收目前登入身分ID
    $vNumber=$_SESSION['vNumber'];//讀取目前進入者的(志工)編號
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
<title>新增時段</title>
<script>
 $(function() {
      //成功動作
       $( "#success" ).dialog({ 
          modal:true,buttons: { 
            "OK": function() { 
               $(this).dialog("close");
               $(this).onClick(location='../model/TintervalManagement.php?page=1');
                   
            } 
          }  
        });
       //失敗動作
        $( "#failure" ).dialog({ 
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
<?php
   require "../DbConnect.php";
   //接收form傳過來的資料
   if ($_SERVER["REQUEST_METHOD"]=='POST') {
      if(isset($_POST['tintervalname'])){
         $tintervalname=$_POST['tintervalname'];
         echo "tintervalname = ".$tintervalname;
      }
      if(isset($_POST['starthour'])){
         $starthour=$_POST['starthour'];
         echo "<br/starthour = ".$starthour;
      }
      if(isset($_POST['endhour'])){
         $endhour=$_POST['endhour'];
         echo "<br/>endhour = ".$endhour;
      }
      $hours=$endhour-$starthour;
      
   }
   else{
      echo "接收資料失敗<br/>";
   }
   echo "<br/>";
   //新增資料
      $insertData=array($tintervalname,$starthour,$endhour,$hours);
      $sql='INSERT INTO tinterval (tName, StartHour, EndHour, Hours) VALUES (?,?,?,?)';
      $sth=$link->prepare($sql);
      try{
         if($sth->execute($insertData)){
            echo "<div id=\"success\" title=\"新增成功\">新增成功<br/></div>";
         }else{
            echo "<div id=\"failure\" title=\"新增失敗\">新增失敗<br/></div>";
         }
      }catch (PDOException $e){
         echo "<div id=\"failure\" title=\"新增失敗\">新增失敗<br/></div>";
               //echo "<script>delay();</script>";
      }
?>

</div>
</body>
</html>