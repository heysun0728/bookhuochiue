<!DOCTYPE html>
<?php
  require "../DbConnect.php";
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
<nav>
<!--匯入左邊索引欄-->
<?php include '../nav_control.php';?> 
</nav>
<article>
  <div class="reg_pic1">
     <img src="../image/reading.png" alt="reading" ></img>
  </div>
<form method="post" action="../model/tinterval_add.php" id="register_form">
  <label for="tintervalname">時段名稱：</laberl><br/>
  <input id="tintervalname" name="tintervalname" class="form-control required" type="text" required/>
  <br/><br/>
  
  
  <label for="starthour">開始時間：</laberl><br/>
  <select id="starthour" name="starthour">
    <?php
      for($i = 8;$i<21;$i++){
        echo "<option value=$i>$i</option>"; 
      }
    ?>
  </select>
  </br><br/>
  
  
  <label for="endhour">結束時間：</laberl><br/>
  <select id="endhour" name="endhour">
    <?php
      for($i = 9;$i<21;$i++){
        echo "<option value=$i>$i</option>"; 
      }
    ?>
  </select>
  </br><br/>
  

  <label for="hours">服務長度：</laberl>

  <span id="hours" name="hours">______ 小時</span>
  </br>
  </br>
  
  <input type="submit" value="新增"/>
</form>
</article>
</body>
</html>