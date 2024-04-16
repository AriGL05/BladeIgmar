<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api:jwt', ['except' => ['uploadPhoto2','See2','Find2']]);
    }
    public function uploadPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            Log::stack(['single','slack'])->error($validator->errors()->toJson());
            return response()->json(['error' => $validator->errors()], 400);
        }
        $path = Storage::disk('digitalocean')->putFile('ariadna', $request->file('image'), 'public');
        Log::stack(['single','slack'])->info('Photo with JWT!');
        return response()->json(['path' => $path]);
    }
    public function uploadPhoto2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            Log::stack(['single','slack'])->error($validator->errors()->toJson());
            return response()->json(['error' => $validator->errors()], 400);
        }
        $path = Storage::disk('digitalocean')->putFile('ariadna', $request->file('image'), 'public');
        Log::stack(['single','slack'])->info('Photo with Sanctum!');
        return response()->json(['path' => $path]);
    }
    public function See()
    {
        $files = Storage::disk('digitalocean')->allFiles('ariadna');

        $fileNames = collect($files)->map(function ($file) {
            return pathinfo($file, PATHINFO_BASENAME);
        });
        Log::stack(['single','slack'])->info('Sending all names with JWT!');
        return response()->json(['files' => $fileNames]);
    }
    public function See2()
    {
        $files = Storage::disk('digitalocean')->allFiles('ariadna');

        $fileNames = collect($files)->map(function ($file) {
            return pathinfo($file, PATHINFO_BASENAME);
        });
        Log::stack(['single','slack'])->info('Sending all names with Sanctum!');
        return response()->json(['files' => $fileNames]);
    }
    public function Find($fileName)
    {
        $filePath = 'ariadna/' . $fileName;
        if (Storage::disk('digitalocean')->exists($filePath)) {
            $fileContent = Storage::disk('digitalocean')->get($filePath);
            $mimeType = Storage::disk('digitalocean')->mimeType($filePath);
            $response = Response::make($fileContent, 200);
            $response->header('Content-Type', $mimeType);
            Log::stack(['single','slack'])->info('Showing image with JWT!');

            return $response;
        } else {
            Log::stack(['single','slack'])->error('File not Found!');
            return response()->json(['error' => 'File not found'], 400);
        }
    }
    public function Find2($fileName)
    {
        $filePath = 'ariadna/' . $fileName;
        if (Storage::disk('digitalocean')->exists($filePath)) {
            $fileContent = Storage::disk('digitalocean')->get($filePath);
            $mimeType = Storage::disk('digitalocean')->mimeType($filePath);
            $response = Response::make($fileContent, 200);
            $response->header('Content-Type', $mimeType);
            Log::stack(['single','slack'])->info('Showing image with Sanctum!');

            return $response;
        } else {
            Log::stack(['single','slack'])->error('File not Found!');
            return response()->json(['error' => 'File not found'], 400);
        }
    }
    
}
