<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shortlink extends Model
{
    use HasFactory;
    protected $fillable = ['original_url', 'short_url', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
