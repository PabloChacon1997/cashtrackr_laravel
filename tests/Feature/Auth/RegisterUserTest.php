<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('show the registration screen', function() {
   $response = $this->get(route('register'));

   $response->assertOk();
   $response->assertStatus(200);
   $response->assertSee('Crear Cuenta');
});


// it('register new user as unverified and dispatches the registrated event', function() {
//     $response = $this->post(route('register.store'), [
//         'name' => 'Test',
//         'mail' => 'test1@google.com',
//         'password' => 'Test$$123',
//         'password_confirmation' => 'Test$$123',
//     ]);

//     $response->assertRedirect(route('verification.notice'));
// });

it('should validated required fields whan the request body is empty', function() {
    $response = $this->post(route('register.store'), []);

    $response->assertSessionHasErrors([
        'name',
        'email',
        'password'
    ]);
});

it('prevents duplicate email addressess', function() {
    User::factory()->create([
        'email' => 'andres@google.com'
    ]);
    $response = $this->post(route('register.store'), [
        'name' => 'Test',
        'mail' => 'andres@google.com',
        'password' => 'Test$$123',
        'password_confirmation' => 'Test$$123',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors([
        'email'
    ]);
});