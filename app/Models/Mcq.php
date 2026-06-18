<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mcq extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Explicitly defined because your table name is singular ('mcq').
     *
     * @var string
     */
    protected $table = 'mcq';

    /**
     * Indicates if the model should be plugged into standard Laravel timestamps.
     * Since your migration does not have $table->timestamps(), we disable them.
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
        'doc_id',
        'question',
        'option_1',
        'option_2',
        'option_3',
        'option_4',
        'correct_answer',
        'status',
    ];

    /**
     * Get the document that this MCQ belongs to.
     * * @return BelongsTo
     */
    // public function document(): BelongsTo
    // {
    //     // Assuming your Document model is App\Models\Document
    //     return $this->belongsTo(Document::class, 'doc_id');
    // }
}