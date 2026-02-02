<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    protected $fillable = [
        'poll_option_id',
        'vote_type',
        'voter_name',
    ];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function pollOption(): BelongsTo
    {
        return $this->belongsTo(PollOption::class);
    }
}
