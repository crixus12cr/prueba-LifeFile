<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class Controller
{
    //
    function FunctionName() : Returntype {
        return $this->response()->json([
            'message' => 'Hello, World!',
        ],JsonResponse::HTTP_OK);
    }
}
