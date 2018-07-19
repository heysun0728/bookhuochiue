<!DOCTYPE html>
<?php
 session_start();
 if(!isset($_SESSION["load_time"])){
    session_destroy();
    session_start();
    $_SESSION["load_time"]="";
 }
?>
<html>
<head>
<title>Book或缺</title>
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery.mousewheel.min.js"></script>
<script src="../js/jquery.validate.js"></script>
<script src="../js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
<link rel="stylesheet" href="../css/style2.css">
<link rel="stylesheet" href="../css/form_style2.css">
<script>
$(document).ready(function(){
    //成功動作
    $( "#success" ).dialog({ 
      modal:true,
      buttons: { 
        "ok": function() { 
            $(this).dialog("close");
        } 
      }  
    });
    //失敗動作
    $( "#failure" ).dialog({ 
      modal:true,
      buttons: { 
        "ok": function() { 
            $(this).dialog("close");
        } 
      }  
    });

    index=1;//為了不要讓scroll的trigger催發兩次作的
    rotation=0;
    /*註冊出現*/
    $("#index_btn1").click(function(){
      $("#register").fadeIn();
    });
    $("#register #cross").click(function(){
      $("#register").hide();  
    })
    /* login出現*/
    $("#index_btn2").click(function(){
        $("#cont").hide();
        $("#login").show();
        $("#login").css("top","42%");
        $("#login").animate({top:'40%'});
    });
    /*login裡的返回鍵*/
    $("#back_btn").click(function(){
    	$("#login").hide();
        $("#cont").show();
        $("#cont").css("top","42%");
        $("#cont").animate({top:'40%'});
    });
    /*login及時檢查*/
    $("#loginform").validate({
      rules:{
        id_input:{
          required:true,
          minlength:2,
          maxlength:10
        },
        pwd_input:{
          required:true
        }
      }
    });
    if($("#index_load_time").text()=="login"){
      show_login();
    }
    else if($("#index_load_time").text()=="register"){
      show_register();  
    }
    else{
        /*讓藍方塊移動*/
        $("#blue").animate({width:"600px"},2000);
        $("#blue").queue(function(){
            $("#logo").delay(800).hide(0);
            $("#b").show();
            $("#b").animate({top:'30px',left:'3000px'},2000);
            $("#b").queue(function(){
               $("#b").hide();
            });
            $("#index").delay(1000).show(0);
            $("#oblique_line").delay(1000).animate({height:"100%",marginTop:"0px",marginBottom:"0px"},2000);
            $("#oblique_line").queue(function(){
                $("#left").animate({width:"50%",marginLeft:"0px"},2000);
                $("#left").queue(function(){
                    $("#right").animate({width:"50%"},2000);
                    $("#right").queue(function(){
                        $("#cont").show();
                        $("#cont").css("top","42%");
                        $("#cont").animate({top:'40%'});
                        $("#cont").queue(function(){
                            $("#oblique_line").hide();
                            $("#diamond").fadeIn(750);
                            mscroll();
                        });
                    });
                });
                mwheel();
            });
        });
    } 
    /*滑鼠滾動*/
    function mscroll(){
        $("#mouse_scroll").css("top","30%");
        $("#mouse_scroll").animate({top:"20%"},1000).animate({top:"30%"},1000);
        //console.log("123");
        setTimeout(mscroll(),5000);
    }
    function mwheel(){
        $(window).mousewheel(function(e){
            //console.log(rotation+" "+e.deltaY);
            rotation+=180;
            /*if(e.deltaY<0){//如果往下滑就加180度 往上則相反
                rotation+=180;
            }
            else{
                rotation-=180
            }*/
            rolling();
         });
    }
    function rolling(){//旋轉
        $("#left_rollbox").css({
        	'-webkit-transform' : 'rotate('+ rotation +'deg)',
            'transform' : 'rotate('+ rotation +'deg)',
            '-webkit-transition': 'all 1s ease-out',
    		'transition': 'all 1s ease-out'});
        $("#right_rollbox").css({
        	'-webkit-transform' : 'rotate('+ rotation +'deg)',
            'transform' : 'rotate('+ rotation +'deg)',
            '-webkit-transition': 'all 1s ease-out',
    		'transition': 'all 1s ease-out'});
        if(rotation%720==360){
            $("#a1").hide();
            $("#a2").hide();
        }
        else if(rotation%720==540){
            $("#b1").hide();
            $("#b2").hide();
        }
        else if(rotation%720==0){
            $("#a1").show();
            $("#a2").show();
        }
        else if(rotation%720==180){
            $("#b1").show();
            $("#b2").show();
        }
    }
    function show_login(){
        $("#index").show();
        $("#left").css({width:"50%",marginLeft:"0px"});
        $("#left").show();
        $("#right").css({width:"50%"});
        $("#right").show();
        $("#blue").hide();
        $("#logo").hide();
        $("#b").hide();
        $("#cont").hide();
        $("#login").show();
        $("#login").css("top","40%");
        $("#diamond").show();
        mwheel();
        mscroll();//一定要擺在最後面 不然他後面的會lag
    }
    function show_register(){
        $("#index").show();
        $("#left").css({width:"50%",marginLeft:"0px"});
        $("#left").show();
        $("#right").css({width:"50%"});
        $("#right").show();
        $("#blue").hide();
        $("#logo").hide();
        $("#b").hide();
        $("#cont").show();
        $("#register").show();
        $("#diamond").show();
        mwheel();
        mscroll();//一定要擺在最後面 不然他後面的會lag
    }
});
function delay(){
    var speed = 5000;
    setTimeout("history.back()", speed);
}
function delay_success(){
    var speed = 5000;
    setTimeout(location='../index.php', speed);
}
</script>
<style>
::-webkit-scrollbar { 
    display: none; 
}
</style>
</head>
<body style="overflow:scroll;">
<?php
    require "DbConnect.php";
    $sql = 'SELECT * FROM announcement ORDER BY ADate DESC';
    $rs=$link->prepare($sql);
    $rs->execute();
    $rows=$rs->fetchall();
?>
<div id="index" style="display:none;">
    <div id="diamond">
        <div id="mouse_border">
            <div id="mouse_scroll"></div>
        </div>
    </div>
    <div id="oblique_line"></div>
	<div id="left">        
	    <div id="left_rollbox">
	        <!--要|左|右|這樣放 每兩個排成一頁面,所以每兩個就要有一個調位置-->
            <!--D--><div class="i_block" id="d2" style="background-color:#ecebeb;left:50%;">
                        <?php 
                            echo   '<div class="i_ann" id="rotatetype">
                                        <div id="date" style="color:#7f7f7f;">|| '.substr($rows[2]["ADate"],0,10).'</div>
                                        <div id="title" style="color:#7f7f7f;">'.$rows[2]["ATitle"].'</div>
                                        <div id="context" style="color:#7f7f7f;">'.$rows[2]["AContext"].'</div>
                                    </div>';
                        ?>
                    </div>
            <!--C--><div class="i_block" id="c1" style="background-color:white;">
                        <img class="i_img2" style="width:53%;left:47%;top:35%" src="../image/index/desk.gif"></img>
                    </div>
	        <!--B--><div class="i_block" id="b1" style="background-color:#f1b68e;left:50%;">
                        <?php 
                            echo   '<div class="i_ann" id="rotatetype">
                                        <div id="date">|| '.substr($rows[0]["ADate"],0,10).'</div>
                                        <div id="title">'.$rows[0]["ATitle"].'</div>
                                        <div id="context">'.$rows[0]["AContext"].'</div>
                                    </div>';
                        ?>
                    </div>
	        <!--A--><div class="i_block" id="a1" style="background-color:#f5f5f5;">
	        			<img class="i_img" src="../image/index/library.jpg"></img>
	        		</div>
	    </div>
	</div>
	<div id="right">
	    <div id="right_rollbox">
            <!--D--><div class="i_block" id="d2" style="background-color:#9895bd;">
                         <img class="i_img2" src="../image/index/earth.gif"></img>
                    </div>
            <!--C--><div class="i_block" id="c2" style="background-color:#7dc9aa;left:50%">
                        <?php 
                            echo   '<div class="i_ann">
                                        <div id="date">|| '.substr($rows[1]["ADate"],0,10).'</div>
                                        <div id="title">'.$rows[1]["ATitle"].'</div>
                                        <div id="context">'.$rows[1]["AContext"].'</div>
                                    </div>';
                        ?>
                    </div>
	    	<!--B--><div class="i_block" id="b2">
	    				<img class="i_img" src="../image/index/music.jpg"></img>
    				</div>
	        <!--A--><div class="i_block" id="a2" style="background-color:#5dc2d0;left:50%">
	        			<div id="cont">
	        		    	<img src="../image/index/logo_white.png" width="60%"></img>
	        		    	<p>圖書志工預約網,歡迎加入我們</p>
	        		    	<input id="index_btn1" type="submit" value="加入志工" >
            				<input id="index_btn2" type="submit" value="志工登入" >
	        			</div>
	        			<div id="login">
				            <h1>登入</h1>
  				            <form role="form" id ="loginform" method="post" action="">
  				                <label for="id_input">帳號</label>
  						        <input id="id_input" name="id_input" class="form-control required" type="text" ><br/>
  						        <label for="pwd_input">密碼</label>
  						        <input id="pwd_input" name="pwd_input" class="form-control required" minlength="3" type="password" ><br/>
  						        <input id="back_btn" type="button" value="返回">
  						        <input id="login_btn" type="submit" value="登入">
		        		    </form>
      					</div>
        			</div>
	    </div>
	</div>
    <form id="register" method="post" action="">
        <h1>註冊</h1>
        <div id="cross">
            <span></span>
            <span></span>
        </div>
        <div id="group1">
            <label for="input_id">帳號</label>
            <input id="input_id" name="id" class="form-control required" minlength="3" maxlength="10" type="text" placdholder="請輸入帳號 3~10個字元'" required >
            <br/>
            <label for="input_pwd">密碼</label>
            <input id="input_pwd" name="pwd" class="form-control required" minlength="6" maxlength="20" type="password" required>
            <br/>
            <label for="input_chkpwd">確認密碼</label>
            <input id="input_chkpwd" name="chkpwd" class="form-control required" minlength="6" maxlength="20"  type="password" required>
            <br/>
            <label for="input_name">姓名</label>
            <input id="input_name" name="name" class="form-control required" minlength="2" maxlength="6" type="text" required>
            <br/>
            <label for="input-bir">生日</label>
            <input id="input-bir" name="birthday" class="form-control required" value="<?php echo date('Y-m-d'); ?>" type="date" required>
            <br/>
            <div style="margin:10px 0px;">
                <label for="male">性別</label>  
                <label class="radio-inline">
                <input type="radio" name="sex" id="male" value="male" checked> 男
                </label>
                <label class="radio-inline">
                <input type="radio" name="sex" id="female" value="female"> 女
                </label>
            </div>
            <label for="input_IDnumber">身分證字號</label>
            <input id="input_IDnumber" name="IDnumber" class="form-control required" type="text" minlength="10" maxlength="10" required>
        </div><!--end of form-group1 -->
        <div id="group2">
            <label for="schoolList">就讀學校</label>
            <select id="schoolList" name="school" style="margin:10px 0;" class="form-control">
            <option></option>
            <select> 
            <br/>
            <label for="input-phone">電話</label>
            <input id="input-phone" name="phone" class="form-control required" type="tel" required>
            <br/>
            <label for="input-email">電子郵件</label>
            <input id="input-email" name="email" class="form-control required" type="email" required>
            <br/>
            <label for="input_pname">監護人姓名</label>
            <input id="input_pname" name="pname" class="form-control required" minlength="2" type="text" required>
            <br/>
            <label for="input_pphone">監護人電話</label>
            <input id="input_pphone" name="pphone" class="form-control required" minlength="10" type="tel" required>
            <br/>
            <label for="input-relationship">監護人關係</label>
            <select name="relationship">
                <option value="父子">父子</option>
                <option value="父女">父女</option>
                <option value="母子">母子</option>
                <option value="母女">母女</option>
            </select>
            <br/>
        </div><!--end of form-group2 -->
        <div id="group3"> 
            <input class="form-group3 btn btn-default submit" type="submit" name="send" value="送出" style="margin:0px;">
        </div>
    </form>
</div>
<div id="b"></div>
<div id="logo">
    <img id="l" src="../image/logo.png"></img>
    <div id="blue"></div>
</div>
<div id="try"></div>
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

function register(){
    require "DbConnect.php";
    //接收register傳過來的資料
    $id = $_POST['id'];
    $pwd = $_POST['pwd'];
    $rpwd = $_POST['chkpwd'];
    $name=$_POST['name'];
    $birthday=$_POST['birthday'];
    $sex=$_POST['sex'];
    $school=$_POST['school'];
    $IDNumber=$_POST['IDnumber'];
    $phone=$_POST['phone'];
    $email=$_POST['email'];
    $pname=$_POST['pname'];
    $pphone=$_POST['pphone'];
    $prelation=$_POST['relationship'];
    $age=round((time()-strtotime($birthday))/(24*60*60)/365.25,0);

    $sql = 'SELECT * FROM member WHERE ID = :id';
    $rs=$link->prepare($sql);

    //bindValue是與變數當時的值有關，即使變動後，綁定的值也不會變動
    $rs->bindValue(':id',$id);
    $rs->execute();//預處理操作 來執行預處理裡面的SQL語法>可以綁定參數
    $rows=$rs->fetchAll(PDO::FETCH_ASSOC);
    $rowCount=count($rows);
    
    if($rowCount){ //有資料筆數則不為0
        $_SESSION["load_time"]="register";
        echo "<div id=\"failure\" title=\"註冊失敗\">帳號已經有人使用<br/>
              倒數計時<div id=\"redirect2\"></div>秒</font></div>";
        echo "<script>delay();</script>";   
    }else{
        //驗證身分證字號是否申請過
        $sql_IDN='SELECT * FROM member WHERE IDNumber = :IDNumber';
        $rs=$link->prepare($sql_IDN);
        $rs->bindValue(':IDNumber',$IDNumber);
        $rs->execute();
        $rows_IDN=$rs->fetchAll(PDO::FETCH_ASSOC);
        $rows_IDNCount=count($rows_IDN);
        if($rows_IDNCount){
            $_SESSION["load_time"]="register";
            echo "
            <div id=\"failure\" title=\"註冊失敗\">
                此組身分證字號已註冊過帳號<br/>
                5秒後自動回到註冊頁面。。。<br/>
            </div>";
            echo "<script>delay();</script>";
            exit();
        }

        if($pwd!=$rpwd){
            $_SESSION["load_time"]="register";
            echo"
            <div id=\"failure\" title=\"註冊失敗\">
                兩次密碼輸入不符!!請重新輸入<br/>
                5秒後自動回到註冊頁面。。。<br/>
            </div> ";
            echo "<script>delay();</scripst>";
            exit;
            }
        //新增資料
        $insertData=array($id,$pwd,$name,$birthday,$sex,$school,$IDNumber,$phone,$email,$age,"申請",0,$pname,$prelation,$pphone,'volunteer');
        $sql='INSERT INTO member(ID, Password, Name, Birthday, Sex, School, IDNumber, Phone, Email, Age, ApplyState, ServiceHours, ParentName, ParentRelationship, ParentPhone, RoleID) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $sth=$link->prepare($sql);
        try{
            if($sth->execute($insertData)){
                $_SESSION["load_time"]="login";
                echo "<div id=\"success\" title=\"註冊成功\">
                    請以註冊的帳號登入網站<br/>
                </div>";
            }else{
                $_SESSION["load_time"]="register";
                echo "
                <div id=\"failure\" title=\"註冊失敗\">
                    請嘗試再註冊一次<br/>
                </div>";
            }
        }catch (PDOException $e){
            echo "
                <div id=\"failure\" title=\"新增失敗\">
                    5秒後自動回到註冊頁面。。。<br/>
                </div>";
        }
    }
}


function check(){
    require "DbConnect.php";

    //接收login傳過來的資料
    $id = $_POST['id_input'];//帳號
    $pwd = $_POST['pwd_input'];//密碼

    //SQL指令
    $sql = 'SELECT * FROM member WHERE ID = :id and Password = :pwd';
    $rs=$link->prepare($sql);

    //bindValue是與變數當時的值有關，即使變動後，綁定的值也不會變動
    $rs->bindValue(':id',$id);
    $rs->bindValue(':pwd',$pwd);
    $rs->execute();//預處理操作 來執行預處理裡面的SQL語法>可以綁定參數

    $rows=$rs->fetchAll(PDO::FETCH_ASSOC);
    $rowCount=count($rows);

    if($rowCount==1){ //有資料筆數則不為0
      //給變數值並記錄在伺服器上
      
      foreach ($rows as $rst){
        $_SESSION['myID']=$id;//設定ID
        $_SESSION['vNumber']=$rst["vNumber"];//將vNumber(志工編號)先寫進session
        $_SESSION['name']=$rst["Name"];//設定名字
        $_SESSION['RoleID']=$rst['RoleID'];
        $url="../model/calendar.php";
        $_SESSION["load_time"]=null;
        echo "<script type='text/javascript'>window.location.href='$url'</script>";
      }
    }else{
       $_SESSION["load_time"]="login";
       echo "<div id=\"failure\" title=\"登入失敗\">
                帳號密碼不符!!請重新輸入<br/>
            </div> ";
    }
}

if(isset($_POST['id_input'])){
  check();
}
if(isset($_POST['send'])){
  register();
}                    
?>
<!--這行不要移動到 固定放在最後面-->
<span id="index_load_time" style="display:none;"><?php echo $_SESSION["load_time"];?></span>
</body>
</html>