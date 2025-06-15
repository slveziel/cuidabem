<?php

declare(strict_types=1);

namespace App\Application\Actions\Login;

use App\Application\Actions\Action;
use App\Domain\Models\Users;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Hash;

class TestTokenAction extends Action
{
    protected function action(): Response
    {
        $token = $this->request->getAttribute('token');

        $this->response->getBody()->write(json_encode(['token' => $token]));
        return $this->response->withHeader('Content-Type', 'application/json');
    }
}
