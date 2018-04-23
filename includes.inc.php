<?php

function cleanKeyValue($valueToClean)
{
    //Characters not allowed in content of key fields
    // /
    // \
    // #
    // ?
    // U+0000 to U+001F
    //   including \t \n \r
    // U+007F to U+009F
    //$stringsToClean[] = "/";
    //$stringsToClean[] = "\\";
    //$stringsToClean[] = "#";
    $stringsToClean[] = '/[^a-zA-Z0-9!'\-]/';

    $cleanedValue = preg_replace($stringsToClean,'-',trim($valueToClean));
    return $cleanedValue;
}