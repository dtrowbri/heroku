<html>
<head>
</head>
<body>
<?php 
    require_once '../../_header.php';
?>
	<div class="container position-absolute top-50 start-50 translate-middle">
		<form action="./registration_handler.php" method="post">
			<div class="form-group">
				<label for="Login" class="form-label">Login</label>
				<input type="text" placeholder="Login" name="Login" class="form-control shadow-sm p-3 mb-5 bg-body rounded">
			</div>
			<div class="form-group">
				<label for="password" class="form-label">Password</label>
				<input type="password" placeholder="Password" name="password" class="form-control shadow-sm p-3 mb-5 bg-body rounded">
			</div>
			<div class="form-group">
				<label for="passwordVerification" class="form-label">Re-enter password</label>
				<input type="password" placeholder="password" name="passwordVerification" class="form-control shadow-sm p-3 mb-5 bg-body rounded">
			</div>
			<input type="submit" value="Sign Up" class="btn btn-primary">
			<button type="button" onclick="window.location.href='../login/login.php'" class="btn btn-primary" style="margin-left: 50px;">Login</button>
		</form>
	</div>
<?php
    require_once '../../_footer.php';
?>
</body>
</html>