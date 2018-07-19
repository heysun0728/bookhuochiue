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
<title>修改公告</title>
<script>
 $(function() {
    //成功動作
   $( "#success" ).dialog({ 
      modal:true,
      buttons: { 
        "OK": function() { 
            $(this).dialog("close");
            $(this).onClick(location='../model/AnnounceManagement.php?page=1');
        } 
      }  
    });
   //失敗動作
    $( "#failure" ).dialog({ 
      modal:true,
      buttons: { 
        "再次修改": function() { 
            $(this).dialog("close");
            $(this).onClick(history.go(-2)); 
        } 
      }  
    });
    $( "#failure_p" ).dialog({ 
      modal:true,
      buttons: { 
        "OK": function() { 
          $(this).dialog("close");
          $(this).onClick(location='../model/AnnounceManagement.php?page=1');
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
<?php
	$no = $_GET['ano'];
	$sql1='SELECT * FROM announcement
	      WHERE A_No=:no';
	$rs=$link->prepare($sql1);
	$rs->bindValue(':no',$no);
	$rs->execute();
	$rows=$rs->fetchAll(PDO::FETCH_ASSOC);
	$rowCount=count($rows);
	if($rowCount==1){
		foreach ($rows as $rst) {
    	    echo "<div class=\"main\">";
	    
	        echo "<form id=\"form_type1\" name=\"form\" method=\"post\">";
	        echo "<input name=\"no\" id=\"input-index\" type=\"hidden\"  value=". $rst["A_No"] ."><br/>"; 
            
            echo "<div id='ann_detail'>
			        <div class=\"form-group\"><label for=\"input-title\">公告標題：</label>
			        <lable for=\"title\">".$rst["ATitle"]."</lable><br/></div>

			        <div class=\"form-group\"><label for=\"input-subtitle\">副標題：</label>
			        <lable for=\"subtitle\">".$rst["ASubtitle"]."</lable><br/></div>
			        
			        <div class=\"form-group\"><label for=\"input-name\">公告類型：</label>
			        <lable for=\"type\">".$rst["AType"]."</lable><br/></div>
			        <div class=\"form-group\"><label for=\"input-city\">公告內容：</label>
			        <lable for=\"context\">".$rst["AContext"]."</lable><br/></div>

			        <div class=\"form-group\"><label for=\"input-type\">公告圖片：</label>
			        <img src=../upload/".$rst["AImage"]." width=\"200\" height=\"150\"\><br/></div>

			         <div class=\"form-group\"><label for=\"input-type\">附加檔案：</label>
			        <lable for=\"file\">".$rst["AFile"]."</lable><br/></div>

			        <input type=\"submit\" name=\"button\" value=\"確定刪除\" /><br/><br/>
			      </div>";
	        echo "</form>";
		    echo "</div>";//end main
	   	}//end of foreach
	}//end of if
    if ($_SERVER["REQUEST_METHOD"]=='POST') {
    	if(isset($_POST['no'])){
		   $ano=$_POST['no'];
	    
		   $sql2 = 'DELETE FROM announcement
				    WHERE A_No=:ano';
			$rs=$link->prepare($sql2);
			$rs->bindValue(':ano',$ano);
			try{
				if($rs->execute()){
					echo "<div id=\"success\" title=\"修改成功\">修改成功<br/></div>";
					echo "<script>delay_success();</script>";
				}else{
					echo "<div id=\"failure\" title=\"修改失敗\">修改失敗<br/></div>";
		   			echo "<script>delay();</script>";
				}
			}catch (PDOException $e){
				echo "<div id=\"failure\" title=\"修改失敗\">修改失敗<br/></div>";
				echo "<script>delay();</script>";
				printf("DataBaseError %s",$e->getMessage());
			}
		}
		else{
	   		echo "接收資料失敗<br/>";
    	}
    }
   	
?>
</div>
</body>
</html>