<?php

namespace App\Models\Words;

use App\Models\User;
use App\Models\Words\Word;
use Illuminate\Database\Eloquent\Model;

class FavoriteWord extends Model
{
    protected $fillable = [
        'user_id',
        'word_id',
    ];

    /**
     * Get the user that owns the favorite word.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, FavoriteWord>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the word that is favorited.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Word, FavoriteWord>
     */
    public function word()
    {
        return $this->belongsTo(Word::class);
    }
}
