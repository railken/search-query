<?php

$autoload = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($autoload)) {
    throw new RuntimeException('Install dependencies using composer to run the demo.');
}

require_once $autoload;

use Railken\SQ\QueryParser;
use Railken\SQ\Resolvers as Resolvers;

$parser = new QueryParser();
$parser->addResolvers([
    new Resolvers\ValueResolver(),
    new Resolvers\KeyResolver(),
    
    new Resolvers\GroupingResolver(),
    new Resolvers\NotEqResolver(),
    new Resolvers\EqResolver(),
    new Resolvers\LteResolver(),
    new Resolvers\LtResolver(),
    new Resolvers\GteResolver(),
    new Resolvers\GtResolver(),
    new Resolvers\CtResolver(),
    new Resolvers\SwResolver(),
    new Resolvers\NotInResolver(),
    new Resolvers\InResolver(),
    new Resolvers\EwResolver(),
    new Resolvers\NotNullResolver(),
    new Resolvers\NullResolver(),
    new Resolvers\AndResolver(),
    new Resolvers\OrResolver(),
]);

try {
    $result = $parser->parse($_GET['q']);
} catch (\Exception $e) {
    http_response_code(400);
    echo json_encode((object)["message" => $e->getMessage()]);
    die();
}

echo json_encode((object)["query" => $result]);
