<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

return function (App $app) {
    $schema = new Schema([
        'query' => new ObjectType([
            'name' => 'Query',
            'fields' => [
                'hello' => [
                    'type' => Type::string(),
                    'resolve' => fn() => 'Hello, world!'
                ]
            ]
        ])
    ]);

    $app->post('/graphql', function (
        ServerRequestInterface $request,
        ResponseInterface $response
    ) use ($schema) {
        $input = json_decode((string) $request->getBody(), true);
        $query = $input['query'] ?? '';
        $result = GraphQL::executeQuery($schema, $query);
        $output = $result->toArray();

        $response->getBody()->write(json_encode($output));
        return $response->withHeader('Content-Type', 'application/json');
    });
};
