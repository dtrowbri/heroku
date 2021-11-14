<?php 
require_once '../shared/authenticationCheck.php';
require_once '../../_header.php';
require_once '../../autoLoader.php';
?>

<html>
<head>
</head>
<body>
	<div class="container" style="margin-top: 50px;">
<?php 
            $userid = $_SESSION["userid"];
            $secretId = $_POST["secretId"];
            $secretName = $_POST["secretName"];
            
            $service = new SecretsService();
            $kvPairs = $service->getKVPair($secretId);
            
            $numOfKVPairs = 0;
            
            echo '<form action="updatesecret.php" method="post">';
            echo '<div class="form-group">';
            echo '<label for="secretName" class="form-label">Secret</label>';
            echo '<input type="hidden" value="' . $secretId . '" name="secretId">';
            echo '<input type="text" value="' . $secretName . '" name="secretName" class="form-control shadow-sm p-3 mb-5 bg-body rounded" readonly>';
            echo '</div>';
            
            foreach($kvPairs as $kvPair){
                $numOfKVPairs++;
                
                echo '<div class="row"> <div class="col">';
                echo '<input type="hidden" name="keyId' . $numOfKVPairs . '" value ="' . $kvPair->getKeyId() . '">';
                echo '<label for="key" class="form-label">Key</label>';
                echo '<input type="text" value="' . $kvPair->getKey() . '" name="key' . $numOfKVPairs . '" class="form-control shadow-sm p-3 mb-5 bg-body rounded">';
                echo '</div>';
                
                echo '<div class="col">';
                echo '<label for="value" class="form-label">Value: </label>';
                echo '<input type="text" value="' . $kvPair->getValue() . '" name="value' . $numOfKVPairs . '" class="form-control shadow-sm p-3 mb-5 bg-body rounded">';
                echo '</div>';
                echo '</div>';
            }
            
            echo '<input type="hidden" value="' . $numOfKVPairs . '" name="numOfKVPairs">';
            echo '<input type="submit" value="Update" class="btn btn-primary">';
            echo '<input type="button" value="Cancel" onclick="window.location.href=\'./secrets.php\'" class="btn btn-primary" style="margin-left: 50px;">';
            echo '</form>';
        ?>
	</div>
<?php require_once '../../_footer.php'; ?>
</body>
</html>