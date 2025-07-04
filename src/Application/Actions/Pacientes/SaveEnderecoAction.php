<?php

declare(strict_types=1);

namespace App\Application\Actions\Pacientes;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class SaveEnderecoAction extends Action
{
    protected function action(): Response
    {
        $data = ['msg' => 'Not implemented!'];
        return $this->respondWithData($data);
    }
} 