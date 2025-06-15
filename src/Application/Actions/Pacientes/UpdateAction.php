<?php

declare(strict_types=1);

namespace App\Application\Actions\Pacientes;

use App\Application\Actions\Action;
use App\Domain\Models\Paciente;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class UpdateAction extends Action
{
    protected function action(): Response
    {
        try {
            $id = (int) $this->resolveArg('id');
            $data = $this->getFormData();
            
            // Busca o paciente
            $paciente = Paciente::find($id);
            if (!$paciente) {
                return $this->respondWithData([
                    'success' => false,
                    'message' => 'Paciente não encontrado'
                ], 404);
            }

            // Regras de validação para cada campo
            $rules = [
                'nome' => v::stringType()->notEmpty()->length(3, 100),
                'data_nascimento' => v::date()->notEmpty(),
                'cpf' => v::stringType()->notEmpty()->length(11, 14),
                'email' => v::email()->notEmpty()
            ];

            // Valida apenas os campos enviados
            $validator = v::create();
            foreach ($data as $field => $value) {
                if (isset($rules[$field])) {
                    $validator->key($field, $rules[$field]);
                }
            }

            $validator->assert($data);

            // Atualiza apenas os campos enviados
            foreach ($data as $field => $value) {
                if (in_array($field, ['nome', 'data_nascimento', 'cpf', 'email'])) {
                    $paciente->$field = $value;
                }
            }
            
            $paciente->save();

            return $this->respondWithData([
                'success' => true,
                'message' => 'Paciente atualizado com sucesso',
                'data' => $paciente
            ]);

        } catch (NestedValidationException $e) {
            return $this->respondWithData([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->getMessages()
            ], 400);
        } catch (\Exception $e) {
            return $this->respondWithData([
                'success' => false,
                'message' => 'Erro ao atualizar paciente',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 