<!DOCTYPE html>
<?php
  session_start();
?>
<head>
<title>新增公告</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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

});
</script>

</head>
<body>
<nav>
<!--匯入左邊索引欄-->
<?php include '../nav_control.php';?> 
</nav>
//判斷權限
<?php
function user(){
  if(isset($_SESSION['myID'])){
    $myID=$_SESSION['myID'];
    $vNumber=$_SESSION['vNumber'];
  require "../DbConnect.php";

  $sql = 'SELECT * FROM comptence_view
          WHERE comptence_view.vNumber=:vNumber';
  $rs=$link->prepare($sql);
  $rs->bindValue(':vNumber',$vNumber);
  $rs->execute();
  $row=$rs->fetch();
  if($row){
    $RoleID=$row['RoleID'];
    $announce_add=$row['announce_add'];
    }//end of if
  }// end of comptence if
}
?>
<?php user();?>
<?php
  if(isset($announce_add))if(($announce_add=='1')){
?>
<article>
  <div class="reg_pic1">
    <img src="../image/reading.png" alt="reading" ></img>
  </div>
  <form id="addannounce_form" enctype="multipart/form-data" method="post">
    <label for="announcetitle">公告標題：</laberl><br/>
    <input id="announcetitle" name="announcetitle" class="form-control required" type="text" required/>
    <br/><br/>

    <label for="announcesubtitle">副標題：</laberl><br/>
    <input id="announcesubtitle" name="announcesubtitle" class="form-control required" type="text"/>
    <br/><br/> 

    <label for="announcetype">公告類型：</laberl><br/>
    <select id="announcetype" name="announcetype">
      <?php
      $sql = 'SELECT * FROM announcetype';
      $rs = $link->prepare($sql);
      $rs -> execute();
      while($row = $rs -> fetch()){
        echo "<option value=".$row[atypeName]." >".$row[atypeName]."</option>";
      }
    ?>
    </select>
    <br/><br/>  
    
    <label for="announcedetail">公告內容：</laberl><br/>
    <textarea id="announcedetail" name="announcedetail" rows="5" cols="40" class="form-control required" required/></textarea>
    <br/><br/>

    <!-- 限制上傳檔案的最大值 -->
    <input type="hidden" name="MAX_FILE_SIZE" value="104857600">
    <!-- 限制上傳檔案的類型 -->
    公告圖片：<input type="file" name="myFile[]" accept="image/jpeg,image/bmp,image/gif,image/png" style="display: block;margin-bottom: 5px;">
    <br/>
    附加檔案：<input type="file" name="myFile[]" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" style="display: block;margin-bottom: 5px;">
    <br/>
    <input name="submit" type="submit" value="新增"/>
  </form>
<?php
  }else{
    echo "<div id=\"failure_c\" title=\"錯誤\">無權限瀏覽<br/></div>";
  }
?>
<?php
  require "../DbConnect.php";
  include_once 'fileupload.php';
  //接收form傳過來的資料
  if (isset($_POST["announcetitle"])){
    if(isset($_POST['announcetitle']) && isset($_POST['announcedetail']) && isset($_POST['announcetype']) && isset($_POST['announcesubtitle'])){
      $announcetitle=$_POST['announcetitle'];
      $announcedetail=$_POST['announcedetail'];     
      $announcetype=$_POST['announcetype'];    
      $announcesubtitle=$_POST['announcesubtitle'];
      $files = getFiles();
      $imgname=$files[0]['name'];
      $filename=$files[1]['name'];
      // 依上傳檔案數執行
      $i=0;
      foreach ($files as $fileInfo) {
        // 呼叫 function
        $res = uploadFile($fileInfo);
        // 上傳成功，將實際儲存檔名存入 array（以便存入資料庫）
        if (!empty($res['dest'])) {
          $uploadFiles[$i] = $res['dest'];
          $i++;
        }
      }
      //新增資料
      $announcedate=date("Y-m-d")." ".date("h:i:s");
      $insertData=array($announcetitle,$announcetype,$announcedetail,$filename,$announcedate,$imgname,$announcesubtitle);
      $sql='INSERT INTO announcement (ATitle,AType,AContext,AFile,ADate,AImage,ASubtitle) VALUES (?,?,?,?,?,?,?)';
      $sth=$link->prepare($sql);
      try{
        if($sth->execute($insertData)){
          echo"
           <div id=\"success\" title=\"新增成功\">
              新增成功<br/>
              ".$res['mes']."<br>
           </div>";
        }
        else{
          echo"
           <div id=\"failure\" title=\"新增失敗\">
              新增失敗<br/>
              ".$res['mes']."<br>
           </div>";
        }
      }
      catch (PDOException $e){
        echo"
           <div id=\"failure\" title=\"新增失敗\">
              新增失敗<br/>
              ".$res['mes']."<br>
           </div>";
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