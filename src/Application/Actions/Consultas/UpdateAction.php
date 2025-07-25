<?php

declare(strict_types=1);

namespace App\Application\Actions\Consultas;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateAction extends Action
{
    protected function action(): Response
    {
        $data = ['msg' => 'Not implemented!'];
        return $this->respondWithData($data);
    }
} 