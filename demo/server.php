<?php

$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    throw new RuntimeException('Install dependencies using composer to run the demo.');
}

require_once $autoload;

use Railken\SQ\QueryParser;


$parser = new QueryParser();

try {
	$result = $parser->parse($_GET['q']);
} catch (\Exception $e) {
	http_response_code(400);
	echo json_encode((object)["message" => $e->getMessage()]);
	die();
}

echo json_encode((object)["query" => $result]);