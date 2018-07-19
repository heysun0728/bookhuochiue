<?php 
    require "../DbConnect.php";
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
 $(function(){
    //成功動作
    $( "#success" ).dialog({ 
      modal:true,buttons: { 
        "OK": function() { 
           $(this).dialog("close");
           $(this).onClick(location='../model/TimeintervalManagement.php?page=1');
               
        } 
      }  
    });
     //失敗動作
      $( "#failure" ).dialog({ 
                  buttons: { 
          "再次新增": function() { 
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
   $NumOfPeople=$ReserveAmount=$note="";
   //接收form傳過來的資料
   if ($_SERVER["REQUEST_METHOD"]=='POST') {
     //$_POST[name]取得post來的資料
     if(isset($_POST['date-input'])){
        $ServiceDate=$_POST['date-input'];
        echo "ServiceDate = ".$ServiceDate;
     }
     if(isset($_POST['Tinterval-input'])){
        $Tinterval=$_POST['Tinterval-input'];
        echo "<br/>Tinterval = ".$Tinterval;
     }
      if(isset($_POST['input-reserveAmount'])){
        $ReserveAmount=$_POST['input-reserveAmount'];
        echo "<br/>ReserveAmount = ".$ReserveAmount;
      }
      if(isset($_POST['input-note'])){
         $ReserveNote=$_POST['input-note'];
         echo "<br/>ReserveNote = ".$ReserveNote;
      }
   }
   else{
     echo "接收資料失敗<br/>";
   }
   echo "<br/>";
   //判斷此時段是否已有資料
   $sql = 'SELECT * FROM timeinterval
           WHERE ServiceDate = :ServiceDate
           AND Tinterval =:Tinterval';
   $rs=$link->prepare($sql);
   //bindValue是與變數當時的值有關，即使變動後，綁定的值也不會變動
   $rs->bindValue(':ServiceDate',$ServiceDate);
   $rs->bindValue(':Tinterval',$Tinterval);
   $rs->execute();//預處理操作 來執行預處理裡面的SQL語法>可以綁定參數
   $rows=$rs->fetchAll(PDO::FETCH_ASSOC);
   $rowCount=count($rows);
   if($rowCount){ //有資料筆數則不為0
    echo "<div id=\"failure\" title=\"新增失敗\">新增失敗<br/></div>";
   }else{
      //新增資料
      $insertData=array($ServiceDate,$Tinterval,0,$ReserveAmount,$ReserveNote);
    //$insertData=array('2017-01-01',1,0,8,'');
      $sql='INSERT INTO timeinterval (ServiceDate, timeid, NumberOfPeople, ReserveAmount, ReserveNote) VALUES (?,?,?,?,?)';
      $sth=$link->prepare($sql);
      try{
         if($sth->execute($insertData)){
            echo "<div id=\"success\" title=\"新增成功\">新增成功<br/></div>";
         }else{
            echo "<div id=\"failure\" title=\"新增失敗\">新增失敗<br/></div>";
         }
      }catch (PDOException $e){
        echo "<div id=\"failure\" title=\"新增失敗\">新增失敗".$e."<br/></div>";
      }

   }
?>
</div>
</body>
</html>