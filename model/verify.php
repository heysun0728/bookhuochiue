<?php 
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
<title>時數核定</title>
<script>
$(document).ready(function(){
	//沒有權限
    $( "#failure" ).dialog({ 
     modal:true,
      buttons: { 
      	"沒有權限": function() { 
      		$(this).dialog("close");
      		$(this).onClick(location='../index.php');
      	} 
      }  
    });
    //成功動作
   $( "#success" ).dialog({ 
      modal:true,buttons: { 
        "OK": function() { 
           $(this).dialog("close");
        } 
      }  
    });
   //失敗動作
    $( "#failure" ).dialog({ 
                buttons: { 
        "修改失敗": function() { 
           $(this).dialog("close");
        } 
      }  
    });
});
</script>
</head>
<body>
<!--
1)匯need黨
2)Css檔need裡有匯,所以不用再加
3)要套有頭像的版本->複製zone1                                                     
4)複製zone2->裡面放form語法
-->
<?php include '../need.php';?> 
<div class="zone1">
	<div id="user_info">
	    <div id="circle"></div>
	    <p><?php  echo $_SESSION['name']?><br/><?php echo$_SESSION['RoleName'] ?></p>
	    <img src="../image/poster.png" alt="icon"></img>
	</div>		
</div>
<div class="zone2">
    <form id="form_type1" method="post" action="">
    <?php 
        $HaveCompetence;
        competence();
		if(isset($_POST["StartTime"]) ){
		    decide();
		}
		if($HaveCompetence=='1'){
			show_table();
		}
		else{
		  	echo "<div id=\"failure\" title=\"沒有權限瀏覽\">
		   			沒有權限瀏覽<br/>
		   		</div>";
		    echo "<script>delay();</script>";
		}
		
    ?>
    </form>
</div>
<?php
function competence(){
	global $HaveCompetence;
	if(isset($_SESSION['competence'])){
		foreach($_SESSION['competence'] as $a){
	        if($a=="時數核定"){
	        	$HaveCompetence='1';
	        }              
		}
	}
}
function change_ServiceHours($newHours){//更新那位學生的總服務時數
		require "../DbConnect.php";
		$sql='UPDATE member
	          SET ServiceHours=ServiceHours+:newHours
		      WHERE vNumber=:vNumber';
        $rs=$link->prepare($sql);
	    $rs->bindValue(':newHours',$newHours);
		$rs->bindValue(':vNumber',$_POST["vNumber2"]);
		if($rs->execute()){
			echo "<div id='success' title='核定成功'>核定成功<br/></div>";
       }
       else{
          echo "<div id=\"failure\" title=\"核定失敗\">核定失敗<br/></div>";
          echo "<script>delay();</script>";
		}
}

function decide(){//核定
        $NowTime=time();//取得時間
	    $NowTime=gmdate("Y-m-d H:i:s",$NowTime+8*3600);//將時間改成mySQL timestamp格式
        $Start_Time=$_POST["StartTime"];//取得開始時間
		$ServiceHour=(strtotime($NowTime)-strtotime($Start_Time))/3600;
		require "../DbConnect.php";
	    $sql='UPDATE servicerecord
	          SET EndTime=:EndTime,
	              StartTime=:StartTime,
	              ServiceHour=:ServiceHour,
	              ReserverState=:ReserverState
		      WHERE ServiceRecord_No=:ServiceRecord_No';
        $rs=$link->prepare($sql);
	    $rs->bindValue(':EndTime',$NowTime);
		$rs->bindValue(':StartTime',$Start_Time);
	    $rs->bindValue(':ServiceHour',$ServiceHour);
	    $rs->bindValue(':ReserverState','核定');
		$rs->bindValue(':ServiceRecord_No',$_POST["ServiceRecord_No"]);
	    if($rs->execute()){
			change_ServiceHours($ServiceHour);
	    }
	    else{
          echo "<div id=\"failure\" title=\"核定失敗\">核定失敗<br/></div>";
          echo "<script>delay();</script>";
	    }
}
function show_table(){
    require "../DbConnect.php";

  $datetime= date("Y-m-d");
  $sql='SELECT m.vNumber,m.Name,m.ApplyState,s.*
          FROM servicerecord s, member m,timeinterval t
          WHERE s.vNumber = m.vNumber
      AND s.Time_No=t.Time_No
          AND t.ServiceDate =:ServiceDate
          AND s.ReserverState =:ReserverState';
    $rs=$link->prepare($sql);
   $rs->bindValue(':ServiceDate',$datetime);
   $rs->bindValue(':ReserverState','報到');
    if($rs->execute()){
    //用php排列html的form及table
    echo "<form method=\"POST\" action=\"\">";
    echo "<table><tr>";
    //表頭名稱陣列
    $header=array("志工編號","姓名","申請狀態","服務館室","開始時間",
                  "結束時間","預約狀態","服務時數","服務狀況");
        foreach($header as $h){
      echo "<th>".$h."</th>";
    }
        echo "</tr>";
    $rowname=array("vNumber","Name","ApplyState","ServiceRoom","StartTime",
                   "EndTime","ReserverState","ServiceHour","ServiceState");
    //顯示表單每列內容
    $row=$rs->fetchAll();

    for($i=0;$i<count($row);$i++){
      echo"<tr>";
      //排文字內容
        for($j=0;$j<=8;$j++){
         echo "<td>".$row[$i][$rowname[$j]]."</td>";
        }
      //排按鈕
      //若已核定過則button隱藏(但仍留空位)
      //$show=($row[$i]["ReserverState"]=='報到')?"visible":"hidden";
      echo "<input type='text' name='ServiceRecord_No' value='".$row[$i]["ServiceRecord_No"]."' style='display:none;'/>";
            echo "<input type='text' name='StartTime' value='".$row[$i]["StartTime"]."' style='display:none;'/>";
            echo "<input type='text' name='ServiceRecord_No' value='".$row[$i]["ServiceRecord_No"]."' style='display:none;'/>";
            echo "<input type='text' name='vNumber2' value='".$row[$i]["vNumber"]."' style='display:none;'/>";
      echo "<td><button type='submit' name='decide' value='".$i."' formmethod='post' formaction='' >核定</button></td>";
      echo "</tr>";
    }
    echo "</table></form>";
  }
  echo "</div>";

}


?>
</body>
</html>