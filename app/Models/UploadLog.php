<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UploadLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * (Optional, as Laravel automatically guesses 'upload_logs', but good for clarity)
     *
     * @var string
     */
    protected $table = 'upload_logs';

    /**
     * Indicates if the model should be plugged into standard Laravel timestamps.
     * Since you only have 'created_at' and no 'updated_at', we set this to false
     * and handle it manually or let the DB handle it.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'upload_date',
        'total_upload',
    ];

    /**
     * The attributes that should be cast.
     * This ensures the dates are returned as Carbon instances automatically.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'upload_date' => 'date',
        'created_at' => 'datetime',
    ];

    /**
     * Boot function to automatically handle the 'created_at' field on creation,
     * since we disabled standard Laravel timestamps.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->created_at)) {
                $model->created_at = $model->freshTimestamp();
            }
        });
    }

    /**
     * Get the user that owns the upload log.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}