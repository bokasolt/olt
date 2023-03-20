<?php

namespace App\Exceptions\GoogleSheet;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessException extends Exception
{
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'message' => $this->message
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
