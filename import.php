<?php
require_once 'vendor/autoload.php';
require_once 'includes.inc.php';
use MicrosoftAzure\Storage\Table\TableRestProxy;
use MicrosoftAzure\Storage\Common\ServiceException;
use MicrosoftAzure\Storage\Table\Models\Entity;
use MicrosoftAzure\Storage\Table\Models\EdmType;

//Set connection String
$connectionString = 'DefaultEndpointsProtocol=https;AccountName=jlkcoding;AccountKey=21rwyUroy2Es66UAMBeBu/9F1rvn0DGA6gsfo/1HgB9zG2XZWjDbsZVeDy5zDZJkIfZjDejdI1+5W+TfR1GHWw==';

// Create Table REST proxy.
$tableClient = TableRestProxy::createTableService($connectionString);

//initiate the array
$data = array();
$cheatCount = 0;
$commentCount = 0;
$lineCount = 0;
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
?>