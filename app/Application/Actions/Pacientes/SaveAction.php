<?php

declare(strict_types=1);

namespace App\Application\Actions\Pacientes;

use App\Domain\Models\Paciente;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class SaveAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        try {
            // Validação dos dados
            $validator = v::key('nome', v::stringType()->notEmpty()->length(3, 100))
                ->key('data_nascimento', v::date()->notEmpty())
                ->key('cpf', v::stringType()->notEmpty()->length(11, 14))
                ->key('email', v::email()->notEmpty());

            $validator->assert($data);

            // Criação do paciente
            $paciente = new Paciente();
            $paciente->nome = $data['nome'];
            $paciente->data_nascimento = $data['data_nascimento'];
            $paciente->cpf = $data['cpf'];
            $paciente->email = $data['email'];
            $paciente->save();

            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Paciente cadastrado com sucesso',
                'data' => $paciente
            ]));

            return $response->withHeader('Content-Type', 'application/json')
                          ->withStatus(201);

        } catch (NestedValidationException $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->getMessages()
            ]));

            return $response->withHeader('Content-Type', 'application/json')
                          ->withStatus(400);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Erro ao cadastrar paciente',
                'error' => $e->getMessage()
            ]));

            return $response->withHeader('Content-Type', 'application/json')
                          ->withStatus(500);
        }
    }
} 