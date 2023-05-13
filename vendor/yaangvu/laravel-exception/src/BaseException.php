<?php

namespace YaangVu\Exceptions;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class BaseException extends HttpResponseException
{
    protected bool $shouldCapture = false;

    public function __construct(string|array $message,
                                ?Exception   $e = null,
                                int          $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        if (is_null($e))
            $e = new Exception(is_string($message) ? $message : json_encode($message));

        if (!is_array($message))
            $message = ['message' => $message];

        Log::debug("BaseException debug: $e \n --------------> Messages: ", $message);

        if (env('APP_ENV') != 'production') {
            $message['error'] = $e->getMessage() ?? '';
            $message['code']  = $e->getCode() ?? '';
            $message['file']  = $e->getFile() ?? '';
            $message['line']  = $e->getLine() ?? '';
            $message['trace'] = $e->getTraceAsString() ?? '';
        }

        $response = response()->json($message)->setStatusCode($code);

        // Capture exceptions to Log system
        $this->capture($e);

        parent::__construct($response);
    }

    public function capture(Exception $exception)
    {
        if (!$this->shouldCapture)
            return;

        if (class_exists(\Sentry\SentrySdk::class))
            \Sentry\captureException($exception);
    }
}
