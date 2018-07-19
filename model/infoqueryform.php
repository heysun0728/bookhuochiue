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
<title>志工查詢</title>
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
<?php 
require "../DbConnect.php";
	$con="";
	//去除特殊字元和空白
	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
    }

	echo "<form id='form_type2' name=\"queryform\" method=\"post\"action=\"../model/infoquery.php?page=1\">";
    echo "<input name=\"inputcondition\" id=\"inputcondition\" type=\"text\">";

	echo "<input name=\"submit\" type=\"submit\" value=\"查詢\"/>";
	echo "</form><br/>";

	$sql='SELECT m.vNumber, m.ID,m.Name, m.ApplyState, s.schoolName, m.ServiceHours
	      FROM member m INNER JOIN school s
	      ON s.schoolid = m.School
	      ORDER BY m.vNumber';
	//prepare() 預處理操作
	$rs=$link->prepare($sql);
	$rs->execute();
	$rows=$rs->fetchAll(PDO::FETCH_ASSOC);
	echo "<form id='form_type3'>";
    include "../model/page.php";
	if($rows)
	{
		echo "<table>
		<tr>
		<th>志工編號</th>
		<th>帳號</th>
		<th>姓名</th>
		<th>申請狀態</th>
		<th>就讀學校</th>
		<th>服務總時數</th>
		</tr>";
			 
		for($i=$min;$i<$max;$i++){
			$rst=$rows[$i];
			echo "<tr>";
			echo "<td><a href=vinfo.php?vNumber=".$rst["vNumber"].">".$rst["vNumber"]."</a></td>";
		    echo "<td><a href=vinfo.php?vNumber=".$rst["vNumber"].">".$rst["ID"]."</a></td>";
			echo "<td><a href=vinfo.php?vNumber=".$rst["vNumber"].">".$rst["Name"]."</td>";
			echo "<td>".$rst["ApplyState"]."</td>";
			echo "<td>".$rst["schoolName"]."</td>";
			echo "<td>".$rst["ServiceHours"]."</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	else
	{
		echo"<div>無相符資料</div>";

	}
	
	//頁數
    $url="../model/infoqueryform.php";
	page_set($pagesum,$url);


	
?>
</form>
</div>
</body>
</html>