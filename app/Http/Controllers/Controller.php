<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Stadion Tracker API",
 *     description="API-documentatie voor stadionbezoeken, wedstrijden, posts en gebruikersinteractie.",
 *     version="1.0.0",
 *     @OA\Contact(
 *         email="cedricvanhoorebeke@outlook.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://backend.ddev.site",
 *     description="DDEV omgeving"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
