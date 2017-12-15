<?php

namespace App\Exceptions;

use Exception;
use App\Exceptions\ActionRepeatedException;
use App\Exceptions\PersonalRuntimeException;
use App\Exceptions\InvalidPasswordException;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\InsufficientGoldException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\InsufficientLifesException;
use App\Exceptions\PersonalValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use App\Exceptions\InvalidVerificationCodeException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        switch($e) {
            case($e instanceof ResourceNotFoundException) :
                return $this->renderException($e);
            break;
            case($e instanceof PersonalValidationException) :
                return $this->renderException($e);
            break;
            case($e instanceof InvalidPasswordException) :
                return $this->renderException($e);
            break;
            case($e instanceof NotFoundHttpException) :
                return $this->renderException($e);
            break;
            case($e instanceof PersonalRuntimeException) :
                return $this->renderException($e);
            break;
            case($e instanceof InsufficientGoldException) :
                return $this->renderException($e);
            break;
            case($e instanceof InsufficientLifesException) :
                return $this->renderException($e);
            break;
            case($e instanceof ActionRepeatedException) :
                return $this->renderException($e);
            break;
            case($e instanceof InvalidVerificationCodeException) :
                return $this->renderException($e);
            break;
            case($e instanceof QueryException) :
                return $this->renderException($e);
            break;

            default :
            break;
        }
        return parent::render($request, $e);
    }

    protected function renderException($e) {
        switch($e) {
            case($e instanceof ResourceNotFoundException) :
                $error = [
                    "details" => $e->getMessage(),
                    "status_code" => 404,
                    "error_code" => "RESOURCE_NOT_FOUND"
                ];

                return response()->json($error, 404);
            break;
            case($e instanceof PersonalValidationException) :
                $error = [
                    "details" => $e->getMessage(),
                    "errors" => $e->errors(),
                    "status_code" => 422,
                    "error_code" => "VALIDATION_EXCEPTION"
                ];

                return response()->json($error, 422);
            break;
            case($e instanceof InvalidPasswordException) :
                $error = [
                    "details" => $e->getMessage(),
                    "status_code" => 401,
                    "error_code" => "INVALID_PASSWORD_EXCEPTION"
                ];

                return response()->json($error, 401);
            break;
            case($e instanceof InvalidVerificationCodeException) :
                $error = [
                    "details" => $e->getMessage(),
                    "status_code" => 401,
                    "error_code" => "INVALID_VERIFICATION_CODE_EXCEPTION"
                ];

                return response()->json($error, 401);
            break;
            case($e instanceof NotFoundHttpException) :
                $error = [
                    "details" => "Sorry, the requested route could not be found.",
                    "status_code" => 404,
                    "error_code" => "INVALID_ROUTE"
                ];

                return response()->json($error, 404);
            break;
            case($e instanceof PersonalRuntimeException) :
                $error = [
                    "details" => $e->getMessage(),
                    "status_code" => 500,
                    "error_code" => "RUNTIME_EXCEPTION"
                ];

                return response()->json($error, 500);
            break;
            case($e instanceof InsufficientGoldException) :
                $error = [
                    "details" => $e->getMessage(),
                    "status_code" => 403,
                    "error_code" => "INSUFFICIENT_GOLD_EXCEPTION"
                ];

                return response()->json($error, 403);
            break;
            case($e instanceof InsufficientLifesException) :
                $error = [
                    "details" => $e->getMessage(),
                    "status_code" => 403,
                    "error_code" => "INSUFFICIENT_LIFES_EXCEPTION"
                ];

                return response()->json($error, 403);
            break;
            case($e instanceof ActionRepeatedException) :
                $error = [
                    "details" => $e->getMessage(),
                    "status_code" => 403,
                    "error_code" => "ACTION_REPEATED_EXCEPTION"
                ];

                return response()->json($error, 403);
            break;
            case($e instanceof QueryException) :
                $error = [
                    "details" => __('common.errors.invalid_query'),
                    "status_code" => 500,
                    "error_code" => "QUERY_EXCEPTION"
                ];

                return response()->json($error, 500);
                break;
            default :
            break;
        }
    }
}
