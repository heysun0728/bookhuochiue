<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
	
	$(function() {
    $( "#inputcon" ).autocomplete({
        source: 'schoolsource.php'
    });
});
</script>
</head>
<body>
	<div class="ui-widget"><input type="text" id="inputcon" name="inputcon"></input>
		<input type="submit" value="查詢" formmethod="post" formaction="../model/schoolquery.php?page=1"></input>
		</div>

</body>
</html>