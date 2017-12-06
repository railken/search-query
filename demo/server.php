<?php

$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    throw new RuntimeException('Install dependencies using composer to run the demo.');
}

require_once $autoload;

use Railken\SQ\QueryParser;


$parser = new QueryParser();
$result = $parser->parse($_GET['q']);

echo json_encode((object)["query" => $result]);