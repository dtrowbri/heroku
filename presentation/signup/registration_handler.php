<head>
</head>
<body>
<?php 

require_once '../../_header.php';
require_once '../../autoLoader.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('main');

$logger->pushHandler( new StreamHandler('php://stdout', Logger::DEBUG));

$login = $_POST["Login"];
$password = $_POST["password"];
$passwordVerification = $_POST["passwordVerification"];

$registrationService = new RegistrationService();

if($registrationService->doesLoginExist($login)){
    echo "The login " . $login . " is already in use, please use a different login";
} else {
    if($password != $passwordVerification){
        echo '<div class="container alert alert-danger">passwords do not match. Please try again.</div>';
    } else {
        $salt = "salt";
        $passwordHash = hash("sha512", $salt . $password);
        $isSuccessful = $registrationService->registerNewUser($login, $passwordHash);
        if($isSuccessful){
            $logger->info("Login created", ['user' => $login, 'class' => 'registration_handler.php']);
            echo '<div class="container">Your login has been successfully created.</div>';
        } else {
            $logger->error("Error registering new user.", ['session' => session_id(), 'user' => $login, 'class' => 'registration_handler.php']);
            echo '<div class="container alert alert-danger">There was an error creating your login. Please try again.</div>';
        }
    }
}
require_once '../../_footer.php';
?>
</body>
</html>
