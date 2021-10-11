<?php
require 'vendor/autoload.php';
use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;
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
$secretName ='phpSM/mysql';
$result= $client->getSecretValue([
'SecretId' => $secretName,
]);
$secret="";
if(isset($result['SecretString'])){
 $secret =$result['SecretString'];
}
$secretObj=json_decode($secret);
$host=$secretObj->host;
$username=$secretObj->username;
$password=$secretObj->password;
$conn = new mysqli($host,$username,$password,"php_sm_db");
if ($conn->connect_error){
die("Connection failed: ".$conn->connect_error);
}

$sql = "SELECT ItemName, ItemPrice FROM php_sm_table";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
  // output data of each row
echo "<table><tr><th>Name</th><th>Price</th></tr>";
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row["ItemName"]. " </td><td> " . $row["ItemPrice"]. "</td></tr> ";
  }
echo "</table>";
} else {
  echo "0 results";
}







$conn->close();


  ?>
</body>
</html>

