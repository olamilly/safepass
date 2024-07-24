<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vault extends Model
{
    protected $fillable = [
        'vaultname',
        'user_id',
        'username',
        'password',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    use HasFactory;
}
