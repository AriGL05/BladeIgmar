<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use \stdClass;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api:jwt', ['except' => ['login','register','register2','login2','logout2']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = Auth::guard('api:jwt')->attempt($credentials)) {
            Log::stack(['single','slack'])->error('ERROR IN LOGIN');
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        Log::stack(['single','slack'])->info('Login successful!');
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        Log::stack(['single','slack'])->info('Checking user with JWT!');
        return response()->json(auth()->guard('api:jwt')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->guard('api:jwt')->logout();
        Log::stack(['single','slack'])->info('Someone logged out using JWT');
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        Log::stack(['single','slack'])->info('Refreshing JWT token!');
        return $this->respondWithToken(auth()->guard('api:jwt')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTFactory::getTTL() * 60
        ]);
    }
    public function register(Request $request)
    {
        Log::debug($request);
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email'=> 'required|string|max:255|unique:users',
            'password'=> 'required|string|min:8'
        ]);
        if($validator->fails()){
            Log::stack(['single','slack'])->error('ERROR IN REGISTER WITH JWT');
            return response()->json($validator->errors());
        }
        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);
        Log::stack(['single','slack'])->info('Register with JWT successful!');
        $token = JWTAuth::fromUser($user);
        return response()
            ->json(['data'=>$user,'Token'=>$token,'token_type'=>'Bearer',]);
    }


    /**Sanctum validation */
    public function register2(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email'=> 'required|string|max:255|unique:users',
            'password'=> 'required|string|min:8'
        ]);
        if($validator->fails()){
            Log::stack(['single','slack'])->error('ERROR IN REGISTER WITH SANCTUM');
            return response()->json($validator->errors());
        }
        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        Log::stack(['single','slack'])->info('Register with Sanctum successful!');
        return response()
            ->json(['data'=>$user,'access_token'=>$token,'token_type'=>'Bearer',]);
    }

    
    public function login2(Request $request){
        if(!Auth::attempt($request->only('email','password')))
        {
            Log::stack(['single','slack'])->error('ERROR IN LOGIN');
            return response()
                ->json(['message'=>'Unauthorized'],401);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $authtoken = $user->createToken('auth_token')->plainTextToken;
        Log::stack(['single','slack'])->info('Login with Sanctum successful!');
        return response()->json([
            'access_token' => $authtoken,
            'token_type'=>'Bearer',
            'user' =>$user,
        ],200);
    }
    public function logout2(){
        auth('sanctum')->user()->tokens()->delete();
        Log::stack(['single','slack'])->info('Someone logged out using Sanctum');
        return ['message' => 'You hace successfully logged out and the token was successfully deleted'];
    }
}
