<?php

declare(strict_types=1);

namespace App\Application\Actions\TransacoesFinanceiras;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class GetAction extends Action
{
    protected function action(): Response
    {
        $data = ['msg' => 'Not implemented!'];
        return $this->respondWithData($data);
    }
} 