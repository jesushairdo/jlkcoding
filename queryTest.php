<?php
require_once('vendor/autoload.php');

use MicrosoftAzure\Storage\Table\TableRestProxy;
use MicrosoftAzure\Storage\Common\ServiceException;
use MicrosoftAzure\Storage\Table\Models\QueryEntitiesOptions;

//Set connection String
$connectionString = 'DefaultEndpointsProtocol=https;AccountName=jlkcoding;AccountKey=21rwyUroy2Es66UAMBeBu/9F1rvn0DGA6gsfo/1HgB9zG2XZWjDbsZVeDy5zDZJkIfZjDejdI1+5W+TfR1GHWw==';

//Create table REST proxy
$tableClient = TableRestProxy::createTableService($connectionString);

//create filter
$filter = "PartitionKey eq 'SNES'";
$options = new QueryEntitiesOptions();
$options->addSelectField('CheatNumber');
$options->addSelectField('Code');
$options->addSelectField('Description');
$options->addSelectField('GameName');


try {
    $result = $tableClient->queryEntities('tblGenieCodes', $filter);
}
catch(ServiceException $e) {
    // Handle exception based on error codes and messages
    // Error codes and messages are here: 
    // https://docs.microsoft.com/rest/api/storageservices/Table-Service-Error-Codes
    $code = $e->getCode();
    $errorMessage = $e->getMessage();
    echo $code.':'. $errorMessage.'<br />';
}

$entities = $result->getEntities();

foreach ($entities as $entity){
    echo $entity->getPartitionKey().':'. $entity->getRowKey().':'. $entity->getProperty('CheatNumber').':'.$entity->getProperty('Code').':'.$entity->getProperty('Description').':'.$entity->getProperty('GameName').'<br />';
}
?>