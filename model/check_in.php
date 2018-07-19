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
<title>志工報到</title>
<script>
$(document).ready(function(){
    $( "#success" ).dialog({ 
        modal:true,buttons: { 
            "OK": function() { 
               $(this).dialog("close");
            } 
        }  
    });
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
<?php include '../need.php';?> 
<div class="zone1">
	<div id="user_info">
	    <div id="circle"></div>
	    <p><?php  echo $_SESSION['name']?><br/><?php echo$_SESSION['RoleName'] ?></p>
	    <img src="../image/poster.png" alt="icon"></img>
	</div>		
</div>
<div class="zone2">
    <?php show();?>
</div>
<?php
function show(){
    require "../DbConnect.php";
	//sql查詢 顯示當日預約表格
	if(isset($_POST["checkin"])){
		check_in($_POST["checkin"]);
	}
    
	require "../DbConnect.php";
	//sql查詢 顯示當日預約表格
	$datetime= date("Y-m-d");
	$sql='SELECT m.vNumber,m.Name,m.ApplyState,s.*
          FROM servicerecord s, member m,timeinterval t
          WHERE s.vNumber = m.vNumber
		  AND s.Time_No=t.Time_No
          AND t.ServiceDate =:ServiceDate
          AND s.ReserverState =:ReserverState';
    $rs=$link->prepare($sql);
	$rs->bindValue(':ServiceDate',$datetime);
	$rs->bindValue(':ReserverState','分配');
    if($rs->execute()){
		//用php排列html的form及table
		echo "<form id='form_type1'>";
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
            //若開始時間為初始值則button顯示(但仍留空位)
			//$show=($row[$i]["StartTime"]=='0000-00-00 00:00:00')?"visible":"hidden";
			echo "<td><button type='submit' name='checkin' value='".$row[$i]["ServiceRecord_No"]."' formmethod='post' formaction='' '>報到</button></td>";
			
			echo "</tr>";
	    }
	    echo "</table>";
    }}
    function check_in($c){//更改開始時間
      	$NowTime=time();//取得時間
		//將時間改成mySQL timestamp格式
		//8*3600解決php時區問題
	    $NowTime=gmdate("Y-m-d H:i:s",$NowTime+8*3600);
	    require "../DbConnect.php";
	    $sql='UPDATE servicerecord
	          SET StartTime=:StartTime,ReserverState=:ReserverState
		      WHERE ServiceRecord_No=:ServiceRecord_No';
        $rs=$link->prepare($sql);
	    $rs->bindValue(':StartTime',$NowTime);
	    $rs->bindValue(':ServiceRecord_No',$c);
	    $rs->bindValue(':ReserverState','報到');
	    if($rs->execute()){
		   echo "<div id='success' title='報到成功'>報到成功<br/></div>";
       }
       else{
          echo "<div id=\"failure\" title=\"報到失敗\">報到失敗<br/></div>";
          echo "<script>delay();</script>";
	    }
    }
?>

</form>
</body>
</html>