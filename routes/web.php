<?php
use Carbon\Carbon;
/////////////////////////////////// START EXAMPLE /////////////////////////////////////////////

$router->get('key', function(){
    return str_random(32);
});

/////////////////////////////////// END EXAMPLE /////////////////////////////////////////////


/////////////////////////////////// START REAL LIFE /////////////////////////////////////////////


$router->group(['prefix' => 'api'], function() use ($router){

  $router->get('/fetch', 'QRCodeController@index');
  $router->post('/fetch/post', 'QRCodeController@store');
  $router->delete('/fetch/delete/{id}', 'QRCodeController@destroy');

  $router->post('/add/verifikasi', 'QRCodeController@store');

  // Login Apps Begin
  $router->post('login', 'API\APIController@loginApps');

  // Login Apps Begin
  $router->post('validasi', 'API\APIController@show');

  // Register Apps to be changed status karyawan
  $router->post('register', 'API\APIController@registerApps');

  // Register Apps new Pin Karyawan
  $router->post('register/pin', 'API\APIController@registerAppsPin');

  // We'll started to create presence action
  $router->post('masuk', 'API\APIController@masuk');

  // We'll started to create presence action
  $router->post('keluar', 'API\APIController@keluar');

  // We'll started to create presence action
  $router->post('mabal', 'API\APIController@mabal');

  // We'll started to create presence action
  $router->post('lembur', 'API\APIController@lembur');

  // We'll started to create random key action
  $router->post('generate', 'API\APIController@generate');

  // We'll started to create history for fetching data in android
  $router->get('history', 'API\APIController@history');


  // We'll started to create user's history for fetching data in android
  $router->get('myhistory/{id}/show', 'API\APIController@myHistory');

  // We'll started edit's action from presence
  /*
    In this part we're using put method for edit the data.
  */
  $router->put('absen/{id}/edit', 'API\APIController@editAbsen');


  // We'll started change status' action from presence
  /*
    In this part we're using put method for edit the data.
  */
  $router->put('verifikasi/{id}/edit', 'API\APIController@changeStatus');


  // We'll started change status' action from presence
  /*
    In this part we're using get method for edit the data.
  */
  $router->get('verifikasi/{id}/show', 'API\APIController@showVerifikasi');

  // We'll started delete's action from presence
  /*
    In this part we're using delete method for destroy the data.
  */
  $router->delete('absen/{id}/delete', 'API\APIController@deleteAbsen');


  /*
    Create New Karyawan Resource
  */
  $router->post('karyawan/post', 'API\APIController@postKaryawan'); // POST

  // We'll started validation's action for pulang
  /*
    Create New Pulang Resource
  */
  $router->get('pulang', 'API\APIController@pulang'); // POST

  // We'll started overtime's code after pulang
  /*
    Create Overtime Resource
  */
  $router->get('over', 'API\APIController@over');

  // We'll started validation's action for pulang

  /*
    In this part we're using put method for update & input an alasan
  */

  $router->put('telat/{id}/alasan', 'API\APIController@telat');

  $router->delete('lapur/{id}/delete', 'API\APIController@lapur');


  /*
    In this part we're using put method for edit the new pin.
  */
  // $router->put('karyawan/pin', 'API\APIController@forgotPin');

  /////////////////////////////////// END REAL LIFE /////////////////////////////////////////////

});
