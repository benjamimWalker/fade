<?php

namespace App\Actions;

use App\Models\Note;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class CreateNote
{
    public function handle(array $data): Note
    {
        do {
            $slug = Str::random(10);
        } while (Note::whereSlug($slug)->exists());

        return Note::create([
            'slug' => $slug,
            'body' => Crypt::encryptString($data['body']),
            'view_limit' =>  $data['view_limit'] ?? null,
            'expires_at' => ($data['ttl'] ?? null) ? now()->addSeconds((int) $data['ttl']) : null,
        ]);
    }
}
