<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Document extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Explicitly defined because your table name is singular ('document').
     *
     * @var string
     */
    protected $table = 'document';

    /**
     * Indicates if the model should be plugged into standard Laravel timestamps.
     * Since you are using a custom 'uploaded_at' timestamp, we disable the defaults.
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
        'filename',
        'original_file_path',
        'status',
    ];

    public function scopeFilter($query, array $filters) {
    
        if($filters['query'] ?? false) {
            $query->where('filename', 'like', '%' . request('query') . '%');
        }
    }



    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    /**
     * Boot function to automatically handle the 'uploaded_at' field on creation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uploaded_at)) {
                $model->uploaded_at = $model->freshTimestamp();
            }
        });
    }

    
    /**
     * Get the user that owns the document.
     *
     * @return BelongsTo
     */
    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }

    /**
     * Get the MCQs associated with this document.
     *
     * @return HasMany
     */
    // public function mcqs(): HasMany
    // {
    //     // Points to the Mcq model using 'doc_id' as the foreign key
    //     return $this->hasMany(Mcq::class, 'doc_id');
    // }
}