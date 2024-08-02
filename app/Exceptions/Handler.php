<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e){
        if($e instanceof AuthenticationException){

            if($request->expectsJson()){
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication failed. Please log in to access this resource.',
                    'error_code' => 'AUTH_001'
                ], 401);
            }

            return response()->json([
                'success' => false,
                'message' => 'Authentication failed. Please log in to access this resource.',
                'error_code' => 'WEB_AUTH_001'
            ], 401);

        }

        return parent::render($request, $e);
    }


}
