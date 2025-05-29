<?php

namespace App\Http\Responses;

class ApiResponse
{
    /**
     * Return a success response.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data = null, string $message = 'Success', int $statusCode = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Return an error response.
     *
     * @param  string  $message
     * @param  int  $statusCode
     * @param  mixed  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error(string $message = 'Error', int $statusCode = 400, $errors = null)
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a validation error response.
     *
     * @param  mixed  $errors
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function validationError($errors, string $message = 'Validation failed')
    {
        return self::error($message, 422, $errors);
    }

    /**
     * Return an unauthorized error response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function unauthorized(string $message = 'Unauthorized')
    {
        return self::error($message, 401);
    }

    /**
     * Return a forbidden error response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function forbidden(string $message = 'Forbidden')
    {
        return self::error($message, 403);
    }

    /**
     * Return a not found error response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function notFound(string $message = 'Resource not found')
    {
        return self::error($message, 404);
    }

    /**
     * Return a server error response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function serverError(string $message = 'Server error')
    {
        return self::error($message, 500);
    }
}
