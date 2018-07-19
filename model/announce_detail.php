<?php 
   session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>公告</title>
<link rel="stylesheet" href="../css/style.css">
<link href="../css/form_style.css" rel="stylesheet" type="text/css" >
</head>
<style>
.main{
   position:relative;
   top:10%;
}
#ann_img{
    height:30%;
    width:60%;
}
#ann_img img{
    min-height:100%;
}
h1{
    font-size:40px;
}
p{
    font-size:20px;
}
</style>
<body>
<nav>
<!--匯入左邊索引欄-->
<?php include '../nav_control.php';?> 
</nav>
<article style="overflow-y:scroll;">
<?php

   require "../DbConnect.php";
    
    $ano=$_GET['ano'];

    $sql='SELECT * FROM announcement
          WHERE A_No=:ano';
    $rs=$link->prepare($sql);
    $rs->bindValue(':ano',$ano);
    $rs->execute();
    $rst=$rs->fetch();
            echo "<div class='main'>";
            if(!empty($rst["AImage"])){
              echo "<div id='ann_img'>";
              echo "<img src=../upload/".$rst["AImage"]." \>";
              echo "</div>";
            }
            echo "<h1>".$rst["ATitle"]."<h1><span>類型：".$rst["AType"]."&nbsp;&nbsp;發布日期：".$rst["ADate"]."</span><br/>";
            echo "<h1>".$rst["ASubtitle"]."<h1><br>";
            echo "<h1>".$rst["AContext"]."<h1><br>";
            if(!empty($rst["AFile"])){
               echo "附加檔案：<a href='../upload/".$rst["AFile"]."'>".$rst["AFile"]."</a>";
            }
       
    echo "</div>";//end main
?>
</article>
</body>
</html>