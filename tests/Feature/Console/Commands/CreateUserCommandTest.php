<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('creates a new user', function () {
    $this->artisan('users:create')
        ->expectsQuestion('Name of the new user', 'Test User')
        ->expectsQuestion('Email of the new user', 'test@example.com')
        ->expectsQuestion('Password of the new user', 'password')
        ->expectsOutput('User test@example.com created successfully')
        ->assertExitCode(0);

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::check('password', User::first()->password),
    ]);
});