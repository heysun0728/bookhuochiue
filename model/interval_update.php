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
<title>修改時段</title>
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
          "再次修改": function() { 
             $(this).dialog("close");
          } 
        }  
      });
});
</script>
<!--讓下拉選單預設值是原本的選項-->
<script type="text/javascript">
document.form.limitnumber.value = '<?php echo $rst["ReserveAmount"]?>';
</script>
<script type="text/javascript">
document.form.TIntervalList.value = '<?php echo $rst["Tinterval"]?>';
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
      if(isset($_SESSION['myID'])){
        $myID=$_SESSION['myID'];
        $vNumber=$_SESSION['vNumber'];
          require "../DbConnect.php";

        $sql = 'SELECT * FROM comptence_view WHERE comptence_view.vNumber=:vNumber';
        $rs=$link->prepare($sql);
        $rs->bindValue(':vNumber',$vNumber);
        $rs->execute();
        $rst=$rs->fetch();
        if($rst){
          $interval_update = $rst['interval_update'];
        }//end of if
}//end of comptence if
  if(isset($interval_update))if($interval_update=='1'){
    $updateNumber = $_GET['value'];
    
    $sql = ' SELECT t1.Time_No, t1.ServiceDate, t1.NumberOfPeople, 
                    t2.tName, t1.ReserveAmount, t1.ReserveNote 
              FROM  timeinterval t1, tinterval t2
              WHERE t1.timeid=t2.timeid AND t1.Time_No=:updateNumber';
    $rs=$link->prepare($sql);
    $rs->bindValue(':updateNumber',$updateNumber);
    $rs->execute();
    $rows=$rs->fetchAll(PDO::FETCH_ASSOC);
    $rowCount=count($rows);
    if($rowCount){
      foreach ($rows as $rst) {
        ?>
        <form id="form_type1" name="form" method="post" >
          <div class="form-group"><label for="input-id">時段編號：(無法修改)</label>
          <input name="inputid" id="input-id" type="text"  value="<?php echo $rst["Time_No"];?>" ReadOnly/><br/></div>
          <div class="form-group"><label for="date">日期：(無法修改)</label>
          <input type="date" value="<?php echo $rst["ServiceDate"];?>" disabled/><br/></div>
          <div class="form-group"><label for="peoplenumber\">目前預約人數：(無法修改)</label>
          <input type="text" value="<?php echo $rst["NumberOfPeople"]; ?>" disabled/><br/></div>

          <label for="TIntervalList">時段：(無法修改) </label>
          <select id="TIntervalList" name="Tinterval" value="<?php echo $rst["tName"];?>" disabled>
                <option></option>
          </select><br/>

        <div class="form-group"><label for="limitnumber">人數上限：</label>
        <select id="limitnumber" name="limitnumber" value="<?php echo $rst["ReserveAmount"];?>">
          <?php for($i = 1;$i<=20;$i++){echo "<option value=$i>$i</option>";} ?>
        </select>
        <div class="form-group"><label for="input_note">備註：</label>
          <textarea name="input_note" id="input_note" value="<?php $rst["ReserveNote"];?>"></textarea><br/>
        </div>
        <input type="submit" name="button" value="確定修改" /><br/><br/>
        </form>
        <?php
        //interval_update
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            $Time_No = $_POST['inputid'];
            $ReserveAmount = $_POST['limitnumber'];
            $ReserveNote = $_POST['input_note'];
            $sql = 'UPDATE timeinterval SET ReserveAmount=:ReserveAmount,ReserveNote=:ReserveNote WHERE Time_No=:Time_No';
            $rs=$link->prepare($sql);
            $rs->bindValue(':Time_No',$Time_No);
            $rs->bindValue(':ReserveAmount',$ReserveAmount);
            $rs->bindValue(':ReserveNote',$ReserveNote);
            //echo "Time_No:".$Time_No." ReserveAmount:".$ReserveAmount." ReserveNote:".$ReserveNote;
            try{
              if($rs->execute()){
                echo "<div id=\"success\" title=\"修改成功\">修改成功<br/></div>";
                echo "<script>delay_success();</script>";
              }else{
                echo "<div id=\"failure\" title=\"修改失敗\">修改失敗<br/></div>";
                echo "<script>delay();</script>";
              }
            }catch (PDOException $e){
              echo "<div id=\"failure\" title=\"修改失敗\">修改失敗<br/></div>";
              echo "<script>delay();</script>";
              printf("DataBaseError %s",$e->getMessage());
            }
        }
      }//end of foreach

    }else{
      echo '沒有資料';
    }//end of if
  }else{
    echo "<div id=\"failure_c\" title=\"沒有權限瀏覽\">沒有權限瀏覽<br/></div>";
    echo "<script>delay();</script>";
  }
  //時段的動態下拉式選單
    $mysqlhost="localhost";
    $mysqluser="root";
    $mysqlpasswd="";

  // 建立資料庫連線
  $link =@mysql_connect($mysqlhost, $mysqluser, $mysqlpasswd);
  if ($link == FALSE) {
    echo "不幸地，現在無法連上資料庫。請查詢資料庫連結是否有誤，請稍後再試。\n".mysql_error();
      exit();
  }
    
  mysql_query("set names utf8");
  $mysqldbname="volunteer";
  mysql_select_db($mysqldbname);

$intervals = mysql_query("select * from tinterval;");
if(!$intervals){
    echo "Execute SQL failed : ".mysql_error();
  exit;
}
$intervalCodeArr=array();     //用來存哪些選項的陣列
$intervalCount=0;
while($rows=mysql_fetch_array($intervals))
{
  $intervalCodeArr[$intervalCount]=$rows['tName'];
  $intervalCount++;
}
for($i=0;$i<count($intervalCodeArr);$i++)
{
  echo "<script type=\"text/javascript\">";
  echo "document.getElementById(\"TIntervalList\").options[$i]=new Option(\"$intervalCodeArr[$i]\",\"$intervalCodeArr[$i]\");";
  echo "</script>";
}
?>


</div>
</body>
</html>