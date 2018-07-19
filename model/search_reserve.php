<?php
   require "../DbConnect.php";
   //側邊攔
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
   }
   else{
	   echo "接收資料失敗";
   }

   //SQL指令-顯示符合日期時段的資料
   $sql = 'SELECT t.*,s.vNumber
           FROM timeinterval t,servicerecord s
           Where t.ServiceDate=:ServiceDate
           and t.Tinterval=:Tinterval';
   $rs=$link->prepare($sql);
   $rs->bindValue(':ServiceDate',$ServiceDate);
   $rs->bindValue(':Tinterval',$Tinterval);
   $rs->execute();

   //將查詢到的資料存到變數裡
   $rows=$rs->fetchAll(PDO::FETCH_ASSOC);
   $rowCount=count($rows);
   if($rowCount){
      //將查詢到的資料存在變數裡
      foreach ($rows as $rst){
         $Time_No=$rst["Time_No"]; //時間編號
         $ServiceDate=$rst["ServiceDate"]; //預約日期
         $Tinterval=$rst["Tinterval"]; //預約時段
         $NumOfPeople=$rst["NumberOfPeople"]; //目前預約人數
         $ReserveAmount=$rst["ReserveAmount"]; //預約上限
         $note=$rst["ReserveNote"];
      }
      $i=array("","上午","下午");
   session_start();
      //查找目前登入者是否已預約過此時段
      $sql =  'SELECT s.vNumber,s.Time_No
               FROM servicerecord s
               WHERE s.Time_No=:Time_No
               AND s.vNumber=:vNumber';
      $rs=$link->prepare($sql);
      $rs->bindValue(':vNumber',$_SESSION['vNumber']);
      $rs->bindValue(':Time_No',$Time_No);
      $rs->execute();
      //$rowcount=$rs->fetchall();
      $rows=$rs->fetchAll(PDO::FETCH_ASSOC);
      $rowCount=count($rows);

      $IsReserved=($rowCount==0)?FALSE:TRUE;//資料筆數是否為0
      $_SESSION["IsReserved"]=$IsReserved;
      //echo "IsReserved = ".$IsReserved;
      //將NumOfPeople丟給cookie
      $cookie_name = "NumOfPeople";
      $cookie_value = $NumOfPeople;
      setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
      //將Time_No放入session

      //isset() 是否存在
      if(isset($Time_No)){
         $_SESSION["Time_No"]=$Time_No;
      }
      header("Location:show_reserve.php?Time_No=".$Time_No." & ServiceDate=".$ServiceDate." & Tinterval=".$Tinterval." & NumOfPeople=".$NumOfPeople." & ReserveAmount=".$ReserveAmount." & note=".$note);
   }else{
      echo "時段不可預約";
      header("Location:../view/reserve_error.html");
   }

  echo "</div>";//end main
  ?>
