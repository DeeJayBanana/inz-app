<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

    //Pobranie powiązania użytkownika przez user_id
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeForUser($query, $user_id) {
        return $query->where('user_id', $user_id);
    }

    public function scopeForAdmin($query, $status) {
        return $query->where('status', $status);
    }
}
