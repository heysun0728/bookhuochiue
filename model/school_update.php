<?php 
  session_start(); 
  require "../DbConnect.php";
?>
<!DOCTYPE html>
<html>
<head>
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/jquery.validate.js"></script>
<link href="../css/jquery-ui.css" rel="stylesheet" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新增學校</title>
<script>
 $(function() {
      //成功動作
       $( "#success" ).dialog({ 
          modal:true,
          buttons: { 
            "OK": function() { 
               $(this).dialog("close");
               $(this).onClick(location='../model/SchoolManagement.php?page=1');
                   
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
<?php
	//判斷權限
	if(isset($_SESSION['myID'])){
    	$myID=$_SESSION['myID'];
		$sql = 'SELECT	r.RoleID, c.school_update
				FROM role r, member m, comptence c
				WHERE r.RoleID = m.RoleID AND r.Roleindex = c.Roleindex AND m.id=:myID';
		$rs=$link->prepare($sql);
		$rs->bindValue(':myID',$myID);
		$rs->execute();
		$rst=$rs->fetch();
		$RoleID=$rst['RoleID'];
		$school_update=$rst['school_update'];
	}
    
    if($school_update=='1'){
		$updateNumber = $_GET['value'];
		$sql='SELECT * FROM school WHERE sindex=:updateNumber';
		$rs=$link->prepare($sql);
		$rs->bindValue(':updateNumber',$updateNumber);
		$rs->execute();
		$rst=$rs->fetch();
		echo "<form id=\"form_type1\" style=\"width:30%\" name=\"form\" method=\"post\" action='' >";

        echo "<input name=\"inputindex\" id=\"input-index\" type=\"hidden\"  value=". $rst["sindex"] ."><br/>"; 

        echo "<div class=\"form-group\"><label for=\"input-id\">學校編號：</label><br/>";
        echo "<input name=\"inputid\" id=\"input-id\" type=\"text\"  value=". $rst["schoolid"] ." ><br/></div>";

        echo "<div class=\"form-group\"><label for=\"input-name\">學校名稱：</label><br/>";
        echo "<input name=\"inputname\" id=\"input-name\" type=\"text\" value=".$rst["schoolName"]."><br/></div>";

        echo "<label for=\"schoolcity\">學校區域：</laberl><br/>";
		echo "<select name=\"schoolcity\">";
  		$sql = 'SELECT * FROM city';
  		$rs=$link->prepare($sql);
  		$rs->execute();
  		while($row=$rs->fetch()){
    		if($rst["City"]==$row["cityid"])
    			echo "<option selected value=".$row["CityName"]." >".$row["CityName"]."</option>";
			else
				echo "<option value=".$row["CityName"]." >".$row["CityName"]."</option>";
  		}//end of while
  		echo "</select><br/>";


		echo "<label for=\"schooltype\">學校類型：</laberl><br/>";
	    echo "<select name=\"schooltype\">";
  		$sql = 'SELECT * FROM SchoolLevel';
  		$rs=$link->prepare($sql);
  		$rs->execute();
  		while($row=$rs->fetch()){
  			if($rst["Level"]==$row["SLID"])
    			echo "<option selected value=".$row["SLtype"]." >".$row["SLtype"]."</option>";
			else
				echo "<option value=".$row["SLtype"]." >".$row["SLtype"]."</option>";
  		}//end of while
  		echo "</select><br/>";

        echo "<input type=\"submit\" name=\"button\" value=\"確定修改\" /><br/><br/>";
        echo "</form>";
		
		//更新
		if ($_SERVER["REQUEST_METHOD"]=='POST') {
        //$_POST[name]取得post來的資料
	 	    if(isset($_POST['inputindex']) && isset($_POST['inputid']) && isset($_POST['inputname']) && isset($_POST['inputcity']) && isset($_POST['inputtype'])){
			    $sindex=$_POST['inputindex'];
		   	    $schoolid = $_POST['inputid'];          
		        $schoolName=$_POST['inputname'];         
	            $city = $_POST['inputcity'];         
	       		$level = $_POST['inputtype'];
	       		require "../DbConnect.php";
	       		$sql = 'UPDATE school
						SET schoolid=:schoolid,
							schoolName=:schoolName,
							City=:city,
							Level=:level
						WHERE sindex=:sindex';
				$rs=$link->prepare($sql);
				$rs->bindValue(':sindex',$sindex);
				$rs->bindValue(':schoolid',$schoolid);
				$rs->bindValue(':schoolName',$schoolName);
				$rs->bindValue(':city',$city);
				$rs->bindValue(':level',$level);
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
		    }else{
		    	echo "接收資料失敗<br/>";
		    }
		    
		}	
	}else{
		echo "<div id=\"failure_c\" title=\"沒有權限瀏覽\">沒有權限瀏覽<br/></div>";
		echo "<script>delay();</script>";
	}	
?>
</div>
</body>
</html>