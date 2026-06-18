<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Document; // Make sure to import your Document model

class Result extends Model
{
    use HasFactory;

    // 1. Explicitly define the singular table name
    protected $table = 'result';

    // 2. Disable default updated_at column since your migration only has created_at
    const UPDATED_AT = null;

    // 3. Define fillable properties for mass assignment
    protected $fillable = [
        'doc_id',
        'score',
        'total_questions',
    ];

    /**
     * Get the document that owns the result.
     */
    // public function document()
    // {
    //     return $this->belongsTo(Document::class, 'doc_id');
    // }
}