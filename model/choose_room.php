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
<title>設定服務館室</title>
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
    <?php choose_room()?>
</div>
<?php
function choose_room(){
    require "../DbConnect.php";
	//sql查詢 顯示當日預約表格
    if(isset($_POST["room"])) {
	   require "../DbConnect.php";
	   $sql='UPDATE servicerecord
	         SET ServiceRoom=:ServiceRoom,ReserverState=:ReserverState
	         WHERE ServiceRecord_No=:ServiceRecord_No';
	   $rs=$link->prepare($sql);
	   $rs->bindValue(':ServiceRoom',$_POST["roomlist"]);
	   $rs->bindValue(':ServiceRecord_No',$_POST["room"]);
	   $rs->bindValue(':ReserverState','分配');
	   if($rs->execute()){
		   echo "<div id='success' title='服務管室更新成功'>服務管室更新成功<br/></div>";
       }
       else{
          echo "<div id='failure' title='服務管室更新失敗'>服務管室更新失敗<br/></div>";
	   }
	}

	$datetime= date("Y-m-d");
	$sql='SELECT m.vNumber,m.Name,m.ApplyState,s.*
          FROM servicerecord s,member m,timeinterval t
          WHERE s.vNumber = m.vNumber
		  AND s.Time_No=t.Time_No
          AND t.ServiceDate =:ServiceDate
          AND s.ReserverState =:ReserverState';
    $rs=$link->prepare($sql);
	$rs->bindValue(':ServiceDate',$datetime);
	$rs->bindValue(':ReserverState','預約');
    if($rs->execute()){
		//用php排列html的form及table
		echo "<form id='form_type1'>";
		echo "<table><tr>";
		//表頭名稱陣列
		$header=array("志工編號","姓名","申請狀態","開始時間","服務狀況","服務館室");
        foreach($header as $h){
			echo "<th>".$h."</th>";
		}
        echo "</tr>";
		$rowname=array("vNumber","Name","ApplyState","ReserverState","ServiceState");
		//顯示表單每列內容
		$row=$rs->fetchAll();
		for($i=0;$i<count($row);$i++){
			echo"<tr>";
			//排文字內容
		    for($j=0;$j<=4;$j++){
			   echo "<td>".$row[$i][$rowname[$j]]."</td>";
		    }
            echo "<form><td>";
            echo "<select name=\"roomlist\">";
            $sql = 'SELECT * FROM serviceroom';
            $rs=$link->prepare($sql);
            $rs->execute();
            while($row2=$rs->fetch()){
          		echo "<option value='".$row2["serviceroomName"]."'>".$row2["serviceroomName"]."</option>";
            }//end of while
            echo "</select>";
            echo  "<button type='submit' formmethod='post' formaction='test2.php' name='room' value='".$row[$i]["ServiceRecord_No"]."'>確定</button>
                  </td></form>";
		    }
		    echo "</tr>";
	    }
	    echo "</table>";
    }
?>

</form>
</body>
</html>