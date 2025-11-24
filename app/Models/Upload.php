<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Upload extends Model
{
    protected $table = 'video_uploads';
    protected $fillable = [
        'user_id',
        'uuid',
        'original_name',
        'extension',
        'size',
        'status'
    ];

    public function scopeForUser($query, $user_id) {
        return $query->where('user_id', $user_id);
    }
}
