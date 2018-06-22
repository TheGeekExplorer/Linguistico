<?php declare(strict_types=1); namespace Linguistico;

include_once("autoload.php");

use Linguistico\lib\Receive\OpenArtifact;
use Linguistico\lib\Send\BuildArtifact;
use Linguistico\lib\Exception;
use Linguistico\lib\Zip;


$WP = new BuildArtifact('product');

for ($i=0; $i<10000; $i++) {
    $WP->addRecord([
        'sku' => '501545462212',
        'image' => '501545462212.jpg',
        'description' => 'Mr Baloos Oven Cleaning Sponges 4xTwin Pack',
        'longdescription' => 'When you buy Mr Baloos Oven Cleaning Sponges 4xTwin Pack you quality of clean is exponentiated!',
        'manufacturer' => 'Mr Baloo',
        'categoryid' => '1',
        'subcategoryid' => '14',
        'price' => '13.99',
        'stock' => '25',
        'status' => '1',
        'agerestricted' => '0'
    ]);
}

$WP->build();