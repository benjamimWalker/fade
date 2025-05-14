<?php

const endpoint = 'api/notes';

it('creates a note with valid body and ttl', function () {
    $payload = [
        'body' => 'This is a secure note.',
        'ttl' => 3600,
        'view_limit' => 2,
    ];

    $this->postJson(endpoint, $payload)
        ->assertCreated()
        ->assertJsonStructure(['access_url']);

    $this->assertDatabaseHas('notes', [
        'view_limit' => 2,
    ]);
});

it('creates a note without ttl or view limit', function () {
    $payload = [
        'body' => 'This is a minimal note.',
    ];

    $response = $this->postJson(endpoint, $payload);

    $response->assertCreated();
    $this->assertDatabaseHas('notes', [
        'view_limit' => null,
    ]);
});

it('fails if body is missing', function () {
    $payload = [
        'ttl' => 600,
    ];

    $this->postJson(endpoint, $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['body']);
});

it('fails if ttl is below minimum', function () {
    $payload = [
        'body' => 'Short-lived note',
        'ttl' => 10,
    ];

    $this->postJson(endpoint, $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['ttl']);
});

it('fails if ttl exceeds maximum', function () {
    $payload = [
        'body' => 'Long-lived note',
        'ttl' => 9999999,
    ];

    $this->postJson(endpoint, $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['ttl']);
});

it('fails if view_limit is below 1', function () {
    $payload = [
        'body' => 'Invalid view limit',
        'view_limit' => 0,
    ];

    $this->postJson(endpoint, $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['view_limit']);
});

it('fails if body is not a string', function () {
    $payload = [
        'body' => ['invalid'],
    ];

    $this->postJson(endpoint, $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['body']);
});

it('fails if ttl is not an integer', function () {
    $payload = [
        'body' => 'Bad TTL type',
        'ttl' => '1 hour',
    ];

    $this->postJson(endpoint, $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['ttl']);
});
