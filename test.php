<?php
require_once 'vendor/autoload.php';
use MicrosoftAzure\Storage\Table\TableRestProxy;
use MicrosoftAzure\Storage\Common\ServiceException;
use MicrosoftAzure\Storage\Table\Models\Entity;
use MicrosoftAzure\Storage\Table\Models\EdmType;

//Set connection String
$connectionString = "DefaultEndpointsProtocol=https;AccountName=jlkcoding;AccountKey=21rwyUroy2Es66UAMBeBu/9F1rvn0DGA6gsfo/1HgB9zG2XZWjDbsZVeDy5zDZJkIfZjDejdI1+5W+TfR1GHWw==";

// Create Table REST proxy.
$tableClient = TableRestProxy::createTableService($connectionString);

try    {
    // Create table.
    $tableClient->createTable("tblGenieCodes");
}
catch(ServiceException $e){
    $code = $e->getCode();
    $error_message = $e->getMessage();
    // Handle exception based on error codes and messages.
    // Error codes and messages can be found here:
    // https://docs.microsoft.com/rest/api/storageservices/Table-Service-Error-Codes
}

//Insert some data

$entity = new Entity();
$entity->setPartitionKey("SNES");
$entity->setRowKey("ActRaiser-1");
$entity->addProperty("GameName", EdmType::STRING, "Act Raiser");
$entity->addProperty("CheatNumber", EdmType::INT32, 1);
$entity->addProperty("CheatComments", EdmType::STRING, "None");
$entity->addProperty("Code", EdmType::STRING, "2264-6FD4");
$entity->addProperty("Description", EdmType::STRING, "Almost invincible in action sequences");

try{
    $tableClient->insertEntity("tblGenieCodes", $entity);
}
catch(ServiceException $e){
    // Handle exception based on error codes and messages.
    // Error codes and messages are here:
    // https://docs.microsoft.com/rest/api/storageservices/Table-Service-Error-Codes
    $code = $e->getCode();
    $error_message = $e->getMessage();
}
?>