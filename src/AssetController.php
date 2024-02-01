<?php

namespace PhpTranslationManagerLaravel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class AssetController extends Controller
{

    public function js()
    {
        $appJs = file_get_contents(__DIR__ . '/../assets/js/app.js') . "\n";

        $response = new Response(
            $appJs,
            200,
            [
                'Content-Type' => 'text/javascript',
            ]
        );

        return $this->cacheResponse($response);
    }

    public function css()
    {
        $appCss = file_get_contents(__DIR__ . '/../assets/css/app.css') . "\n";

        $response = new Response(
            $appCss,
            200,
            [
                'Content-Type' => 'text/css',
            ]
        );

        return $this->cacheResponse($response);
    }

    /**
     * Cache the response 1 year (31536000 sec)
     */
    protected function cacheResponse(Response $response)
    {

        
        // $response->setSharedMaxAge(31536000);
        // $response->setMaxAge(31536000);
        // $response->setExpires(new \DateTime('+1 year'));

        return $response;
    }
}
