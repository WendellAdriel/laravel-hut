<?php

namespace WendellAdriel\LaravelHut\Http;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

abstract class ApiController extends Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Builds and sends a simple success API response
     *
     * @param int $code
     * @return JsonResponse
     */
    protected function apiSimpleSuccessResponse(int $code = Response::HTTP_CREATED): JsonResponse
    {
        return response()->json(['success' => true], $code);
    }

    /**
     * Builds and sends a success API response
     *
     * @param mixed $data
     * @param int   $code
     * @param bool  $forceUTF8Convert
     * @return JsonResponse
     */
    protected function apiSuccessResponse(
        $data,
        int $code = Response::HTTP_OK,
        bool $forceUTF8Convert = false
    ): JsonResponse {
        $formattedData = $forceUTF8Convert ? $this->convertToUTF8Recursively($data) : $data;
        return response()->json($formattedData, $code);
    }

    /**
     * Builds and sends an error API response
     *
     * @param string         $message
     * @param Throwable|null $exception
     * @param int            $code
     * @return JsonResponse
     */
    protected function apiErrorResponse(
        string $message,
        Throwable $exception = null,
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse {
        $response = ['message' => $message];

        if (!empty($exception) && config('app.debug')) {
            $response['debug'] = [
                'message' => $exception->getMessage(),
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
                'trace'   => $exception->getTraceAsString()
            ];
        }

        return response()->json($response, $code);
    }

    /**
     * @param mixed $data
     * @return mixed
     */
    private function convertToUTF8Recursively($data)
    {
        if (is_string($data)) {
            return utf8_encode($data);
        } elseif (is_array($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $formattedKey          = is_string($key) ? utf8_encode($key) : $key;
                $result[$formattedKey] = $this->convertToUTF8Recursively($value);
            }
            return $result;
        } elseif (is_object($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $formattedKey            = is_string($key) ? utf8_encode($key) : $key;
                $result->{$formattedKey} = $this->convertToUTF8Recursively($value);
            }
            return $result;
        } else {
            return $data;
        }
    }
}
