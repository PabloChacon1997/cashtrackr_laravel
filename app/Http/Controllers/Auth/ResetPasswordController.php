<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Testing\Fluent\Concerns\Has;

class ResetPasswordController extends Controller
{
    public function index(string $token, Request $request)
    {
        return view('auth.reset-password',[
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function store(ResetPasswordRequest $request)
    {
        $data = $request->validated();
        $status = Password::reset(
            $data,
            function($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('success', 'El Password se actualizó correctamente, ya puedes inciar sesión');
        }

        return back()->with([
            'token' => 'EL en lace no es válido o ha expirado. Intenta solicitando otro.'
        ]);
    }
}
