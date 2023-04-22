<?php

namespace App\Http\Controllers;

use App\Adapters\MailerLiteAdapter;
use App\Models\ApiKey;
use App\Services\MailerLite\MailerLiteService;
use App\Utilities\ApiUtility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    public function index(): JsonResponse
    {
        $allKeys = ApiKey::all()->toArray();

        return ApiUtility::success("All API Keys", $allKeys);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|unique:api_keys'
        ]);


        $adapter = new MailerLiteAdapter($validated['key']);

        if (!$adapter->checkValidKey()) {
            return ApiUtility::validation('Invalid API Key Provided', [
                'key' => 'Invalid api key provided'
            ]);
        }

        $newApiKey = new ApiKey();
        $newApiKey->key = $validated['key'];
        $newApiKey->save();

        return ApiUtility::success("Api key stored successfully", $newApiKey->toArray());
    }

}
