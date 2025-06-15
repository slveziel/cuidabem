<?php

declare(strict_types=1);

namespace App\Application\Actions\Pacientes;

use App\Application\Actions\Action;
use App\Domain\Models\Paciente;
use Psr\Http\Message\ResponseInterface as Response;

class ListarAction extends Action
{
    protected function action(): Response
    {
        try {
            $pacientes = Paciente::all();

            return $this->respondWithData([
                'success' => true,
                'data' => $pacientes
            ]);

        } catch (\Exception $e) {
            return $this->respondWithData([
                'success' => false,
                'message' => 'Erro ao listar pacientes',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 