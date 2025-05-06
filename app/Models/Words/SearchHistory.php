<?php

namespace App\Models\Words;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Model SearchHistory
 *
 * @property int $id
 * @property int $user_id
 * @property string $word
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SearchHistory extends Model
{
    protected $fillable = [
        'user_id',
        'word',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
