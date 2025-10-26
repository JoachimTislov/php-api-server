<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TwoFactorController extends Controller
{
    protected TwoFactorService $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * Enable 2FA for the authenticated user.
     */
    public function enable(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($this->twoFactorService->isTwoFactorEnabled($user)) {
            return response()->json([
                'message' => '2FA is already enabled for your account.',
            ], 400);
        }

        // Send verification code to confirm enabling 2FA
        $this->twoFactorService->generateAndSendCode($user);

        return response()->json([
            'message' => 'A verification code has been sent to your email. Please verify to enable 2FA.',
            'requires_verification' => true,
        ]);
    }

    /**
     * Confirm enabling 2FA with verification code.
     */
    public function confirmEnable(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();

        if (!$this->twoFactorService->verifyCode($user, $request->code)) {
            throw ValidationException::withMessages([
                'code' => ['The provided code is invalid or has expired.'],
            ]);
        }

        $this->twoFactorService->enableTwoFactor($user);

        return response()->json([
            'message' => '2FA has been successfully enabled for your account.',
        ]);
    }

    /**
     * Disable 2FA for the authenticated user.
     */
    public function disable(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        if (!password_verify($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }

        if (!$this->twoFactorService->isTwoFactorEnabled($user)) {
            return response()->json([
                'message' => '2FA is not enabled for your account.',
            ], 400);
        }

        $this->twoFactorService->disableTwoFactor($user);

        return response()->json([
            'message' => '2FA has been successfully disabled for your account.',
        ]);
    }

    /**
     * Get 2FA status for the authenticated user.
     */
    public function status(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'two_factor_enabled' => $this->twoFactorService->isTwoFactorEnabled($user),
            'two_factor_confirmed_at' => $user->two_factor_confirmed_at,
        ]);
    }

    /**
     * Verify a 2FA code during login.
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $user = \App\Models\User::where
