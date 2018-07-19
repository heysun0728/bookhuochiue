<?php
$a=array(31,29,31,30,31,30,31,31,30,31,30,31);
for($i=1;$i<=12;$i++){
   $up=$a[$i-1];
   for($j=1;$j<=$up;$j++){
       $d="2016-";
       if($i<10){
       	  $d.="0".$i."-";
       }
       else{
       	  $d.=$i."-";
       }
       if($j<10){
          $d.="0".$j;
       }
       else{
          $d.=$j;
       }
       require "../DbConnect.php";

       for($x=1;$x<=8;$x++){
       	  $ReserveAmount=rand(4,10);
       	  $insertData=array($d,$x,0,$ReserveAmount,'');
          
          $sql='INSERT INTO timeinterval(ServiceDate,timeid,NumberOfPeople,ReserveAmount,ReserveNote) 
                VALUES (?,?,?,?,?)';
          $rs=$link->prepare($sql);
          if($rs->execute($insertData)){echo "成功<br>";};
	   }
       
   }
}


?>
