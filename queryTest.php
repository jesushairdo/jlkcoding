<?php
require_once('vendor/autoload.php');

use MicrosoftAzure\Storage\Table\TableRestProxy;
use MicrosoftAzure\Storage\Common\ServiceException;
use MicrosoftAzure\Storage\Table\Models\QueryEntitiesOptions;

$storageAccountName = getenv('TABLE_ACCOUNTNAME');
$storageAccountKey = getenv('TABLE_KEY');

//Set connection String
$connectionString = 'DefaultEndpointsProtocol=https;AccountName='.$storageAccountName.';AccountKey='.$storageAccountKey;

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
echo count($entities) .' results found <br />';
$i=0;
foreach ($entities as $entity){
    echo $entity->getPartitionKey().':'. $entity->getRowKey().':'. $entity->getProperty('CheatNumber')->getValue().':'.$entity->getProperty('Code')->getValue().':'.$entity->getProperty('Description')->getValue().':'.$entity->getProperty('GameName')->getValue().'<br />'."\n";
    $i++;
    print 'Row '. $i .' <br />'."\n";
}
?>