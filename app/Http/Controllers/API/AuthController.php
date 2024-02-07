<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $recaptchaResponse = $request->recaptcha_response;
        $secretKey = env('RECAPTCHA_SECRET_KEY');

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $recaptchaResponse
        ]);

        $responseData = $response->json();

        // Check reCAPTCHA verification result
        if (!$responseData['success']) {
            // reCAPTCHA verification failed
            return response()->json(['error' => 'reCAPTCHA verification failed'], 400);
        }
        
        // Validation rules
        $request->validate([
            'username' => [Rule::unique('users')],
            'email' => [Rule::unique('users')],
        ]);

        $user = new User;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        try {
            $user->save();
        } catch (\Illuminate\Database\QueryException $exception) {
            $errorCode = $exception->errorInfo[1];

            // Handle unique constraint violation
            if ($errorCode == 1062) { // MySQL error code for unique constraint violation
                return response()->json([
                    'error' => 'The username or email is already taken.',
                    'success' => false
                ], 422);
            }
        }

        return response()->json([
            'msg' => "Account created successfully.",
            'success' => true
        ]);
    }

    /**
     * Logs the user in.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $recaptchaResponse = $request->recaptcha_response;
        $secretKey = env('RECAPTCHA_SECRET_KEY');

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $recaptchaResponse
        ]);

        $responseData = $response->json();

        // Check reCAPTCHA verification result
        if (!$responseData['success']) {
            // reCAPTCHA verification failed
            return response()->json(['error' => 'reCAPTCHA verification failed'], 400);
        }
        $user = User::where('username', $request->username)->first();
        if ($user) {
            if (password_verify($request->password, $user->password)) {
                $token = Str::random(30);

                return response()->json([
                    'token' => $token,
                    'user_id' => $user->id,
                    'msg' => "User logged in successfully",
                    'success' => true
                ]);
            }
            return response()->json([
                'msg' => "Password incorrect",
                'success' => false
            ]);
        }

        return response()->json([
            'msg' => "User not found",
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}