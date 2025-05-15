<?php

use App\Jobs\DeleteExpiredNotes;
use App\Models\Note;
use Illuminate\Support\Facades\Log;

it('deletes expired notes in chunks and logs the count', function () {
    Log::spy();

    $expired = Note::factory()->count(3)->create([
        'expires_at' => now()->subDay(),
    ]);

    $nonExpired = Note::factory()->count(2)->create([
        'expires_at' => now()->addDay(),
    ]);

    $job = new DeleteExpiredNotes();
    $job->handle();

    foreach ($expired as $note) {
        expect(Note::find($note->id))->toBeNull();
    }

    foreach ($nonExpired as $note) {
        expect(Note::find($note->id))->not->toBeNull();
    }

    Log::shouldHaveReceived('info')
        ->once()
        ->with('Deleted 3 expired notes.');
});

it('does nothing and logs zero when no expired notes exist', function () {
    Log::spy();

    Note::factory()->count(5)->create([
        'expires_at' => now()->addDay(),
    ]);

    $job = new DeleteExpiredNotes();
    $job->handle();

    $this->assertDatabaseCount('notes', 5);

    Log::shouldHaveReceived('info')
        ->once()
        ->with('Deleted 0 expired notes.');
});

it('deletes in chunks efficiently', function () {
    Log::spy();

    $total = 2500;
    Note::factory()->count($total)->create([
        'expires_at' => now()->subDay(),
    ]);

    $job = new DeleteExpiredNotes();
    $job->handle();

    $this->assertDatabaseCount('notes', 0);

    Log::shouldHaveReceived('info')
        ->once()
        ->with("Deleted {$total} expired notes.");
});
