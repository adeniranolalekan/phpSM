<?php
error_reporting(0);
require 'vendor/autoload.php';
use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;
date_default_timezone_set('Africa/Lagos');
?><html>
<head><title>Secret Manager With PHP</title></head>
<body>
<?php

  class DbItem{
  public $name;
  public $price;
  function __construct($name, $price){
      $this->name = $name;
      $this->price = $price;
  }
  }

 echo '<form action="index.php" method="post">
 Enter the name of Item<input name="name" type="text">
 Enter the price of Item<input name="price" type="text">
 <input type="submit">
 </form>
 <form action="list.php">

  <input type="submit" value="List Items">
  </form>
<form action="s3objects.php">

  <input type="submit" value="S3 Objects">
  </form>';

if($_POST["name"] && $_POST["price"]){
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
$dbItem= new DbItem($_POST["name"],$_POST["price"]);
echo ''.$dbItem->name.' and '.$dbItem->price;
$conn = new mysqli($host,$username,$password,"php_sm_db");
if ($conn->connect_error){
die("Connection failed: ".$conn->connect_error);
}

$sql = "INSERT INTO php_sm_table (ItemName, ItemPrice) VALUES ('$dbItem->name', '$dbItem->price')";

if ($conn->query($sql)===TRUE){
 echo "New record created successfully";
}else{
echo "Error: ".$sql."<br>".$conn->error;
}
$conn->close();

}
 ?>
</body>
</html>

