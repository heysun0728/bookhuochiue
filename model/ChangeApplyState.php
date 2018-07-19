<?php 
    session_start(); 
    $vNumber=$_SESSION['vNumber'];
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
<title>更改申請狀態</title>
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
<?php include '../need.php';?> 
<div class="zone1">
	<div id="user_info">
	    <div id="circle"></div>
	    <p><?php  echo $_SESSION['name']?><br/><?php echo$_SESSION['RoleName'] ?></p>
	    <img src="../image/poster.png" alt="icon"></img>
	</div>		
</div>
<div class="zone2">
    <div id="form_type1">
    <?php 
        $HaveCompetence;
        competence();
		if(isset($_POST["change"])){
			change_state();
		}
		if($HaveCompetence=='1'){
			show_table();
		}
    ?>
    </div>
</div>
<?php
function competence(){
	global $HaveCompetence;
	if(isset($_SESSION['competence'])){
		foreach($_SESSION['competence'] as $a){
	        if($a=="更改申請狀態"){
	        	$HaveCompetence='1';
	        }              
		}
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
          GROUP BY m.vNumber';
    $rs=$link->prepare($sql);
	$rs->bindValue(':ServiceDate',$datetime);
    if($rs->execute()){
		//用php排列html的form及table
		echo "<form method=\"POST\" action=\"\">";
		echo "<table><tr>";
		//表頭名稱陣列
		$header=array("志工編號","姓名","申請狀態","預約狀態","服務時數");
        foreach($header as $h){
			echo "<th>".$h."</th>";
		}
        echo "</tr>";
		$rowname=array("vNumber","Name","ApplyState","ReserverState","ServiceHour");
		//顯示表單每列內容
		$row=$rs->fetchAll();
		for($i=0;$i<count($row);$i++){
			echo"<tr>";
			//排文字內容
			$astate=array("申請","複審通過","停權");
		    for($j=0;$j<=4;$j++){
		        if($j==2){
                    echo "<td><select name='".$row[$i]["vNumber"]."_list'>";
                    for($x=0;$x<3;$x++){
                    	if($row[$i][$rowname[$j]]==$astate[$x]){ 
                            echo "<option selected='selected' value='".$astate[$x]."'>".$astate[$x]."</option>";
                    	}
                    	else{
                    		echo "<option value='".$astate[$x]."'>".$astate[$x]."</option>";
                    	}
                    } 
                    echo "</select>
                          <button type='submit' formmethod='post' formaction='ChangeApplyState.php' name='change' value='".$row[$i]["vNumber"]."'>更改</button>
                          </td>";
		        }
		        else{
		       	    echo "<td>".$row[$i][$rowname[$j]]."</td>";
		        }
		    }		
			echo "</tr>";
		}
		echo "</table></form>";
	}
}
function change_state(){
	$listname=$_POST["change"]."_list";
	require "../DbConnect.php";
    $sql='UPDATE member
         SET ApplyState=:ApplyState
         WHERE vNumber=:vNumber';
    $rs=$link->prepare($sql);
    $rs->bindValue(':ApplyState',$_POST[$listname]);
    $rs->bindValue(':vNumber',$_POST["change"]);
    if($rs->execute()){
	   echo "<div id='success' title='申請狀態更改成功'>申請狀態更改成功<br/></div>";
    }
    else{
      echo "<div id=\"failure\" title=\"申請狀態更改失敗\">申請狀態更改失敗<br/></div>";
      echo "<script>delay();</script>";
    }
}
?>
</body>
</html>