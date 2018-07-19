<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../js/regcheck.js">
<title>刪除公告</title>
<!--CSS-->
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/form_style.css">
<!--jQuery-->
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery.validate.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<!--dialog-->
<script>
 $(function() {
    //成功動作
   $( "#success" ).dialog({ 
      modal:true,
      buttons: { 
        "OK": function() { 
            $(this).dialog("close");
            $(this).onClick(location='../model/AnnounceMangerment.php?page=1');
        } 
      }  
    });
   //失敗動作
    $( "#failure" ).dialog({ 
      modal:true,
      buttons: { 
        "再次刪除": function() { 
            $(this).dialog("close");
            $(this).onClick(history.go(-2)); 
        } 
      }  
    });
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

<?php
	require "../DbConnect.php";
	session_start();
?>
</head>
<style>
</style>
<body>
<!--匯入左邊索引欄-->
<nav>
    <?php include '../nav_control.php';?> 
</nav>
<article>
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
	    
	        echo "<form id=\"announce_form\" name=\"form\" method=\"post\">";
	        echo "<input name=\"no\" id=\"input-index\" type=\"hidden\"  value=". $rst["A_No"] ."><br/>"; 
            
            echo "<div id='ann_detail'>
			        <div class=\"form-group\"><label for=\"input-title\">公告標題：</label><br/>
			        <lable for=\"title\">".$rst["ATitle"]."</lable><br/></div>

			        <div class=\"form-group\"><label for=\"input-subtitle\">副標題：</label><br/>
			        <lable for=\"subtitle\">".$rst["ASubtitle"]."</lable><br/></div>
			        
			        <div class=\"form-group\"><label for=\"input-name\">公告類型：</label><br/>
			        <lable for=\"type\">".$rst["AType"]."</lable><br/></div>
			        <div class=\"form-group\"><label for=\"input-city\">公告內容：</label><br/>
			        <lable for=\"context\">".$rst["AContext"]."</lable><br/></div>

			        <div class=\"form-group\"><label for=\"input-type\">公告圖片：</label><br/>
			        <img src=../upload/".$rst["AImage"]." width=\"200\" height=\"150\"\><br/></div>

			         <div class=\"form-group\"><label for=\"input-type\">附加檔案：</label><br/>
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
</article>
</body>
</html>