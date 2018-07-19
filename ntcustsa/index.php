<html>
<head>
	

</head>
<?php require "DbConnect.php";?>
<body>
	<form method="post">
		<label for="ProductName">商品名稱</label>
		<input id="ProductName" name="ProductName" maxlength="20">
		<br/>

		<label for="ProductType">商品類別</label>
		<select id="ProductType" name="ProductType">
    	<?php
    	  $sql = 'SELECT * FROM producttype';
    	  $rs = $link->prepare($sql);
    	  $rs -> execute();
    	  while($row = $rs -> fetch()){
    	    echo "<option value=".$row[PTID]." >".$row[PTName]."</option>";
    	  }
    	?>
  </select>
  		<br/>

		<label for="Brand">品牌</label>
		<select id="Brand" name="Brand">
    	<?php
    	  $sql = 'SELECT * FROM brand';
    	  $rs = $link->prepare($sql);
    	  $rs -> execute();
    	  while($row = $rs -> fetch()){
    	    echo "<option value=".$row[BrandID]." >".$row[BrandName]."</option>";
    	  }
    	?>
  </select>
  		<br/>

		<label for="ProductItem">商品子類別</label>
		<select id="ProductItem" name="ProductItem">
    	<?php
    	  $sql = 'SELECT * FROM productitem';
    	  $rs = $link->prepare($sql);
    	  $rs -> execute();
    	  while($row = $rs -> fetch()){
    	    echo "<option value=".$row[PIID]." >".$row[PIName]."</option>";
    	  }
    	?>
  </select>
		<label for="ProductNO">商品貨號</label>
		<input id="ProductNO" name="ProductNO" maxlength="20">
		<label for="ProductSpec">商品規格</label>
		<input id="ProductSpec" name="ProductSpecs" maxlength="20">
		<label for="ProductName">商品名稱</label>
		<input id="ProductName" name="ProductName" maxlength="20">
	</form>

</body>
</html>