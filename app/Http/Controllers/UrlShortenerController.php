<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Exception;
use App\Models\Url;
use Cache;


class UrlShortenerController extends Controller
{
    //Store newly created URL short
    public function store (Request $request)
    {
        try{
            if(auth()->user()->id)
            {
                $longURL = $request->get('url');
                $newlyGeneratedURL = $request->get('shortUrl');

                if($longURL != '' || $newlyGeneratedURL != '')
                {
                    $urlFound = Url::where('old_url', $longURL)->get(['id','new_url'])->toArray();
                    if(!empty($urlFound))
                    {
                        return $urlFound[0]['new_url'];
                    }
                    else{
                        $urlTable = new Url;
                        $urlTable->old_url = $longURL;
                        $urlTable->new_url = $newlyGeneratedURL;
                        $urlTable->user_id = auth()->user()->id;
                        $urlTable->user_ip =  $_SERVER['REMOTE_ADDR'];
                        if($urlTable->save())
                        {
                            return $urlTable->new_url;
                        }
                        else{
                            return 'Error in saving URL';
                        }


                    }
                }
            }
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    //Handle redirect URL
    public function handleRedirectUrl(Request $request , $url)
    {
        
        try { 
            $uri = $_SERVER['REQUEST_URI'];
            if($uri == ''){
                return abort(404);
            }
            $url = Url::where('new_url','like','%'.$uri.'%')->get('old_url');

            if( $url == '' || count($url) == 0)
            {
                return abort(404);
            }
            else{
                return redirect($url[0]['old_url']);
            }
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
