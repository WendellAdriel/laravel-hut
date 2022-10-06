<?php

namespace WendellAdriel\LaravelHut\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiHandler extends Handler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param Request   $request
     * @param Throwable $exception
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthenticationException) {
            return $this->error($exception, Response::HTTP_UNAUTHORIZED, 'Unauthenticated');
        }

        if ($exception instanceof ValidationException) {
            return $this->error($exception, Response::HTTP_UNPROCESSABLE_ENTITY, $exception->errors());
        }

        if (
            $exception instanceof ModelNotFoundException
            || $exception instanceof NotFoundHttpException
        ) {
            return $this->error($exception, Response::HTTP_NOT_FOUND, 'Resource not found');
        }

        if ($exception instanceof AppExceptionInterface) {
            return $this->error($exception);
        }

        return $this->error($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param Request                 $request
     * @param AuthenticationException $exception
     * @return JsonResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->error($exception, Response::HTTP_UNAUTHORIZED, 'Unauthenticated');
    }

    /**
     * Builds an error response
     *
     * @param Throwable         $exception
     * @param int|null          $code
     * @param string|array|null $message
     * @return JsonResponse
     */
    private function error(Throwable $exception, int $code = null, $message = null)
    {
        $response = ['message' => $message ?: $exception->getMessage()];
        if (config('app.debug')) {
            $response['debug'] = [
                'message' => $exception->getMessage(),
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
                'trace'   => $exception->getTraceAsString()
            ];
        }

        return response()->json($response, $code ?: $exception->getCode());
    }
}
