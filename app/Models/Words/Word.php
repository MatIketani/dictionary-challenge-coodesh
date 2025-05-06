<?php

namespace App\Models\Words;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Word.
 *
 * Represents a word in the database.
 *
 * @property string $word
 */
class Word extends Model
{
    protected $fillable = [
        'word',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
