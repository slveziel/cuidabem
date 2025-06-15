<?php

declare(strict_types=1);

namespace App\Application\Actions\Login;

use App\Application\Actions\Action;
use App\Domain\Models\Usuario;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Hash;

class LoginAction extends Action
{
    protected function action(): Response
    {
        $params = (array) $this->request->getParsedBody();

        $username = $params['username'] ?? '';
        $password = $params['password'] ?? '';

        if (empty($username) || empty($password)) {
            $this->response->withStatus(400)
                            ->withHeader('Content-Type', 'application/json');
            return $this->respondWithData(['error' => 'username e senha são obrigatórios.']);
        }

        $user = Usuario::where('email', $username)->first();
// dd($password);
        if (!$user || !password_verify($password, $user->senha)) {
            $this->response->withStatus(401)
                            ->withHeader('Content-Type', 'application/json');
            return $this->respondWithData(['error' => 'Credenciais inválidas.']);
        }

        $key = $this->settings->get('jwt')['key'];
        $payload = [
            'iss' => 'governanca-api',
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + (60 * 60),
            'username' => $user->username,
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');

        $this->response->getBody()->write(json_encode(['token' => $jwt]));
        return $this->response->withHeader('Content-Type', 'application/json');
    }
}
