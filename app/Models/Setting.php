<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'app_name',
        'app_logo',
        'github_token',
        'seb_enabled',
        'seb_key',
    ];

    protected function casts(): array
    {
        return [
            'seb_enabled' => 'boolean',
        ];
    }
}
