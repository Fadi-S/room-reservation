<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedEmail extends Model
{
    protected $fillable = [
        'email',
        'reason',
        'bounce_type',
        'bounce_subtype',
        'raw_payload',
    ];

    protected $casts = [
        'raw_payload' => 'array',
    ];

    public static function isBlocked(string $email): bool
    {
        return static::where('email', strtolower(trim($email)))->exists();
    }
}
