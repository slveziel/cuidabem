<?php

declare(strict_types=1);

namespace App\Application\Actions\Pacientes;

use App\Application\Actions\Action;
use App\Domain\Models\Paciente;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class SaveAction extends Action
{
    protected function action(): Response
    {
        $data = $this->getFormData();

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

            return $this->respondWithData([
                'success' => true,
                'message' => 'Paciente cadastrado com sucesso',
                'data' => $paciente
            ], 201);

        } catch (NestedValidationException $e) {
            return $this->respondWithData([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->getMessages()
            ], 400);
        } catch (\Exception $e) {
            return $this->respondWithData([
                'success' => false,
                'message' => 'Erro ao cadastrar paciente',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 