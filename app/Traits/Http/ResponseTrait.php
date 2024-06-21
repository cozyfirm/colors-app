<?php
namespace App\Traits\Http;

use App\Models\User;
use \Illuminate\Http\JsonResponse;

trait ResponseTrait{
    /**
     * @param $message
     * @param $url
     * @return JsonResponse
     *
     * Return custom made response for frontend; Fixed code
     */
    public function jsonSuccess($message, $url = null): JsonResponse {
        return response()->json([
            'code' => '0000',
            'message' => $message,
            'url' => $url
        ]);
    }
    public function jsonError($code, $message, $url = null): JsonResponse {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'url' => $url
        ]);
    }

    /**
     * @param $code
     * @param $message
     * @param array $data
     * @return JsonResponse
     *
     * Custom JSON response
     */
    public function apiResponse($code, $message, array $data = []): JsonResponse {
        return ($data === []) ? response()->json([
            'code' => $code,
            'message' => $message
        ]) : response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * @param $code
     * @param $message
     * @param array $data
     * @return bool|string
     *
     * Mixed response; Code in form of variable; Return type string
     */
    public function jsonResponse($code, $message, array $data = []): bool | string {
        return json_encode([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }
}
