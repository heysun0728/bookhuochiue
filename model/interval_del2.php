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
                $(this).onClick(location='../model/TimeintervalMangerment.php?page=1');
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
    <title>刪除時段資料</title>
</head>
<?php
  require "../DbConnect.php";
  //接收admin資料
  session_start();
  $id = $_SESSION['myID'];//接收目前登入身分ID
  $vNumber=$_SESSION['vNumber'];//讀取目前進入者的(志工)編號
  $name=$_SESSION['name'];
?>
    <nav>
        <!--匯入左邊索引欄-->
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
        $vNumber=$_SESSION['vNumber'];
          require "../DbConnect.php";
        $sql = 'SELECT * FROM comptence_view WHERE comptence_view.vNumber=:vNumber';
        $rs=$link->prepare($sql);
        $rs->bindValue(':vNumber',$vNumber);
        $rs->execute();
        $rst=$rs->fetch();
        if($rst){
          $interval_del = $rst['interval_del'];
        }//end of if
  }//end of comptence if
  
  
  if(isset($interval_del))if($interval_del=='1'){
    $updateNumber = $_GET['value'];
    $sql = ' SELECT t1.Time_No, t1.ServiceDate, t1.NumberOfPeople, t2.tName, 
                    t1.ReserveAmount, t1.ReserveNote 
              FROM   timeinterval t1, tinterval t2
              WHERE  t1.timeid=t2.timeid AND t1.Time_No=:updateNumber
              ';
    $rs=$link->prepare($sql);
    $rs->bindValue(':updateNumber',$updateNumber);
    $rs->execute();
    $rst = $rs->fetch();
    if($rst){
      
        ?>
    <div class="main">
            <div class="form-group">        
            <form id="register_form" name="form" method="post">

                <label for="timeid">時段編號：(無法修改)</label><br/>
                <input name="timeid" id="timeid" type="text"  value="<?php echo $rst["Time_No"]; ?>" Readonly/><br/>
            </div>
            <div class="form-group">
                <label for="date">日期：(無法修改)</label><br/>
                <input type="date" value="<?php echo $rst["ServiceDate"]; ?>" disabled/><br/>
            </div>
            <div class="form-group">
                <label for="peoplenumber">目前預約人數：(無法修改)</label><br/>
                <input type="text" value="<?php echo $rst["NumberOfPeople"]; ?>" disabled/><br/>
            </div>
            時段：<br/>
            <select name="Tinterval" value="<?php echo $rst["tName"]; ?>">
                <option value=\"1\">上午</option>
                <option value=\"2\">下午</option>
            </select><br/>
            <div class="form-group">
                <label for="limitnumber">人數上限：</label><br/>
                <input name="input-reserveAmount" id="limitnumber" type="text" value="<?php echo $rst["ReserveAmount"]; ?>" /><br/>
            </div>
                <div class="form-group"><label for="input-note">備註：</label><br/>
                <textarea name="input-note" id="input-note" value="<?php echo $rst["ReserveNote"]; ?>"></textarea><br/>
            </div>
            <input type="submit" name="button" value="確定刪除" /><br/><br/>
        </form>
    </div>

<?php 
      if($_SERVER["REQUEST_METHOD"]=='POST'){
        echo "<div class=\"main\">";
        if(isset($_POST['timeid']))$Time_No = $_POST['timeid'];
          //缺少其他資料表需要刪除的資料SQL
          $sql = 'DELETE FROM timeinterval WHERE Time_No=:Time_No';
          $rs=$link->prepare($sql);
          $rs->bindValue(':Time_No',$Time_No);
          try{
            if($rs->execute()){
              echo "<div id=\"success\" title=\"刪除成功\">刪除成功<br/></div>";
            }else{
              echo"<div id=\"failure\" title=\"刪除失敗\">刪除失敗<br/></div>";
              echo "<script>delay();</script>";
            }
          }catch (PDOException $e){
            echo "<div id=\"failure\" title=\"刪除失敗\">刪除失敗<br/></div>";
            echo "<script>delay();</script>";
            printf("DataBaseError %s",$e->getMessage());
          }
        echo "</div>";//end main
      }
    }//end of if
  }else{
    echo "<div id=\"failure_c\" title=\"沒有權限瀏覽\">沒有權限瀏覽<br/></div>";
    echo "<script>delay();</script>";
  }
?>
<!--讓下拉選單預設值是原本的選項-->
    <script type="text/javascript">
      document.form.select_school.value = '<?php echo $rst["Tinterval"]?>';
    </script>
 
    </article>
    </body>

    </html>