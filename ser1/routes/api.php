<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api'], function () {
Route::post('call3rd', function (Request $request) {

    $client = new Client();
try{
        $res = $client->request('post', 'http://localhost:8001/api/getwidjet', [
        'form_params' => [
            'data' => $request->input,
            'form' => route('form')
        ]
    ]);
} catch (Exception $exception) {
    return response( )->json(['template'=>['main'=>'<h1 style="color:white"> not connected to 3rd app  or not running plz run</h1>']]);
}



        if( $res->getStatusCode()=='200')
        {
            $data =json_decode( $res->getBody()->getContents() );
            if($data && isset($data->template))
            {
                // dd($data,$request->all());
                return response( )->json(['template'=>$data->template,$request->all()]);
            }else{
                return response( )->json(['template'=>['main'=>'<h1 style="color:white"> not connected to 3rd app </h1>']]);
            }
        }

        return response( )->json(['template'=>['main'=>'<h1 style="color:white"> not connected to 3rd app </h1>']]);

});
Route::post('/form', function (Request $request) {
    return view('mysite1')->with('success', 'Profile updated!');;
})->name('form');

});
