<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof
            \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                "errors" => [
                    json_decode(json_encode([
                        "code"    => "ERROR-404",
                        "title"   => "Not Found",
                        "message" => $exception->getMessage(),
                    ])),
                ],
            ], 404);
        }

        if ($exception instanceof
            \Illuminate\Contracts\Filesystem\FileNotFoundException) {
            return response()->json([
                "errors" => [
                    json_decode(json_encode([
                        "code"  => "ERROR-404",
                        "title" => "File not found",
                    ])),
                ],
            ], 404);
        }

        if ($exception->getCode() == 500) {
            return response()->json([
                "errors" => [
                    json_decode(json_encode([
                        "code"    => "ERROR-500",
                        "title"   => "Server error, information bellow",
                        "message" => $exception->getMessage(),
                    ])),
                ],
            ], 500);
        }

        if (
            $exception instanceof HttpException
        ) {
            return response()->json([
                "errors" => [
                    json_decode(json_encode([
                        "code"    => "ERROR-" . $exception->getStatusCode(),
                        "title"   => "Request error",
                        "message" => $exception->getMessage(),
                    ])),
                ],
            ], $exception->getStatusCode());
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                "errors" => [
                    json_decode(json_encode([
                        "code"    => "ERROR-401",
                        "title"   => "Request error",
                        "message" => $exception->getMessage(),
                    ])),
                ],
            ], 401);
        }

        return redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
