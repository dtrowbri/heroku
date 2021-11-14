<html>
<head>
</head>
<body>
<?php 
require_once '../../_header.php';
require_once '../../autoLoader.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('main');
$logger->pushHandler( new StreamHandler('php://stderr', Logger::DEBUG));

$login = $_POST["Login"];
$password = $_POST["Password"];
$salt = "salt";
$passwordHash = hash("sha512", $salt . $password);

$logger->info('Starting session', ['session' => session_id(),'user' => $login, 'class' => 'loginhandler.php']);

$loginService = new LoginService();
if($loginService->validateLogin($login, $passwordHash)){
    $userId = $loginService->getUserId($login);
    $userName = $loginService->getUserName($userId); 
    if($userId == "Error"){
        $logger->warning('Failure retrieving UserId.', ['user' => $login, 'class' => 'loginhandler.php']);
        header("Location: ./login.php");
    }
    
    $logger->info('Authentication Success', ['user' => $login, 'class' => 'loginhandler.php']);
    $_SESSION["authenticated"] = true;
    $_SESSION["userid"] = $userId;
    $_SESSION['username'] = $userName;
     
    header("Location: ../secrets/secrets.php");
} else {
    $logger->warning('Authentication Failure', ['user' => $login, 'class' => 'loginhandler.php']);
    echo '<div class="container alert alert-danger">The username or password is incorrect. Please try again.</div>';
}

require_once '../../_footer.php';
?>
</body>
</html>
