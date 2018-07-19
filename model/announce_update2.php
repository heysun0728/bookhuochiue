<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改公告</title>

<!--CSS-->
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/form_style.css">
<!--jQuery-->
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery.validate.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
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
    $( "#failure_p" ).dialog({ 
      modal:true,
      buttons: { 
        "OK": function() { 
          $(this).dialog("close");
          $(this).onClick(location='../model/AnnounceMangerment.php?page=1');
        } 
      }  
    });
});
</script>
</head>
<body>
<nav>
<!--匯入左邊索引欄-->
<?php include '../nav_control.php';?> 
</nav>
<article>
   	<div class="reg_pic1">
    	<img src="../image/reading.png" alt="reading" ></img>
  	</div>
<?php
	require "../DbConnect.php";
	$ano = $_GET['ano'];
	$sql1='SELECT * FROM announcement WHERE A_No=:ano';
	$rs=$link->prepare($sql1);
	$rs->bindValue(':ano',$ano);
	$rs->execute();
	$rst=$rs->fetch();
			//載入修改學校的表單
			echo "<div class=\"main\">";
           	echo "<form enctype=\"multipart/form-data\" method=\"post\">";
    
	        echo "<input name=\"no\" id=\"input-index\" type=\"hidden\"  value=". $rst["A_No"] ."><br/>"; 
            
	        echo "<div class=\"form-type\"><label for=\"input-title\">公告標題：</label><br/>";
	        echo "<input name=\"title\" id=\"input-title\" type=\"text\"  value=". $rst["ATitle"] ."><br/>";

	         echo "<div class=\"form-type\"><label for=\"input-subtitle\">副標題：</label><br/>";
	        echo "<input name=\"subtitle\" id=\"input-subtitle\" type=\"text\"  value=". $rst["ASubtitle"] ."><br/></div>";

	        echo "<div class=\"form-type\"><label for=\"input-type\">公告類型：</label><br/>";
	        echo "<input name=\"type\" id=\"input-type\" type=\"text\" value=".$rst["AType"]."><br/></div>";
            
	        echo "<div class=\"form-group\"><label for=\"input-context\">公告內容：</label><br/>";
	        echo "<textarea id=\"context\" name=\"context\" rows=\"5\" cols=\"40\"/>".$rst["AContext"]."</textarea><br/></div>";

	        echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"104857600\">";

	        echo "<div class=\"form-group\"><label>公告圖片：</label><br/>";
	        echo "<img src=../upload/".$rst["AImage"]." width=\"200\" height=\"150\"\>";
	        echo "<div class=\"form-group\"><label for=\"input-img\">重新上傳：</label><br/>";
	        echo "<input type=\"file\" name=\"myFile[0]\" id=\"input-img\" accept=\"image/jpeg,image/bmp,image/gif,image/png\" style=\"display: block;margin-bottom: 5px;\" ><br/>";
	        echo "<input name=\"oimage\" id=\"input-image\" type=\"hidden\"  value=". $rst["AImage"] ."><br/></div>"; 

	        echo "<div class=\"form-group\"><label>附加檔案：".$rst["AFile"]."</label>";
	         echo "<div class=\"form-group\"><label for=\"input-file\">重新上傳：</label><br/>";
	        echo "<input type=\"file\" name=\"myFile[1]\" id=\"input-file\" accept=\"application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document\" style=\"display: block;margin-bottom: 5px;\" >";
	        echo "<input name=\"ofile\" id=\"input-file\" type=\"hidden\"  value=".$rst["AFile"]."><br/></div>"; 

	        echo "<input type=\"submit\" name=\"button\" value=\"確定修改\" /><br/><br/>";
	        echo "</form>";
	        echo "</div>";//end main

	include_once 'fileupload.php';
    
	if ($_SERVER["REQUEST_METHOD"]=='POST') {
	   //取得post來的資料
	    if(isset($_POST['no']) && isset($_POST['title']) && isset($_POST['context']) && isset($_POST['type']) && isset($_POST['subtitle']) && isset($_POST['oimage']) && isset($_POST['ofile'])){
		    $ano=$_POST['no'];
	  	    $title=$_POST['title'];          
	        $context=$_POST['context'];         
	        $type=$_POST['type'];         
	        $subtitle=$_POST['subtitle'];
	        $files = getFiles();
			// 依上傳檔案數執行
			if($files[0]['name']==null){
				$imgname=$_POST['oimage'];
			}else{
				$imgname=$files[0]['name'];
			}
			if($files[1]['name']==null){
				$filename=$_POST['ofile'];
			}else{
		    	$filename=$files[1]['name'];
			}
			$i=0;
		    foreach ($files as $fileInfo) {
		      // 呼叫 function
		      $res=uploadFile($fileInfo);
		      // 上傳成功，將實際儲存檔名存入 array（以便存入資料庫）
		      if (!empty($res['dest'])) {
		          $uploadFiles[$i]=$res['dest'];
		          $i++;
		      }
		    }
		    $announcedate=date("Y-m-d")." ".date("h:i:s");
			$sql2= 'UPDATE announcement
					SET ATitle=:title,
		                ASubtitle=:subtitle,
		  				AType=:type,
		  				AContext=:context,
		  				AFile=:file,
		  				AImage=:img,
		  				ADate=:adate
					WHERE A_No=:ano';
			$rs=$link->prepare($sql2);
			$rs->bindValue(':ano',$ano);
			$rs->bindValue(':title',$title);
		    $rs->bindValue(':subtitle',$subtitle);
			$rs->bindValue(':type',$type);
			$rs->bindValue(':context',$context);
			$rs->bindValue(':img',$imgname);
			$rs->bindValue(':file',$filename);
			$rs->bindValue(':adate',$announcedate);

			try{
				if($rs->execute()){
					echo "<div id=\"success\" title=\"修改成功\">修改成功<br/>".$res['mes']."<br></div>";
					echo "<script>delay_success();</script>";
				}else{
					echo"
						<div id=\"failure\" title=\"修改失敗\">
		   					修改失敗<br/>
		   					".$res['mes']."<br>
		   				</div>";
		   			echo "<script>delay();</script>";
				}
			}catch (PDOException $e){
				echo"
					<div id=\"failure\" title=\"修改失敗\">
	   					修改失敗<br/>
	   					".$res['mes']."<br>
	   				</div>";
				printf("DataBaseError %s",$e->getMessage());
			}
		}        
		else{
		   echo "<div id=\"failure_p\" title=\"接收資料失敗\">
   					接收資料失敗<br/>
   					".$res['mes']."<br>
   				</div>";
	    }	   
	}   
?>
</article>
</body>
</html>