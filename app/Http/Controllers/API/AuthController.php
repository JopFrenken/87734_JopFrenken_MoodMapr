<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

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
        // Validation rules
        $request->validate([
            'username' => [Rule::unique('users')],
            'email' => [Rule::unique('users')],
        ]);

        $user = new User;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Crypt::encryptString($request->password);
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
        $user = User::where('username', $request->username)->first();
        if ($user) {
            if (password_verify($request->password, $user->password)) {
                $token = Str::random(60);

                return response()->json([
                    'token' => $token,
                    'msg' => "User logged in successfully",
                    'success' => true
                ]);
            }
        }
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