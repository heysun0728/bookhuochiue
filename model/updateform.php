<?php  
        
        echo "<div class=\"form-group1\">";
        echo "<b>帳號：(無法修改)</b><br/>";
        echo "". $rst["ID"] ."<br/><br/>";
        echo "<label for=\"input-pwd\">密碼：</label><br/>";
        echo "<input name=\"inputpwd\" id=\"input-pwd\" type=\"password\" required/><br/>";
        echo "<label for=\"chk-pwd\">確認密碼：</label><br/>";
        echo "<input name=\"chkpwd\" id=\"chk-pwd\" type=\"password\" required/> <br/>";
        echo "<label for=\"input-phone\">電話：</label><br/>";
        echo "<input name=\"inputphone\" id=\"input-phone\" type=\"tel\"  value=".$rst["Phone"]." /> <br/>";
        echo "<label for=\"input-email\">電子郵件：</label><br/>";
        echo "<input name=\"inputemail\" id=\"input-email\" type=\"email\"  value=".$rst["Email"]." /><br/>";
        echo "<label>生日(無法修改)</label><br/>";
        echo "". $rst["Birthday"] ."<br/><br/>";
        //身分證字號
        echo "<label>身分證字號(無法修改)</label><br/>";
        echo "". $rst["IDNumber"] ."<br/><br/></div>";
        
        if($RoleID=='R1'){
        echo "<div class=\"form-group2\">";
        //學校
        echo "就讀學校：<br/>";
        $school=$rst['schoolName'];
        echo "<select name=\"select_school\">";
        $sql = 'SELECT * FROM school';
        $rs=$link->prepare($sql);
        $rs->execute();
        while($row=$rs->fetch()){
            if($row["schoolName"]==$school){
              echo "<option selected value='".$row["schoolid"]."'>".$row["schoolName"]."</option>";
            }
            else{
              echo "<option>".$row["schoolName"]."</option>";
            }
        }
        echo "</select><br/>";
        

        //監護人姓名
        echo "<label for=\"input-pname\">監護人姓名：</label><br/>";
        echo "<input name=\"inputpname\" id=\"input-pname\" type=\"text\"  value=".$rst["ParentName"]." /><br/>";
        //監護人電話
        echo "<label for=\"input-pphone\">監護人電話：</label><br/>";
        echo "<input name=\"inputpphone\" id=\"input-pphone\" type=\"text\"  value=".$rst["ParentPhone"]." /><br/>";
        //與監護人關係
        echo "<label for=\"input-relationship\">監護人關係：</label><br/>";
        echo "<input name=\"inputprelationship\" id=\"input-relationship\" type=\"text\"  value=".$rst["ParentRelationship"]." /></div>";
        }

        //訊息
        echo "<div style=\"padding-top: 150%;margin-left: -110px;\"><h3>如欲修改(無法修改)之項目，請洽館員!!</h3></div>";
        //button
        echo "<input class ='form-group3' type=\"submit\" name=\"button\" value=\"確定修改\" /><br/><br/>";


?>