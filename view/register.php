<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>註冊</title>
<!--CSS-->
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/form_style.css">

<!--jQuery-->
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery.validate.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>

<style>

form p {
  color: #000000;
  font-size: 20pt;
}
form label{
  font-size: 12pt;
}
</style>
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
 
<form role="form" id = "register_form" method="post" action="../model/reg.php">
  <div class="register_form_p">
      <div class="form-group1">
      <label for="input_id">帳號</label><br/>
      <input id="input_id" name="id" class="form-control required" minlength="3" maxlength="10" type="text" placdholder="請輸入帳號 3~10個字元'" required >
      <br/>

      <label for="input_pwd">密碼</label><br/>
      <input id="input_pwd" name="pwd" class="form-control required" minlength="6" maxlength="20" type="password" required>
      <br/>

      <label for="input_chkpwd">確認密碼</label><br/>
      <input id="input_chkpwd" name="chkpwd" class="form-control required" minlength="6" maxlength="20"  type="password" required>
      <br/>

      <label for="input_name">姓名</label><br/>
      <input id="input_name" name="name" class="form-control required" minlength="2" maxlength="6" type="text" required>
      <br/>

      <label for="input-bir">生日</label><br/>
      <input id="input-bir" name="birthday" class="form-control required" value="<?php echo date('Y-m-d'); ?>" type="date" required><br/>
      <br/>
      
      <div style="margin:10px 0px;">
        <label for="male">性別</label>  
        <label class="radio-inline">
          <input type="radio" name="sex" id="male" value="male" checked> 男
        </label>
        <label class="radio-inline">
          <input type="radio" name="sex" id="female" value="female"> 女
        </label>
      </div><!--END OF SEX-->
      
      <label for="schoolList">就讀學校*</label><br/>
      <select id="schoolList" name="school" style="margin:10px 0;" class="form-control">
        <option></option>
      <select> 

      <br/>
    </div><!--end of form-group1 -->
     <div class="form-group2">
        <label for="input_IDnumber">身分證字號 *</label><br/>
        <input id="input_IDnumber" name="IDnumber" class="form-control required" type="text" minlength="10" maxlength="10" required>
        <br/>
        
        <label for="input-phone">電話 *</label><br/>
        <input id="input-phone" name="phone" class="form-control required" type="tel" required>
        <br/>

        <label for="input-email">電子郵件 *</label><br/>
        <input id="input-email" name="email" class="form-control required" type="email" required>
        <br/>

        <label for="input_pname">監護人姓名</label><br/>
        <input id="input_pname" name="pname" class="form-control required" minlength="2" type="text" required>
        <br/>

        <label for="input_pphone">監護人電話</label><br/>
        <input id="input_pphone" name="pphone" class="form-control required" minlength="10" type="tel" required>
        <br/>

        <label for="input-relationship">監護人關係</label><br/>
        <input id="input-relationship" name="relationship" class="form-control required" type="text" required>
        <br/>
    </div><!--end of form-group2 -->
    
   
    <div class="form-group3"> 
    <input class="form-group3 btn btn-default submit" type="submit" name="send" value="註冊" style="margin:0px;">
    </div>
    
    </div><!--end of register_form_p-->

 
</form>

</article>
</body>
<!--學校的動態下拉式選單-->
<?php
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

$schools = mysql_query("select * from school;");
if(!$schools){
    echo "Execute SQL failed : ".mysql_error();
  exit;
}
$schoolCodeArr=array();     //用來存哪些選項的陣列
$schoolCount=0;
while($rows=mysql_fetch_array($schools))
{
  $schoolCodeArr[$schoolCount]=$rows['schoolName'];
  $schoolCount++;
}
for($i=0;$i<count($schoolCodeArr);$i++)
{
  echo "<script type=\"text/javascript\">";
  echo "document.getElementById(\"schoolList\").options[$i]=new Option(\"$schoolCodeArr[$i]\",\"$schoolCodeArr[$i]\");";
  echo "</script>";
}
?>

</html>
