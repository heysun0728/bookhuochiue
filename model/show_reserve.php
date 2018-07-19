<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--CSS-->
<link href="../css/style.css" rel="stylesheet" type="text/css" >
<head>
<?php
  session_start(); 
  $i=array("","上午","下午");
  $Time_No = $_GET['Time_No'];
  $ServiceDate = $_GET['ServiceDate']; 
  $Tinterval = $_GET['Tinterval']; 
  $NumOfPeople = $_GET['NumOfPeople']; 
  $ReserveAmount = $_GET['ReserveAmount']; 
  $note = $_GET['note'];
  $IsReserved=(bool)$_SESSION["IsReserved"];
?>
<?php //將NumOfPeople丟給cookie
   $cookie_name = "NumOfPeople";
   $cookie_value = $NumOfPeople;
   setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
?>
<?php //將Time_No放入session
   if(isset($Time_No)){
     $_SESSION["Time_No"]=$Time_No;
   }
   if(isset($_SESSION['MemberLevel'])){
    $MemberLevel=$_SESSION['MemberLevel'];
   }
?>
</head>
<body>
<nav>
<!--匯入左邊索引欄-->
<?php include '../nav_control.php';?> 
</nav>
<article>
<!--顯示查詢出的資料-->
<form id="form2" method="post" action="reservefinish.php">
   預約詳細資料<br/>
   日期:<?php echo  $ServiceDate;?><br/>
   時段:<?php echo $i[(int)$Tinterval];?><br/>
   現在人數:<?php echo $NumOfPeople;?><br/>
   人數上限:<?php echo $ReserveAmount;?><br/>
   備註:<?php echo $note;?><br/><br/>
   <?php
   echo "<div>";
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
      elseif(isset($NumOfPeople)){//若資料存在並且預約人數=人數上限
          echo "訊息<br/>"; 
          echo "人數已滿";
      }
    }
?>

</form>
</article>
</body>
</html>