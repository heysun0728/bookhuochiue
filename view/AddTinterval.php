<?php 
	require "../DbConnect.php";
	session_start();
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
<form method="post" action="../model/tinterval_add.php" id="form_type1">
  <label for="tintervalname">時段名稱：</laberl>
  <input id="tintervalname" name="tintervalname" class="form-control required" type="text" required/>
  <br/><br/>
  
  <label for="starthour">開始時間：</laberl>
  <select id="starthour" name="starthour">
    <?php
      for($i = 8;$i<21;$i++){
        echo "<option value=$i>$i</option>"; 
      }
    ?>
  </select>
  </br><br/>
  
  
  <label for="endhour">結束時間：</laberl>
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



</div>
</body>
</html>