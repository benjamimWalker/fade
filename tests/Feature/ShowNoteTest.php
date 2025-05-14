<?php

use App\Models\Note;
use Illuminate\Support\Facades\Crypt;

it('shows a valid note and decrypts the body', function () {
    $note = Note::create([
        'slug' => 'testslug123',
        'body' => Crypt::encryptString('Top secret content'),
    ]);

    $this->getJson(route('notes.show', ['note' => $note->slug], false))
        ->assertOk()
        ->assertJson([
            'body' => 'Top secret content',
        ]);
});

it('decrements view_limit if present', function () {
    $note = Note::create([
        'slug' => 'limitedviewslug',
        'body' => Crypt::encryptString('View limited note'),
        'view_limit' => 2,
    ]);

    $this->getJson(route('notes.show', ['note' => $note->slug], false))
        ->assertOk()
        ->assertJson(['body' => 'View limited note']);

    $this->assertDatabaseHas('notes', [
        'slug' => 'limitedviewslug',
        'view_limit' => 1,
    ]);
});

it('deletes the note if view_limit is 1', function () {
    $note = Note::create([
        'slug' => 'deleteonview',
        'body' => Crypt::encryptString('One time note'),
        'view_limit' => 1,
    ]);

    $this->getJson(route('notes.show', ['note' => $note->slug], false))
        ->assertOk()
        ->assertJson(['body' => 'One time note']);

    $this->assertDatabaseMissing('notes', [
        'slug' => 'deleteonview',
    ]);
});

it('returns not found if note has expired', function () {
    $note = Note::create([
        'slug' => 'expiredslug',
        'body' => Crypt::encryptString('Expired note'),
        'expires_at' => now()->subMinute(),
    ]);

    $this->getJson(route('notes.show', ['note' => $note->slug], false))
        ->assertNotFound();
});
