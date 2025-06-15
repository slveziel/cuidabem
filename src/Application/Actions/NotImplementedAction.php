<?php

declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;

class NotImplementedAction extends Action
{
    protected function action(): Response
    {
        $data = ['msg' => 'Not implemented!'];

        return $this->respondWithData($data);
    }
}
