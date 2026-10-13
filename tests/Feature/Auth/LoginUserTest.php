<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


it('show the login screen', function() {
    $response = $this->get(route('login'));

    $response->assertOk();
});

it('logs in verified user successfully', function() {
    User::factory()->create([
        'email' => 'test@google.com',
        'password' => bcrypt('QIcHhGdf$$97'),
        'email_verified_at' => now(),
    ]);

    $response = $this->post(route('login.store'), [
        'email' => 'test@google.com',
        'password' => 'QIcHhGdf$$97',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
});

it('does not log in with invalid credentials', function() {
    User::factory()->create([
        'email' => 'test@google.com',
        'password' => bcrypt('QIcHhGdf$$97'),
    ]);

    $response = $this->from(route('login'))->post(route('login.store'), [
        'email' => 'test@google.com',
        'password' => 'QIcHhGdf$$97Incorrect',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('error', 'Credenciales incorrectas');
    $this->assertGuest();
});


it('prevents unverified user from accesing', function() {
    User::factory()->unverified()->create([
        'email' => 'test@google.com',
        'password' => bcrypt('QIcHhGdf$$97'),
    ]);

    $response = $this->post(route('login.store'), [
        'email' => 'test@google.com',
        'password' => 'QIcHhGdf$$97',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();

    $dashboardResponse = $this->get(route('dashboard'));
    $dashboardResponse->assertRedirect(route('verification.notice'));
});


it('does not allow access to dashboard if email is not verified', function() {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertRedirect(route('verification.notice'));
});


it('allow access to dashboard if email is verified', function() {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
});

it('fails login if user does not exist', function() {
    $response = $this->from(route('login'))
        ->post(route('login.store'), [
            'email' => 'domain@domain.com',
            'password' => 'QIcHhGdf$$76',
        ]);
    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors([
        'email' => 'No encontramos una cuenta con ese correo electrónico.'
    ]);

    $this->assertGuest();
});
