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

//Process file
if (($handle = fopen('data/GameGenieCodes-snes.csv', 'r')) !== FALSE) {
    while (($import = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($import);
        echo '<p> '. $num .' fields in line '.$row.': <br /></p>'."\n";
        $row++;
        //is this a comment or a code
        if (is_numeric($import[1]))
        {
            //this is a game code
            $data[(cleanKeyValue($import[0]))]['codes'][($import[1])]['gameName'] = $import[0];
            $data[(cleanKeyValue($import[0]))]['codes'][($import[1])]['code'] = $import[2];
            $data[(cleanKeyValue($import[0]))]['codes'][($import[1])]['description'] = $import[3];
            $data[(cleanKeyValue($import[0]))]['codes'][($import[1])]['originalOrder'] = $import[5];
        }
        else
        {
            //this is a comment or header
            $data[(cleanKeyValue($import[0]))]['comments'][] = $import[2];
        }
    }
    fclose($handle);
}
var_dump($data);
?>