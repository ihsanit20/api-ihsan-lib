<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function getS3Prefix($request)
    {
        $origin = $request->header('Origin');
        $referer = $request->header('Referer');
        $domain = $origin ?? $referer;

        $domain = rtrim(preg_replace('/^http(|s):\/\/(www\.|)|www\./', '', $domain), '/');

        return preg_replace('/[^a-zA-Z0-9]/', '-', $domain);
    }
}