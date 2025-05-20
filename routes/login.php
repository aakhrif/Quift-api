<?php 
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../includes/db.php';

return function (\Slim\App $app) {
    $app->post('/login', function (Request $request, Response $response) {
        $pdo = DB::conn();
        
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $response->getBody()->write(json_encode(['error' => 'Invalid credentials']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $token = JWT::encode(['sub' => $user['id']], 'secret', 'HS256');
        $response->getBody()->write(json_encode(['token' => $token]));

        return $response->withHeader('Content-Type', 'application/json');
    });
};
