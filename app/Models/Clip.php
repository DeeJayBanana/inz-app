<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Clip extends Model
{
    protected $table = 'clips';
    protected $fillable = [
        'uuid',
        'filename',
        'path',
        'label',
        'start_time',
    ];

    public function videoUpload(): BelongsTo
    {
        return $this->belongsTo(Upload::class, 'uuid', 'uuid');
    }

}
