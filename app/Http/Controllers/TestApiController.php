<?php
namespace App\Http\Controllers;

class TestApiController extends Controller
{
    public function test()
    {
        return response()->json([
            'success' => true,
            'message' => 'Your first API route is working!',
        ]);
    }
}
