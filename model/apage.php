<?php
    $rowCount=count($rows);
	$min=($_GET["page"]-1)*3;
	if($_GET["page"]*3>$rowCount){
		$max=$rowCount;
	}
	else{
		$max=$_GET["page"]*3;
	}

	$pagesum=$rowCount/3;
    if($rowCount%3!=0){
        	$pagesum+=1;
    }
?>