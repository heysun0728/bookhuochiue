<?php 
   session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery-2.1.4.min.js"></script>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/form_style.css">
<link rel="stylesheet" href="../css/table_style2.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>公告</title>
</head>
<script>
$(document).ready(function(){
	nowiswhite=$("#first");//替換前的公告
	$(".list_item").click(function(){
        //把現在的調白,把以前的調黑
		nowiswhite.css("background-color","#f5f5f5");
        $(this).css("background-color","white");
        nowiswhite=$(this);
        choose=$(this).attr('data-value');//目前選到了編號幾的公告

        date=$("#ann_"+choose+" #datetext").text();
        title=$("#ann_"+choose+" #titletext").text();
        img=$("#ann_"+choose+" #imgtext").text();
        context=$("#ann_"+choose+" #contexttext").text();

    	$("#date span").text(date);
    	$("#title span").text(title);
		$("#context span").text(context);        
        $('#imgbox img').attr("src",img);

	});
});
</script>
<body>
<?php include '../need.php';?> 
<div class="zone1">
	<div id="scroll_list">
	    <div id="head"></div>
	    <div id="list">
	        <?php
                require "../DbConnect.php";
            	$sql = 'SELECT * FROM announcement ORDER BY ADate DESC';
            	$rs=$link->prepare($sql);
				$rs->execute();
				$i=0;
				$rows=$rs->fetchall();
				for($i=0;$i<count($rows);$i++){
					if($i==0){
						echo '<div class="list_item" id="first" style="background-color:white" data-value="'.$rows[$i]["A_No"].'">';
					}
					else{
						echo '<div class="list_item" data-value="'.$rows[$i]["A_No"].'">';
					}
					echo '<div id="date">'.substr($rows[$i]["ADate"],0,10).'</div>
					 	  <div id="title">'.$rows[$i]["ATitle"].'</div>
					 	  </div>';
				}
	        ?>
	    </div>
	</div>	
</div>
<div id="menu"></div>
<div class="zone3">
	<div id="essay">
		<div id="date"><span><?php echo substr($rows[0]["ADate"],0,10);?></span></div></br>
		<div id="title"><span><?php echo $rows[0]["ATitle"];?></span></div>
		<div id="imgbox"><img src=<?php echo "../upload/".$rows[0]["AImage"];?>></img></div>
		<div id="context"><span><?php echo $rows[0]["AContext"];?></span></div>	
	</div>
</div>
<?php 
for($i=0;$i<count($rows);$i++){
	$url="../upload/".$rows[$i]["AImage"];
	echo '<div id="ann_'.$rows[$i]["A_No"].'" style="display:none;">
	   	  	<span id="datetext">'.substr($rows[$i]["ADate"],0,10).'</span></br>
		  	<span id="titletext">'.$rows[$i]["ATitle"].'</span></br>
		  	<span id="imgtext">'.$url.'</span>
		  	<span id="contexttext">'.$rows[$i]["AContext"].'</span></br>
		  </div>';
}
?>
</body>
</html>