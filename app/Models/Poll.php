<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Poll extends Model
{
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'participant_token',
        'admin_token',
    ];

    protected static function booted(): void
    {
        static::creating(function (Poll $poll) {
            $poll->participant_token ??= Str::random(12);
            $poll->admin_token ??= Str::random(24);
        });
    }

    protected function participantUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): string => url("/p/{$this->participant_token}"),
        );
    }

    protected function adminUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): string => url("/a/{$this->admin_token}"),
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(PollOption::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
