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
<!--JavaScript 計算服務時數-->
<script type="text/javascript"> 
window.onload = function(){

//onmousement滑鼠移開 
document.getElementById("starthour").onmouseout = function(){ 
  var a= document.getElementById("starthour").value;
  var b= document.getElementById("endhour").value;
  
  a= parseInt(document.getElementById("starthour").value);
  b= parseInt(document.getElementById("endhour").value);
  if(a==b||a>b){
    document.getElementById("endhour").value=a+1;
    ;
  }
  a= parseInt(document.getElementById("starthour").value);
  b= parseInt(document.getElementById("endhour").value);
  
  if(!isNaN(this.value) && this.value != ""){
      document.getElementById("hours").innerHTML = " " +(parseInt(b)-parseInt(a)) + "小時"; 
  }else{ 
    document.getElementById("hours").innerHTML = 0+"小時"; 
  }
}; 

document.getElementById("endhour").onmouseout = function(){ 
  var a= parseInt(document.getElementById("starthour").value);
  var b= parseInt(document.getElementById("endhour").value);
  
  if(!isNaN(this.value) && this.value != ""){ 
    if((b-a)<=0){
        document.getElementById("endhour").value=a+1;
    }else{
      document.getElementById("hours").innerHTML = " " +(parseInt(b)-parseInt(a)) + "小時"; 
    }
  }else{
    document.getElementById("hours").innerHTML = 0+"小時"; 
  } 
};
 
  };

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
    $sql = 'SELECT r.RoleID, c.interval_add
            FROM role r, member m, comptence c
            WHERE r.RoleID = m.RoleID AND 
                  r.Roleindex = c.Roleindex AND 
                  m.id=:myID';
    $rs=$link->prepare($sql);
    $rs->bindValue(':myID',$myID);
    $rs->execute();
    $rst=$rs->fetch();
    $RoleID=$rst['RoleID'];
    $interval_add=$rst['interval_add'];
                 
  }//end of comptence if

  if(isset($interval_add))if($interval_add=='1'){
    $updateNumber = $_GET['value'];
    $sql='SELECT * FROM tinterval WHERE timeid = :updateNumber';
    $rs=$link->prepare($sql);
    $rs->bindValue(':updateNumber',$updateNumber);
    $rs->execute();
    $rst=$rs->fetch();
    echo "<form id=\"form_type1\" method=\"post\" >
                  <label for=\"tintervalname\">時段名稱：</laberl>
                  <input id=\"tintervalname\" name=\"tintervalname\" class=\"form-control required\" value=\"".$rst['tName']."\"type=\"text\" />
                  <br/><br/>
                  <label for=\"starthour\">開始時間：</laberl>
                  <select id=\"starthour\" name=\"starthour\" >";
                  for($i = 8 ;$i<21;$i++){
                    if($rst['StartHour']==$i) echo "<option value=$i SELECTED>$i</option>"; 
                    else echo "<option value=$i>$i</option>";
                  }
                  echo "</select></br><br/>
                  <label for=\"endhour\">結束時間：</laberl>
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
                  <input type=\"submit\" value=\"修改\"/>
                  </form>";
        
    //更新
    if ($_SERVER["REQUEST_METHOD"]=='POST') {
      //$_POST[name]取得post來的資料
      if(isset($_POST['tintervalname']))$tintervalname=$_POST['tintervalname'];
      if(isset($_POST['starthour']))$starthour = $_POST['starthour'];          
      if(isset($_POST['endhour']))$endhour=$_POST['endhour'];         
      $hours=$endhour-$starthour;
      $sql = 'UPDATE tinterval 
              SET tName=:tName, starthour=:starthour, endhour=:endhour, hours=:hours
              WHERE timeid=:timeid';
      $rs=$link->prepare($sql);
      $rs->bindValue(':tName',$tintervalname);
      $rs->bindValue(':starthour',$starthour);
      $rs->bindValue(':endhour',$endhour);
      $rs->bindValue(':hours',$hours);
      $rs->bindValue(':timeid',$updateNumber);

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

  }//end of try...catch
   }//end of post if
  
  
  }else{
    echo "<div id=\"failure_c\" title=\"沒有權限瀏覽\">沒有權限瀏覽<br/></div>";
    echo "<script>delay();</script>";
  }
?>
</div>
</body>
</html>