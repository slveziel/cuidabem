<?php

declare(strict_types=1);

namespace App\Application\Actions\Pacientes;

use App\Application\Actions\Action;
use App\Domain\Models\Paciente;
use Psr\Http\Message\ResponseInterface as Response;

class GetAction extends Action
{
    protected function action(): Response
    {
        try {
            $id = (int) $this->resolveArg('id');
            
            $paciente = Paciente::find($id);

            if (!$paciente) {
                return $this->respondWithData([
                    'success' => false,
                    'message' => 'Paciente não encontrado'
                ], 404);
            }

            return $this->respondWithData([
                'success' => true,
                'data' => $paciente
            ]);

        } catch (\Exception $e) {
            return $this->respondWithData([
                'success' => false,
                'message' => 'Erro ao buscar paciente',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 