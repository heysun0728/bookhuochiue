<!DOCTYPE html>
<html>
<head>
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/jquery.validate.js"></script>
<link rel="stylesheet" href="../css/style2.css">
<link rel="stylesheet" href="../css/jquery-ui.css">
<link rel="stylesheet" href="../css/form_style2.css" type="text/css" >
<link rel="stylesheet" href="../css/table_style2.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Book或缺 圖書志工系統</title>
<script>
$(document).ready(function(){
    //登出
    $("#logout_sucess").dialog({ 
        autoOpen:false,
        modal:true,
        buttons: { 
          "OK":function() { 
              $(this).dialog("close");
              location ='../index.php';
          } 
        }  
    });
    $("#logout_ahref").click(function(){
        $("#logout_sucess").dialog("open");
    });
    $( "#failure_c" ).dialog({ 
        modal:true,
        buttons: { 
            "沒有權限": function() { 
                $(this).dialog("close");
                $(this).onClick(location='../index.php');
           } 
        }  
    });
	timer = 0;
    step=0;
	$("#menu_btn").click(function(){ 
        $(this).toggleClass('open');
		if(timer==0){
        	timer=1;
         	$("#menu").fadeIn(300);
    	}else{
         	timer=0;
         	$("#menu").fadeOut(300);
    	}
    });
    $(".zone1").animate({left:'4%'},700);
    $(".zone1").queue(function(){
        $(".zone2").animate({left:'28%'},1000);
        essay_show();//公告部分用zone3
    });
    $(".menu_option").hover(function(event){
        event.preventDefault();
        a=$(this).find(".white_c2");
        if (a.hasClass("isDown")) {
            a.animate({width:'250px',height:'250px'});    
        }
        else{
            a.animate({width:'220px',height:'220px'});
        }
        a.toggleClass("isDown");
        return false;
    });
    n=5;//目前螢幕上最左邊icon編號(若只到8,n=10)
    $("#leftbtn").click(function(){
        left=$(".options2").position().left;//取得相對位置
        num=parseInt($("#num").text());//總共有幾個menu icon
        l=left;//要更新的left
        if(n!=5){
            l=left;
            l+=1450;
            n-=5;
        }
        s=l+"px";
        $(".options2").animate({left:s},400);
    });
    $("#rightbtn").click(function(){
        left=$(".options2").position().left;//取得相對位置
        num=parseInt($("#num").text());//總共有幾個menu icon
        l=left;//要更新的left
        if(n<num){
            l=left;
            l-=1450;
            n+=5;
        }
        s=l+"px";
        $(".options2").animate({left:s},600);
    });
    function essay_show(){
        $("#essay #date").show();
        $("#essay #date").css("top","2%");
        $("#essay #date").animate({top:'0%'},500);
        $("#essay #date").queue(function(){
            $("#essay #title").show();
            $("#essay #title").css("top","2%");
            $("#essay #title").animate({top:'-1%'},500); 
            $("#essay #title").queue(function(){
                $("#essay #imgbox").show();
                $("#essay #imgbox").css("top","4%");
                $("#essay #imgbox").animate({top:'2%'},500); 
                $("#essay #imgbox").queue(function(){
                    $("#essay #context").show();
                    $("#essay #context").css("top","6%");
                    $("#essay #context").animate({top:'4%'},500); 
                });
            });     
        });
    }
});
</script>
<script type ="text/javascript">
    function delay(){
      var speed = 5000;
      setTimeout("history.back()", speed);
    }
</script>
</head>
<body>
<div id='logout_sucess' title='登出成功' style="display:none;">登出成功<br/></div>

<!--左邊攔-->
<div id="white_block">
</div>    
<!--menu btn-->
<div id="menu_btn">
    <span></span>
    <span></span>
    <span></span>
</div>
<!-- 底部基準線 -->
<div class="line" id="line1"></div>
<div class="line" id="line2"></div>
<div class="line" id="line3"></div>
<div class="line" id="line4"></div>
<div class="line" id="line5"></div>
<div class="line" id="line6"></div>
<div class="line" id="line7"></div>
<!-- menu頁面 -->
<div id="menu">
<?php
Competence_select();
function Competence_select(){ //取得目前所有使用者權限
    require "DbConnect.php";
    /*$RoleID=$RoleName=$volunteer_checkin=$volunteer_checked=$volunteer_editinf=$room_add=$room_update=$room_del=$interval_add=$interval_update=$interval_del=$school_add=$school_del=$school_update=$announce_add=$announce_update=$announce_del=$usergroup_add=$usergroup_update=$usergroup_del=""*/
    if(isset($_SESSION['myID'])){
        /*
        global $RoleID,$RoleName,$volunteer_checkin,$volunteer_checked,$volunteer_editinf,$room_add,$room_update,$room_del,$interval_add,$interval_update,$interval_del,$school_add,$school_del,$school_update,$announce_add,$announce_update,$announce_del,$usergroup_add,$usergroup_update,$usergroup_del;
        */
        $myID=$_SESSION['myID'];
        $vNumber=$_SESSION['vNumber'];
        $sql = 'SELECT * FROM comptence_view WHERE comptence_view.vNumber=:vNumber';
        $rs=$link->prepare($sql);
        $rs->bindValue(':vNumber',$vNumber);
        $rs->execute();
        $rows=$rs->fetchAll(PDO::FETCH_ASSOC);
        $rowCount=count($rows);
        if($rowCount==1){
            foreach ($rows as $rst) {
                $RoleID=$rst['RoleID'];
                $RoleName=$rst['RoleName'];
                $volunteer_checkin=$rst['volunteer_checkin'];
                $volunteer_checked=$rst['volunteer_checked'];
                $volunteer_editinf=$rst['volunteer_editinf'];
                $room_add = $rst['room_add'];
                $room_update = $rst['room_update'];
                $room_del = $rst['room_del'];
                $interval_add = $rst['interval_add'];
                $interval_update = $rst['interval_update'];
                $interval_del = $rst['interval_del'];
                //echo $interval_del;
                $school_add = $rst['school_add'];
                $school_del = $rst['school_del'];
                $school_update = $rst['school_update'];
                $announce_add = $rst['announce_add'];
                $announce_update = $rst['announce_update'];
                $announce_del = $rst['announce_del'];
                $usergroup_add= $rst['usergroup_add'];
                $usergroup_update= $rst['usergroup_update'];
                $usergroup_del= $rst['usergroup_del'];
                $_SESSION['RoleName']=$rst['RoleName'];
            }//end of foreach
        }//end of if
        set_menu($RoleID,$RoleName,$volunteer_checkin,$volunteer_checked,$volunteer_editinf,$room_add,$room_update,$room_del,$interval_add,$interval_update,$interval_del,$school_add,$school_del,$school_update,$announce_add,$announce_update,$announce_del,$usergroup_add,$usergroup_update,$usergroup_del);  
    }//end of comptence if
}
function set_menu($RoleID,$RoleName,$volunteer_checkin,$volunteer_checked,$volunteer_editinf,$room_add,$room_update,$room_del,$interval_add,$interval_update,$interval_del,$school_add,$school_del,$school_update,$announce_add,$announce_update,$announce_del,$usergroup_add,$usergroup_update,$usergroup_del){ //設定icon 和 使用者該看到哪些頁面
    //連結/圖片網址/左/上/寬
    $icon_info=array("預約"=>array("../model/calendar.php","../image/menu/calendar.png",60,60,130), 
                  "服務紀錄"=>array("../model/service_record.php?page=1","../image/menu/record.png",70,50,110),
                  "個人資料"=>array("../model/userinfoupdate.php","../image/menu/userinfo.png",50,70,145),
                  "公告"=>array("../model/Announce.php","../image/menu/newspaper.png",60,60,130),
                  "更改申請狀態"=>array("../model/ChangeApplyState.php","../image/menu/write.png",65,60,150),
                  "設定服務館室"=>array("../model/choose_room.php","../image/menu/chooseroom.png",50,45,150),
                  "志工報到"=>array("../model/check_in.php","../image/menu/checkin.png",45,45,150),
                  "時數核定"=>array("../model/verify.php","../image/menu/timer.png",45,45,150),
                  "申請確認"=>array("../model/apply_confirm.php","../image/menu/verify.png",45,45,150),
                  "預約查詢"=>array("../model/calendar.php","../image/menu/record.png",70,50,110),
                  "志工查詢"=>array("../model/infoqueryform.php?page=1","../image/menu/usersearch.png",60,60,145),
                  "志工管理"=>array("../model/memberview.php?page=1","../image/menu/usermanage.png",60,60,140),
                  "使用者管理"=>array("../model/usergroup.php?page=1","../image/menu/UserGroup.png",47,60,155),
                  "館室管理"=>array("../model/RoomManagement.php?page=1","../image/menu/roommanage.png",40,60,160),
                  "學校管理"=>array("../model/SchoolManagement.php?page=1","../image/menu/school.png",27,60,195),
                  "時段管理"=>array("../model/TimeintervalManagement.php?page=1","../image/menu/timemanage.png",60,60,145),
                  "公告管理"=>array("../model/AnnounceManagement.php?page=1","../image/menu/newspaper.png",60,60,130),
                  "登出"=>array("","../image/menu/exit.png",70,50,110));
    $com=array();//列出使用者所有的權限 不要給初值不然下面count會判斷錯誤
    if($RoleID=='volunteer'){//志工

        $a=array("預約","服務紀錄","個人資料","公告");
        foreach($a as $x){
            $com[count($com)]=$x;
        }
        $com[count($com)]="登出";
        //show_icon($com);
    }
    else{//館員管理員
        //echo $_SESSION['name'].$RoleName;
        if(isset($volunteer_checkin))if($volunteer_checkin=='1'){
            $a=array("更改申請狀態","設定服務館室","志工報到","時數核定","申請確認","預約查詢","志工查詢","志工管理");
            foreach($a as $x){
                $com[count($com)]=$x;
            }
        }
        if(isset($usergroup_add)||isset($usergroup_update)||isset($usergroup_del))if($usergroup_add=='1'||$usergroup_update=='1'||$usergroup_del=='1') {
            $com[count($com)]="使用者管理";
        }
        if(isset($room_add)||isset($room_update)||isset($room_del))if($room_add=='1'||$room_update=='1'||$room_del=='1'){

            $com[count($com)]="館室管理";
        }

        if($school_del=='1'||$school_update=='1'){
            $com[count($com)]="學校管理";
        }
        if($interval_add=='1'||$interval_update=='1'){
            //echo $interval_add,$interval_update,$interval_del;
            $com[count($com)]="時段管理";
        }
        if(isset($announce_add)||isset($announce_update)||isset($announce_del))if($announce_add=='1'||$announce_update=='1'||$announce_del=='1'){
            $com[count($com)]="公告管理";
        }
        $com[count($com)]="登出";
        //show_icon($com);
    }
    show_icon($com,$icon_info);

}   
function show_icon($com,$icon_info){ //把icon排版部分
    $_SESSION["competence"]=$com;
    if(count($com)<=5){ //因為只有一排 所以沒箭頭而且要用有置中的options1
        echo '<div class="options">'; 
        foreach($com as $a){
            //echo $icon_info[$x][1];
            icon($a,$icon_info);
        }
        echo '</div>';
    }
    else{
        echo '<span id="leftbtn"></span>
              <span id="rightbtn"></span>
              <div class="hide_option_box">
              <div class="options2">';
        foreach($com as $a){
            //echo $icon_info[$x][1];
            icon($a,$icon_info);

        }
        echo '</div></div>';
    }
    echo '<p id="num" style="display:none">'.count($com).'</p>';
}
function icon($a,$icon_info){//把icon顯示出來
    if($a=="登出"){
        echo '<a id="logout_ahref">
            <div class="menu_option">
                <div id="white_c1">
                    <div id="blue_c1"></div>
                    <div class="white_c2"></div> 
                    <img src="'.$icon_info[$a][1].'" style="left:'.$icon_info[$a][2].'px;
            top:'.$icon_info[$a][3].'px;width:'.$icon_info[$a][4].'px"></img>
                </div>
                <p>'.$a.'</p>
            </div>
          </a>';
    }else{
        echo '<a href="'.$icon_info[$a][0].'">
            <div class="menu_option">
                <div id="white_c1">
                    <div id="blue_c1"></div>
                    <div class="white_c2"></div> 
                    <img src="'.$icon_info[$a][1].'" style="left:'.$icon_info[$a][2].'px;
            top:'.$icon_info[$a][3].'px;width:'.$icon_info[$a][4].'px"></img>
                </div>
                <p>'.$a.'</p>
            </div>
          </a>';
    }
}

function page_set($pagesum,$url){
        echo "<div id='page'>第";
        $nowpage=$_GET["page"];

        $start;
        $end;

        if($pagesum>5&&$nowpage>2){
            $start=$nowpage-2;
        }
        else{
            $start=1;
        }
         
        if(($nowpage+3>$pagesum)||$pagesum<6){
            $end=$pagesum;
        }
        else{
            if($nowpage<=2){
                $end=6;
            }
            else{
                if($pagesum>5){
                    $end=$nowpage+3;
                }
                else{
                    $end=$pagesum;
                }
            }
        }


        for($i=$start;$i<=$end;$i++)
        {
            if($i==$nowpage){
                echo "<a href='".$url."?page=".$i."' style='background-color:#5dc2d0;'>".$i."</a>";
            }
            else{
                echo "<a href='".$url."?page=".$i."'>".$i."</a>";
            }
        }
        echo "頁</div>";
    }

function page_set2($pagesum,$url){
        echo "<div id='page'>第";
        $nowpage=$_GET["page"];

        $start;
        $end;

        if($pagesum>5&&$nowpage>2){
            $start=$nowpage-2;
        }
        else{
            $start=1;
        }
         
        if(($nowpage+3>$pagesum)||$pagesum<6){
            $end=$pagesum;
        }
        else{
            if($nowpage<=2){
                $end=6;
            }
            else{
                if($pagesum>5){
                    $end=$nowpage+3;
                }
                else{
                    $end=$pagesum;
                }
            }
        }


        for($i=$start;$i<=$end;$i++)
        {
            if($i==$nowpage){
                echo "<a href='".$url."page=".$i."' style='background-color:#5dc2d0;'>".$i."</a>";
            }
            else{
                echo "<a href='".$url."page=".$i."'>".$i."</a>";
            }
        }
        echo "頁</div>";
    }
?>
    
</div>
</body>
</html>
