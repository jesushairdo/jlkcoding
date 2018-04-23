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

//Process file
if (($handle = fopen('data/GameGenieCodes-snes.csv', 'r')) !== FALSE) {
    while (($import = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($import);
        echo '<p> '. $num .' fields in line '.$row.': <br /></p>'."\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
            echo cleanKeyValue($import[$c]) . '<br />'."\n";
        }
    }
    fclose($handle);
}
?>