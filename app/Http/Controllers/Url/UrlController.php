<?php

namespace App\Http\Controllers\Url;

use App\Http\Controllers\Controller;
use App\Http\Resources\UrlCollection;
use App\Http\Resources\UrlResource;
use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UrlController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        try {
            $urls = new UrlCollection(UrlResource::collection(Url::all()->where('user_id', Auth::user()->id))->keyBy->code);
            if ($urls->count() > 0) {
                return response()->json([
                    'status' => true,
                    'message' => 'Shrinkked URLs List',
                    'data' => $urls,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Shrinkked URLs not found!',
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Create URL
     */
    public function create(Request $request)
    {
        try {
            //Validated
            $validateUrl = Validator::make($request->all(),
                [
                    'url' => 'required|url',
                ]);

            if ($validateUrl->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUrl->errors(),
                ], 401);
            }

            $url = Url::where('url', $request->url)->first();

            if (! $url) {
                $newUrl = Url::create([
                    'url' => $request->url,
                    'code' => Str::random(8),
                    'user_id' => $request->user()->id,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Given Url shrinkked successfully',
                    'data' => new UrlResource($newUrl),
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Shrinkked URL already exists for given URL!',
                    'data' => new UrlResource($url),
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete URL
     */
    public function delete($code)
    {
        try {
            if (Url::where('user_id', Auth::user()->id)->where('code', $code)->delete()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Shrinkked URL with the code '.$code.' deleted',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Redirect away
     */
    public function redirect($code)
    {
        $url = Url::where('code', $code)->first();

        if ($url) {
            $url->increment('hits');

            return Redirect::away($url->url);
        } else {
            abort(404);
        }
    }
}
