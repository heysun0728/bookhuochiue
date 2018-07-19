<?php 
   session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--CSS-->
<link href="../css/style.css" rel="stylesheet" type="text/css" >
<link href="../css/form_style.css" rel="stylesheet" type="text/css">
<style>
article{
   background-image:url('../image/book.jpg');
}
</style>
<script>
   function show_reserve(){
       document.getElementById("block2p").style.display="none";
       document.getElementById("res_form2").style.display="block";
       return true;
   }
   Date.prototype.toDateInputValue = (function() {
    var local = new Date(this);
    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
    return local.toJSON().slice(0,10);
});
   document.getElementById('date-input').value = new Date().toDateInputValue();
</script>
</head>
<body>
<nav>
<!--匯入左邊索引欄-->
<?php include '../nav_control.php';?> 
</nav>
<article>
<div class="block3"></div>
<form method="post" action="" id ="loginform" onsubmit="return show_reserve()">
  <div class="loginform_p">
  日期:
  <input name="date-input" value="<?php echo date('Y-m-d'); ?>" type="date"/><br>
  時段:
  <select name="Tinterval-input">
     <option value="1">上午</option>
	 <option value="2">下午</option>
  <select><br><br>
  <input type="submit" value="查詢" />  
</div>
</form>
<br><br>

<?php
   require "../DbConnect.php";
   $NumOfPeople=$ReserveAmount=$note=$ServiceDate="";
   $Tinterval=0;
   $i=array("","上午","下午");
   if(isset($_POST['date-input']) && isset($_POST['Tinterval-input'])){
       $ServiceDate=$_POST['date-input'];
       $Tinterval=$_POST['Tinterval-input'];
  
       require "../DbConnect.php";
       //SQL指令-顯示符合日期時段的資料
       $sql = 'SELECT t.*,s.vNumber	
               FROM timeinterval t,servicerecord s
               Where t.ServiceDate=:ServiceDate
               and t.Tinterval=:Tinterval';
       $rs=$link->prepare($sql);
       $rs->bindValue(':ServiceDate',$ServiceDate);
       $rs->bindValue(':Tinterval',$Tinterval);
       $rs->execute();
   
       //將查詢到的資料存在變數裡
       $row=$rs->fetch();
       $Time_No=$row["Time_No"]; 
       $ServiceDate=$row["ServiceDate"];
       $Tinterval=$row["Tinterval"]; 
       $NumOfPeople=$row["NumberOfPeople"];
       $ReserveAmount=$row["ReserveAmount"];
       $note=$row["ReserveNote"];
       //查找目前登入者是否已預約過此時段
       $sql = 'SELECT s.vNumber,s.Time_No
               FROM servicerecord s
               WHERE s.Time_No=:Time_No
               AND s.vNumber=:vNumber';
       $rs=$link->prepare($sql);
       $rs->bindValue(':vNumber',$_SESSION["vNumber"]);
       $rs->bindValue(':Time_No',$Time_No);
       $rs->execute();
       $rowcount=$rs->fetchall();
       $IsReserved=(count($rowcount)==0)?FALSE:TRUE;//資料筆數是否為0
       $row=NULL;
   }
   
?>


<!--顯示查詢出的資料-->
<div class="block2">
<form id="res_form2" method="post" action="reservefinish.php">
   <h2><?php echo $ServiceDate;?> <?php echo $i[$Tinterval];?></h2>
   現在人數:<?php echo $NumOfPeople;?><br>
   人數上限:<?php echo $ReserveAmount;?><br>
   備註:<?php echo $note;?><br>
   <?php
      if($_SESSION['MemberLevel']=='1'){
      if((int)$NumOfPeople<(int)$ReserveAmount){
        if($IsReserved){
          echo "訊息<br/>"; 
          echo "您已預約過此時段";
        }
        else{
          echo "<br><input name='submit' type='submit' value='預約'/>";
        }
      }  
      elseif(!empty($NumOfPeople)){//若資料存在並且預約人數=人數上限
          echo "訊息<br/>"; 
          echo "人數已滿";
      }
    }
   ?>
</form>
</div>
<?php 
   //將NumOfPeople丟給cookie
   $cookie_name = "NumOfPeople";
   $cookie_value = $NumOfPeople;
   setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
  
  //將Time_No放入session
   if(isset($Time_No)){
	  $_SESSION["Time_No"]=$Time_No;
   }
?>
</article>
</body>
</html>