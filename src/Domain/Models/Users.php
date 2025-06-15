<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model {
    protected $table = 'users';
    protected $fillable = ['username', 'password', 'email', 'status', 'user_kind_id', 'entity_id', 'schema_id'];
}
