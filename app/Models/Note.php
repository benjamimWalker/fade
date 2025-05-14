<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'body',
        'view_limit',
        'expires_at'
    ];

    protected $hidden = [
        'slug'
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime'
        ];
    }

    public function accessUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => url(route('notes.show', ['note' => $this->slug], false)),
        );
    }

    public function hasExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
