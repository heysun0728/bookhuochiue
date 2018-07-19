<?php 
  session_start(); 
  require "../DbConnect.php";
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
<title>申請確認</title>
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
      modal:true,
      buttons: { 
        "OK": function() { 
            $(this).dialog("close");
            $(this).onClick(location='../model/apply_confirm.php');
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
    <!--MM_openBrWindow用來進行另開分頁動作 target一定要設-->
    <form id="form_type1" method="post" action="../tcpdf/print.php" onsubmit="MM_openBrWindow('','print','width=200,height=200')" target="print">
    <?php
    $sql="SELECT m.*,sch.schoolName,
                 SUM(s.ServiceHour) as sHours,
                 MIN(s.StartTime) as StartTime,
                 MAX(s.StartTime) as EndTime
          FROM member m,servicerecord s,school sch
          WHERE m.vNumber=s.vNumber
          AND s.ReserverState=:ReserverState
          AND m.School=sch.schoolid
          GROUP BY m.vNumber";
    $rs=$link->prepare($sql);
    $rs->bindValue(':ReserverState',"申請");
    if($rs->execute()){
        echo "<table class=\"table table-striped table-hover\">
          <tr><th>申請狀態</th><th>學號</th><th>學校</th><th>姓名</th><th>連絡電話</th><th>時間</th><th>申請時數</th><th></th></tr>";
        $td_index=array("ApplyState","IDNumber","schoolName","Name","Phone","","sHours");
        while($row=$rs->fetch()){
            echo "<tr>";
            for($i=0;$i<=6;$i++){
              if($i==5){
                 //去時間
                 $s=substr($row["StartTime"],0,10);
                 $e=substr($row["EndTime"],0,10);
                 //年月日放進陣列
                 $s_date=explode("-",$s);
                 $year=(int)$s_date[0]-1911;//民國
                 echo "<td>民國".$year."年".(int)$s_date[1]."月".(int)$s_date[2]."日";
                 if($s!=$e){
                    $e_date=explode("-",$e);
                    echo "至";
                    if($s_date[0]!=$e_date[0]){//若間隔兩年不同年
                      $year=(int)$e_date[0]-1911;
                      echo "民國".$year."年";
                    }
                    echo (int)$e_date[1]."月".(int)$e_date[2]."日";
                 }
                 echo "</td>";
              }
              else{
                echo "<td>".$row[$td_index[$i]]."</td>";
              }
              
            }
            echo "<td><button name='confirm_btn' type='submit' value='".$row["vNumber"]."'>完成確認</button></td>";
            echo "</tr></form>";
        }
    }
?>
</form>

</div>
</body>
</html>