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
                $(this).onClick(location='../model/TintervalMangerment.php?page=1');
            } 
          }  
        });
       //失敗動作
        $( "#failure" ).dialog({ 
          modal:true,
          buttons: { 
            "再次刪除": function() { 
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
    <title>刪除區間時段資料</title>
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

        $sql = 'SELECT comptence.interval_del FROM role, member, comptence
                WHERE  role.RoleID = member.RoleID AND role.Roleindex = comptence.Roleindex AND member.vNumber=:vNumber';

        $rs=$link->prepare($sql);
        $rs->bindValue(':vNumber',$vNumber);
        $rs->execute();
        $rows=$rs->fetchAll(PDO::FETCH_ASSOC);
        $rowCount=count($rows);
        if($rowCount==1){
          foreach ($rows as $rst) {
            $interval_del = $rst['interval_del'];
          }//end of foreach
        }//end of if
  }//end of comptence if
  
  
  if(isset($interval_del))if($interval_del=='1'){
    $updateNumber = $_GET['value'];
    $sql = ' SELECT * FROM tinterval WHERE timeid=:updateNumber';
    $rs=$link->prepare($sql);
    $rs->bindValue(':updateNumber',$updateNumber);
    $rs->execute();
    $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
    $rowCount=count($rows);
    if($rowCount==1){
      foreach ($rows as $rst) {
      echo "  <div class=\"main\">
                <form method=\"post\" class=\"yellowform\">
                  <label for=\"tintervalname\">時段名稱：</laberl><br/>
                  <input id=\"tintervalname\" name=\"tintervalname\" class=\"form-control required\" value=\"".$rst['tName']."\"type=\"text\" />
                  <br/><br/>
                  <label for=\"starthour\">開始時間：</laberl><br/>
                  <select id=\"starthour\" name=\"starthour\" >";
                  for($i = 8 ;$i<21;$i++){
                    if($rst['StartHour']==$i) echo "<option value=$i SELECTED>$i</option>"; 
                    else echo "<option value=$i>$i</option>";
                  }
                  echo "</select></br><br/>
                  <label for=\"endhour\">結束時間：</laberl><br/>
                  <select id=\"endhour\" name=\"endhour\" value=\"10\">";
    
                  for($i = 9;$i<21;$i++){
                    if($rst['EndHour']==$i) 
                      echo "<option value=$i SELECTED>$i</option>"; 
                    else echo "<option value=$i>$i</option>"; 
                  }
    
                  echo "</select></br><br/>
                  <label for=\"hours\">服務長度：</laberl>
                  <span id=\"hours\" name=\"hours\">".$rst['Hours']."小時</span>
                  </br></br>
                  <input type=\"submit\" value=\"刪除\"/>
                  </form></div>";
      }//end of foreach
      
      if($_SERVER["REQUEST_METHOD"]=='POST'){
        echo "<div class=\"main\">";
        if(isset($_POST['timeid']))$Time_No = $_POST['timeid'];
          //缺少其他資料表需要刪除的資料SQL
          $sql = 'DELETE FROM tinterval WHERE timeid=:timeid';
          $rs=$link->prepare($sql);
          $rs->bindValue(':timeid',$updateNumber);
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
      //document.form.select_school.value = '<?php echo $rst["Tinterval"]?>';
    </script>
 
    </article>
    </body>

    </html>