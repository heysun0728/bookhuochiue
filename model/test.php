<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>jQuery UI 对话框（Dialog） - 模态确认</title>
  <!--jQuery-->
<script src="../js/jquery-2.1.4.js"></script>
<script src="../js/jquery.validate.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>


    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
    
    <script type=\"text/javascript\">
      $(function(){
        $("#edit").click(function(){
          var offset = $(this).offset(),
            num = $(this).siblings("input[name=num]").val(),
            phone = $("#phone" + num).val(),
            address = $("#address" + num).val();
          
          $("#editNum").val(num);
          $("#editPhone").val(phone);
          $("#editAddress").val(address);
          
          $("#editTable").css({
            "position": "absolute",
            "top": (offset.top - 65) + "px",
            "left": (offset.left + 40) + "px",
            "display": "block"
          });
          
        });
        
        $("#editTable .ok").click(function(){
          var num = $("#editNum").val();
          
          $("#phone" + num).val($("#editPhone").val());
          $("#address" + num).val($("#editAddress").val());
          
          $("#editTable").hide();
        });
      });
    </script>
    
  </head>
  <body>
   <table width="200px;" style="margin-top:100px;">
      <tr>
        <td width="100px;">
          小明  
        </td>
        <td>
          <input type="button" value="edit" id="edit">
          <input type="hidden" name="num" value="1">
          <input type="hidden" name="phone1" id="phone1" value="0912345678">
          <input type="hidden" name="address1" id="address1" value="taiwan">
        </td>
      </tr>
      <tr>
        <td>
          小華  
        </td>
        <td>
          <input type="button" value="edit" id="edit">
          <input type="hidden" name="num" value="2">
          <input type="hidden" name="phone1" id="phone2" value="0912312312">
          <input type="hidden" name="address1" id="address2" value="macau">
        </td>
      </tr>
    </table>
    
    <table id="editTable" cellpadding='1' cellspacing='1' style="border:1px 000000 solid; display:none;">
      <tr>
        <td>電話</td>
        <td><input type="text" id="editPhone"></td>
      </tr>
      <tr>
        <td>地址</td>
        <td><input type="text" id="editAddress"></td>
      </tr>
      <tr>
        <td><input type="hidden" id="editNum"></td>
        <td><input type="button" class="ok" value="ok"></td>
      </tr>
    </table>
  </body>
</html>