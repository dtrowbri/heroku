<style>
    .navbar-align{
        margin-right: 50px;
    }
</style>
<nav class="navbar navbar-expand-lg" style="background-color: #fd9843">
	<div class="container">
		<div style="width: 50%">
		<?php 
		      if(isset($_SESSION['userid']) == false || $_SESSION['userid'] == null || $_SESSION['userid'] == false){
		          echo '<a href="../login/login.php" class="navbar-brand navbar-align">Login</a>';
		      } else {
	              echo '<a href="../login/logout.php" class="navbar-brand navbar-align">Logout</a>'; 
	          }
		?>
		<a href="../signup/signup.php" class="navbar-brand navbar-align">Sign up</a>
		<a href="../secrets/secrets.php" class="navbar-brand navbar-align">Secrets</a>
		</div>
		<?php 
    		if(isset($_SESSION['username']) != false || $_SESSION['username'] != null || $_SESSION['username'] != false){
    		    $username = $_SESSION['username'];
    		    if($username != null){
    		        echo '<p class="navbar-brand" style="margin: 0; ">Hello ' . $username . '!</p>';
    		    }
    		}
		?>
	</div>
</nav>