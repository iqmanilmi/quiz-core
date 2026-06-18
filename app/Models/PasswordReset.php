<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordReset extends Model
{
    use HasFactory;

    // 1. Explicitly define the table name
    protected $table = 'password_resets';

    // 2. Define the fillable fields for mass assignment
    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    // 3. Disable Laravel's default created_at and updated_at timestamps
    public $timestamps = false;

    // 4. Cast expires_at to a Carbon instance automatically
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the password reset token.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}