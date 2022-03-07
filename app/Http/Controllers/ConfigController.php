<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ConfigController extends Controller
{

    /**
     * Fetch config file
     *
     * @return JsonResponse
     * @throws FileNotFoundException
     */
    public function index(): JsonResponse
    {
        $path = 'Wildbook/target/classes/bundles/locationID.json';

        $content = json_decode(Storage::disk('tomcat_config')->get($path), true);

        return response()->json([
            'data' => $content,
        ]);
    }
}
