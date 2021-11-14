<script>
	function addRow(){
		var mainDiv = document.getElementById("extraRows");

		var rowCountElem = document.getElementById("numberOfRows");
		var value = parseInt(rowCountElem.getAttribute("value"));
		value = value + 1;
		rowCountElem.setAttribute("value", value);

		var templateDiv = document.getElementById("templateDiv");
		newDiv = templateDiv.cloneNode(true);
		newDiv.setAttribute("id", "div" + value);
		newDiv.style.visibility = "visible";
		newDiv.style.height = null;

		var keyElem = newDiv.children[0].children[1];
		var valueElem = newDiv.children[1].children[1];
		keyElem.setAttribute("id", "div" + value + "Key");
		keyElem.setAttribute("name", "key" + value);
		valueElem.setAttribute("id", "div" + value + "Value");
		valueElem.setAttribute("name", "value" + value);
		
		mainDiv.appendChild(newDiv);
	}

	function deleteRow(item){
		item.parentNode.parentNode.remove();
		var value = parseInt(document.getElementById("numberOfRows").value);
		value--;
		document.getElementById("numberOfRows").setAttribute("value", value);
		renameElements();
	}

	function renameElements(){
		var value = 1;
		var div1 = document.getElementById("div1");
		if(div1 != null){
			value = 2;
		}

		var extraRows = document.getElementById("extraRows").children;

		for(var i = 0; i < extraRows.length; i++){
			extraRows[i].setAttribute("id", "div" + value);
			extraRows[i].children[0].children[1].setAttribute("name", "key" + value);
			extraRows[i].children[0].children[1].setAttribute("id", "div" + value + "Key");
			extraRows[i].children[1].children[1].setAttribute("name", "value" + value);
			extraRows[i].children[1].children[1].setAttribute("id", "div" + value + "Value");
			value++;
		}
	}
</script>
<?php 
require_once '../shared/authenticationCheck.php';
require_once '../../_header.php';
require_once '../../autoLoader.php';
?>
<html>
<head>
</head>
<body>
	<div class="container">
		<div class="row" id="templateDiv" style="height: 35px; visibility: collapse;">
				<div class="col">
					<label for="Key" class="form-label">Key: </label>
					<input type="text" name="templateKey" id="templateKey" class="form-control shadow-sm p-3 mb-5 bg-body rounded">
				</div>
				<div class="col">
					<label for="Value" class="form-label">Value:</label>
					<input type="text" name="templateValue" id="templateValue" class="form-control shadow-sm p-3 mb-5 bg-body rounded" >
				</div>
				<div class="col">
					<button type="button"  id="deleteButton" onclick="deleteRow(this);" style="margin-top: 35px; margin-right: 50px; vertical-align: middle; font-size: 36px; background-color: white; border: white; color: blue; test-decoration: underline; cursor: pointer; float: right; ">Delete</button>
				</div> 
		</div>
		<form action="./addsecrethandler.php" method="post">
			<input type="hidden" value="1" name="numberOfRows" id="numberOfRows">
			<div class="form-group">
				<label for="SecretName" class="form-label">Secret</label>
				<input type="text" placeholder="Secret" name="SecretName" class="form-control shadow-sm p-3 mb-5 bg-body rounded">
			</div>
			<div class="row" id="div1">
					<div class="col">
    					<label for="Key" class="form-label">Key: </label>
    					<input type="text" name="key1" class="form-control shadow-sm p-3 mb-5 bg-body rounded">
					</div>
					<div class="col">
    					<label for="Value" class="form-label">Value:</label>
    					<input type="text" name="value1" class="form-control shadow-sm p-3 mb-5 bg-body rounded">
					</div>
					<div class="col">
						<button type="button"  id="deleteButton" onclick="deleteRow(this);" style="margin-top: 35px; margin-right: 50px; vertical-align: middle; font-size: 36px; background-color: white; border: white; color: blue; test-decoration: underline; cursor: pointer; float: right; ">Delete</button>
					</div> 
			</div>
			
			<div id="extraRows">
			</div>
			<div style="margin-bottom: 20px">
			<button type="button" onclick="addRow();" class="btn btn-primary">Add Row</button>
			</div>
			<input type="submit" value="Add Secret" class="btn btn-primary">
			<button type="button" onclick="window.location.href='./secrets.php'" class="btn btn-primary" style="margin-left: 50px;">Cancel</button>
		</form>
	</div>
<?php require_once '../../_footer.php'; ?>
</body>
</html>