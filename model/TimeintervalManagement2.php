<!DOCTYPE html>
<?php
	require "../DbConnect.php";
	session_start();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>時段管理</title>
<!--CSS-->
<link rel="stylesheet" href="../css/style.css">
<link href="../css/table_style.css" rel="stylesheet" type="text/css" >
<link href="../css/form_style.css" rel="stylesheet" type="text/css" >
<!--jQuery-->
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery.validate.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<style>
article{
   background-image:url('../image/book.jpg');
}
</style>
<!--dialog-->
<script>
 $(function() {
 		//沒有權限
        $( "#failure_c" ).dialog({
        	modal:true,
          	buttons: { 
          		"沒有權限": function() { 
          		$(this).dialog("close");
          		$(this).onClick(location='../index.php');
          	} 
          }  
        });

});
</script>
<!--倒數秒數控制-->
<script type ="text/javascript">
	function delay(){
		var speed = 5000;
		setTimeout("history.back()", speed);
	}
</script>

</head>
<body>

<nav>
<!--匯入左邊索引欄-->
<?php include '../nav_control.php';?> 
</nav>

<article>
<form id="form_type2">
<?php
//目前人數的地方需要再做修改 沒有做SQL計算人數
	//判斷權限
 if(isset($_SESSION['myID'])){
    $myID=$_SESSION['myID'];
  require "../DbConnect.php";

  $sql  = ' SELECT r.RoleID, c.interval_add, c.interval_update, c.interval_del
			FROM role r, member m, comptence c
			WHERE r.RoleID = m.RoleID AND r.Roleindex = c.Roleindex AND m.id=:myID';

        $rs=$link->prepare($sql);
        $rs->bindValue(':myID',$myID);
        $rs->execute();
        $rst=$rs->fetch();
        $RoleID=$rst['RoleID'];
        $interval_add = $rst['interval_add'];
        $interval_update = $rst['interval_update'];
        $interval_del = $rst['interval_del'];

}//end of comptence
if(isset($interval_add)||isset($interval_update)||isset($interval_del))if($interval_add=='1'||$interval_update=='1'||$interval_del='1'){
$id = $_SESSION['myID'];//讀取館員ID
		
		echo "<input type=\"submit\" value=\"查詢\" formmethod=\"post\" formaction=\"../model/timeintervalquery.php?page=1\"></input>";

		echo "<button type='submit' formmethod='post' formaction='../view/AddTimeInterval.php'>新增時段</button>";
		echo "<button type='submit' formmethod='post' formaction='../model/TintervalMangerment.php?page=1'>區間時段管理</button>";
		
		echo "</form>";
        echo "<form id='form_type3'>";
		//全部資料
		$sql='SELECT t1.*, t2.tName 
		      FROM timeinterval t1, tinterval t2
		      WHERE t1.timeid = t2.timeid
		      ORDER BY ServiceDate DESC';
		$rs=$link->prepare($sql);
		//execute() 執行預處理裡面的SQL > 綁定參數
		$rs->execute();
		$rows=$rs->fetchAll(PDO::FETCH_ASSOC);
		include "../model/page.php";
		echo "<table class=\"table table-striped table-hover\">
			  <tr>
			  <th>時段編號</th>
			  <th>日期</th>
			  <th>時段</th>
			  <th>人數</th>
			  <th>人數上限</th>
			  <th>備註</th>";
		if(isset($interval_update))if($interval_update=='1')
			echo  "<th>修改</th>";
		if(isset($interval_del))if($interval_del=='1')
			echo  "<th>刪除</th>";
		echo  "</tr>";
   
		if($rows)
		{
			for($i=$min;$i<$max;$i++){
				$rst=$rows[$i];
				echo "<tr>";
				echo "<td>".$rst["Time_No"]."</td>";
				echo "<td>".$rst["ServiceDate"]."</td>";
				echo "<td>".$rst["tName"]."</td>";
				echo "<td>".$rst["NumberOfPeople"]."</td>";
				echo "<td>".$rst["ReserveAmount"]."</td>";
				echo "<td>".$rst["ReserveNote"]."</td>";
				if(isset($interval_update))if($interval_update=='1')
				echo "<td><input type=\"button\" onclick=\"updateinterval(".$rst['Time_No'].")\" value=\"修改\"  /></td>";
				if(isset($interval_del))if($interval_del=='1')
       			echo "<td><input type=\"button\" onclick=\"deleteinterval(".$rst['Time_No'].")\" value=\"刪除\"  /></td>";
       			echo "</tr>";
			}
		}
		else{
			echo"<tr>無資料</tr>";
		}
		echo"</table><br/></div>";	
}
else{
	echo "<div id=\"failure_c\" title=\"沒有權限瀏覽\">沒有權限瀏覽<br/></div>";
	echo "<script>delay();</script>";
}//end of comptence
		
		//頁數
		page_set($pagesum);

		function page_set($pagesum){
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
		            echo "<a href='../model/TimeintervalMangerment.php?page=".$i."' style='background-color:#ffe66f;'>".$i."</a>";
		    	}
		    	else{
		    	    echo "<a href='../model/TimeintervalMangerment.php?page=".$i."'>".$i."</a>";
		    	}
		    }
		    echo "頁</div>";
		}
?>
</form>
</article>
</body>
   
<script>
function updateinterval(v){
  location.href="interval_update.php?value=" + v;
}
function deleteinterval(v){
  location.href="interval_del.php?value=" + v;
}
</script>

</html>
