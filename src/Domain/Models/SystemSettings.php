<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSettings extends Model {
    protected $table = 'system_settings';
    protected $fillable = ['version'];
}
