<html>
<head>
</head>
<body>
<?php 
    require_once '../../_header.php';
    require_once '../../autoLoader.php';
?>
<div class="container position-absolute top-50 start-50 translate-middle">
	<form action="./loginhandler.php" method="POST">
		<div class="form-group">
			<label for="Login" class="form-label">Login</label>
			<input type="text" placeholder="Login" name="Login" class="form-control shadow-sm p-3 mb-5 bg-body rounded">
		</div>
		<div class="form-group">
			<label for="Password" class="form-label">Password</label>
			<input type="password" placeholder="Password" name="Password" class="form-control shadow-sm p-3 mb-5 bg-body rounded">
		</div>
		
		<input type="submit" value="Login" class="btn btn-primary">
		<button type="button" onclick="window.location.href='../signup/signup.php'" class="btn btn-primary" style="margin-left: 50px;">Sign UP</button>
	</form>
</div>
<?php 
    require_once '../../_footer.php';
?>
</body>
</html>
