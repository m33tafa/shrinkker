<?php

namespace App\Http\Controllers\Url;

use App\Models\Url;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL as FacadesURL;
use Illuminate\Support\Str;

class UrlController extends Controller
{

    /**
     * Index
     *
     */
    public function index()
    {
        try
        {
            $urls = Url::select('code','url','hits','created_at')->where('user_id', Auth::user()->id)->get();
            if($urls->count() > 0)
            {
                return response()->json([
                    'status' => true,
                    'message' => 'My Shrinkked URLs',
                    'urls' => $urls
                ], 200);
            }else
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Shrinkked URLs not found!'
                ], 200);
            }
        }catch (\Throwable $th)
        {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Create URL
     *
     */
    public function create(Request $request)
    {
        try
        {
            $url = Url::where('url', $request->url)->first();

            if(!$url)
            {
                $newUrl = Url::create([
                    'url' => $request->url,
                    'code' => Str::random(8),
                    'user_id' => $request->user()->id
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Given Url shrinkked successfully',
                    'given_url' => $request->url,
                    'shrinkked_url' => FacadesURL::to('/'. $newUrl->code)
                ], 200);
            }else
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Shrinkked URL already exists for given URL!',
                    'given_url' => $request->url,
                    'shrinkked_url' => FacadesURL::to('/'. $url->code),
                    'creation_time' => $url->created_at
                ], 200);
            }

        }catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Delete URL
     *
     */
    public function delete($code)
    {
        try
        {
            if(Url::where('user_id', Auth::user()->id)->where('code', $code)->delete())
            {
                return response()->json([
                    'status' => true,
                    'message' => 'Shrinkked URL deleted'
                ], 200);
            }else
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
        }catch (\Throwable $th)
        {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Redirect away
     *
     */
    public function redirect($code)
    {
        $url = Url::where('code', $code)->first();

        if($url)
        {
            $url->increment('hits');
            return Redirect::away($url->url);
        }else
        {
            abort(404);
        }
    }
}
