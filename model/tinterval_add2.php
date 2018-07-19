<!DOCTYPE html>
<?php
  session_start();
?>
<html>
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新增時段</title>
<!--CSS-->
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/form_style.css">
<!--jQuery-->
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery.validate.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>

<style>
article{
   background-image:url('../image/book.jpg');
}
</style>
<script>
 $(function() {
      //成功動作
       $( "#success" ).dialog({ 
          modal:true,buttons: { 
            "OK": function() { 
               $(this).dialog("close");
               $(this).onClick(location='../model/TimeintervalMangerment.php?page=1');
                   
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
<nav>
<!--匯入左邊索引欄-->
<?php include '../nav_control.php';?> 
</nav>
<article>
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
</article>
</body>
</html>