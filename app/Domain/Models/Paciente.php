<?php

declare(strict_types=1);

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';
    
    protected $fillable = [
        'nome',
        'data_nascimento',
        'cpf',
        'email'
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];
} 