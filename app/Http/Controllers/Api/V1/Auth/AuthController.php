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

        return response()->json(['message' => 'Check your email to verify your account.'], 201);
    }

    public function login(Request $request)
    {
        $this->validateRequest($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);

        return $this->repository->login($request);
    }

    public function logout()
    {
        $this->authUser->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out.',
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $this->validateRequest($request, ['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['status' => __($status)])
            : response()->json(['email' => __($status)], 422);
    }

    public function resetPassword(Request $request)
    {
        $this->validateRequest($request, [
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
            return response()->json(['message' => 'Invalid verification link.'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 409);
        }

        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($user));

            return response()->json(['message' => 'Email successfully verified.'], 200);
        }

        return response()->json(['message' => 'Error verifying email.'], 500);
    }

    public function resendConfirmRegister(Request $request)
    {
        $this->validateRequest($request, ['email' => 'required|email']);

        $user = $this->repository->findUserForEmail($request);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'The email is already verified.'], 200);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link resent!'], 200);
    }

    public function profile()
    {
        return $this->repository->profile();
    }

    public function editProfile(UserFormRequest $request)
    {
        $this->repository->editProfile($request->all());

        return $this->responseMessage('Profile updated successfully');
    }

    public function upgradeToArtist()
    {
        $this->repository->upgradeToArtist();

        return $this->responseMessage('User upgraded to artist successfully');
    }

    public function follow($uuid)
    {
        return $this->repository->follow($uuid);
    }

    public function unfollow($uuid)
    {
        return $this->repository->unfollow($uuid);
    }

    public function following()
    {
        return $this->repository->followingList();
    }

    public function followers()
    {
        return $this->repository->followersList();
    }
}
