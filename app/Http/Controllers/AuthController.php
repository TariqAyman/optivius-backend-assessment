<?php
// Copyright
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Rules\LocalesInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Testing\Fluent\Concerns\Has;

class AuthController extends ApiController
{
    /**
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['login', 'register',]]);
    }

    /**
     * Attempt to register a new user to the API.
     *
     * @param Request $request
     * @return string
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        User::query()->truncate();

        $this->validator($request, [
            'name' => ['required', new LocalesInput()],
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:20|unique:users',
        ]);

        try {

            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'api_token' => base64_encode(random_bytes(40)),
                'password' => Hash::make($request->get('password')),
            ]);

            return $this->success([
                'user' => $user,
                'message' => 'CREATED'
            ]);

        } catch (\Exception $e) {
            return $this->badRequest('User Registration Failed!');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return string
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validator($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->input('email'))->with('articles:id,title,content,user_id,created_at')->first();

        if (Hash::check($request->get('password'), $user->password)) {
            return $this->success($user);
        } else {
            return  $this->unauthorized('Email or Password Wrong');
        }
    }
}
