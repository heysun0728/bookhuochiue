<?php
    if(isset($rows)){
    	$rowCount=count($rows);//如果不是用rows請自己設定$rowCount
    }
	$min=($_GET["page"]-1)*10;
	if($_GET["page"]*10>$rowCount){
		$max=$rowCount;
	}
	else{
		$max=$_GET["page"]*10;
	}

	$pagesum=$rowCount/10;
    if($rowCount%10!=0){
        	$pagesum+=1;
    }

?>