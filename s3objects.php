<?php
require 'vendor/autoload.php';
use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
date_default_timezone_set('Africa/Lagos');
?><html>
<head><title>Secret Manager With PHP</title></head>
<body>
<?php
echo '<a href="index.php"> Home</a>';

$client=new SecretsManagerClient([

'version'=>'2017-10-17',
'region'=>'eu-west-1'
]);
$secretName ='phpSM/apiKey';
$result= $client->getSecretValue([
'SecretId' => $secretName,
]);
$secret="";
if(isset($result['SecretString'])){
 $secret =$result['SecretString'];
}
$secretObj=json_decode($secret);
$secretKey=$secretObj->secretKey;
$accessKey=$secretObj->accessKey;
$s3 = new S3Client([
    'version' => 'latest',
  'credentials'=>array(
'key'=>$accessKey,
'secret'=>$secretKey,
),

    'region'  => 'eu-west-1'
]);
$bucket = 'phpsmbucket';
try {
    $results = $s3->getPaginator('ListObjects', [
        'Bucket' => $bucket
    ]);
echo "<table>";
    foreach ($results as $result) {
       echo "<tr>";
        foreach ($result['Contents'] as $object) {
            echo "<td>" . $object['Key'] . " </td> ";
        }
       echo "</tr>";
 }
echo "</table>";
} catch (S3Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}



  ?>
</body>
</html>