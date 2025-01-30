<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Requests\Api\V1\User\UserFormRequest;
use App\Models\User;
use App\Repositories\Api\V1\Auth\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AuthController extends CrudController
{

    protected $model = User::class;

    public function __construct()
    {
        $this->repository = new AuthRepository();
    }

    public function register(UserFormRequest $request)
    {
        $data = $request->validated();
        $this->repository->register($data);

        return response()->json(['message' => 'Verifique seu e-mail para verificar sua conta.'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);

        return $this->repository->login($request);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['status' => __($status)])
            : response()->json(['email' => __($status)], 422);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();
            }
        );

        return $status == Password::PASSWORD_RESET
            ? response()->json(['status' => __($status)])
            : response()->json(['email' => __($status)], 422);
    }

    public function confirmRegister($id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Link de verificação inválido.'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'E-mail já verificado'], 409);
        }

        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($user));

            return response()->json(['message' => 'E-mail verificado com sucesso'], 200);
        }

        return response()->json(['message' => 'Erro ao verificar e-mail'], 500);
    }

    public function resendConfirmRegister(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = $this->repository->findUserForEmail($request);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'O e-mail já foi verificado.'], 200);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Link de verificação enviado novamente!'], 200);
    }
}
