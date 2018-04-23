<?php
require_once 'vendor/autoload.php';
require_once 'includes.inc.php';
use MicrosoftAzure\Storage\Table\TableRestProxy;
use MicrosoftAzure\Storage\Common\ServiceException;
use MicrosoftAzure\Storage\Table\Models\Entity;
use MicrosoftAzure\Storage\Table\Models\EdmType;
use MicrosoftAzure\Storage\Table\Models\BatchOperations;

$storageAccountName = getenv('TABLE_ACCOUNTNAME');
$storageAccountKey = getenv('TABLE_KEY');

//Set connection String
$connectionString = 'DefaultEndpointsProtocol=https;AccountName='.$storageAccountName.';AccountKey='.$storageAccountKey;

// Create Table REST proxy.
$tableClient = TableRestProxy::createTableService($connectionString);

//initiate the array
$data = array();
$cheatCount = 0;
$commentCount = 0;
$lineCount = 0;
$entitiesInserted = 0;
//Process file
if (($handle = fopen('data/GameGenieCodes-snes.csv', 'r')) !== FALSE) {
    while (($import = fgetcsv($handle, 1000, ",")) !== FALSE) {
        //check to see if this a comment or a code
        if (is_numeric($import[1]))
        {
            //this is a game code
            $data[(cleanKeyValue($import[0]))]['codes'][($import[1])]['gameName'] = $import[0];
            $data[(cleanKeyValue($import[0]))]['codes'][($import[1])]['code'] = $import[2];
            $data[(cleanKeyValue($import[0]))]['codes'][($import[1])]['description'] = $import[3];
            $data[(cleanKeyValue($import[0]))]['codes'][($import[1])]['originalOrder'] = $import[5];
            $cheatCount ++;
        }
        else
        {
            //this is a comment or header
            $data[(cleanKeyValue($import[0]))]['comments'][] = $import[2];
            $commentCount ++;
        }
        $lineCount++;
    }
    fclose($handle);
}
//output some information
print '<p><strong>'. $lineCount .'</strong> lines processed</p>';
print '<p>The following was discovered</p>';
print '<ul><li><strong>'. count($data) .'</strong> games</li>';
print '<li><strong>'. $cheatCount .'</strong> cheats</li>';
print '<li><strong>'. $commentCount .'</strong> comments</li></ul>';

//import data into Azure Table Storage using a batch
//$ops = new BatchOperations();

foreach ($data as $gameKey => $info)
{
    //print '<!-- '.$gameKey.':'. print_r($info,true) .' -->'."\n\n";
    //check that the game has codes
    if (array_key_exists('codes',$info))
    {
        //loop through each game's codes
        foreach ($info['codes'] as $codeNumber => $codeInfo)
        {
            //create entity
            $entity = new Entity();
            $entity->setPartitionKey("SNES");
            $entity->setRowKey(''. $gameKey .'-'.str_pad($codeNumber,6,0,STR_PAD_LEFT));
            $entity->addProperty("GameName", EdmType::STRING, $codeInfo['gameName']);
            $entity->addProperty("CheatNumber", EdmType::INT32, $codeNumber);
            $entity->addProperty("CheatComments", EdmType::STRING, "None");
            $entity->addProperty("Code", EdmType::STRING, $codeInfo['code']);
            $entity->addProperty("Description", EdmType::STRING, $codeInfo['description']);
  
            //add entity to table (individually)
            try{
                $tableClient->insertOrReplaceEntity('tblGenieCodes', $entity);                
                $entitiesInserted ++;
            }
            catch(ServiceException $e){
                // Handle exception based on error codes and messages.
                // Error codes and messages are here:
                // https://docs.microsoft.com/rest/api/storageservices/Table-Service-Error-Codes
                $errorCode = $e->getCode();
                $error_message = $e->getMessage();
                print $errorCode .': '. $error_message.'<br />';
            }
  
            //add entity to batch
            //$ops->addInsertorReplaceEntity('tblGenieCodes', $entity);
        }
    }
}
//print '<!-- '. print_r($ops,true) .' -->';
//submit batch process
/*
try{
    $tableClient->batch($ops);
}
catch(ServiceException $e){
    // Handle exception based on error codes and messages.
    // Error codes and messages are here:
    // https://docs.microsoft.com/rest/api/storageservices/Table-Service-Error-Codes
    $errorCode = $e->getCode();
    $error_message = $e->getMessage();
    print $errorCode .': '. $error_message.'<br />';
}
*/
print '<p><strong>'. $entitiesInserted.'</strong> codes have been inserted into storage</p>';
?>