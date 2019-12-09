<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        if ($exception
            instanceof
            \Illuminate\Database\Eloquent\ModelNotFoundException)
        {
            return response()->json([
                "errors" => [
                    json_decode(json_encode([
                        "code" => "ERROR-404",
                        "title" => "Not Found",
                        "message" => $exception->getMessage()
                    ]))
                ]
            ], 404);
        }

        if ($exception
            instanceof
            \Illuminate\Contracts\Filesystem\FileNotFoundException)
        {
            return response()->json([
                "errors" => [
                    json_decode(json_encode([
                        "code" => "ERROR-404",
                        "title" => "File not found"
                    ]))
                ]
            ], 404);
        }

        if ($exception->getCode() == 500) {
            return response()->json([
                "errors" => [
                    json_decode(json_encode([
                        "code" => "ERROR-500",
                        "title" => "Server error, information bellow",
                        "message" => $exception->getMessage()
                    ]))
                ]
            ]);
        }
        return response()->json([
            "errors" => [
                json_decode(json_encode([
                    "code" => "ERROR-500",
                    "title" => "Server error, information bellow",
                    "message" => $exception->getMessage()
                ]))
            ]
        ]);
    }
}
