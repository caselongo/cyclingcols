<?php

Route::get('user/cols','Users\ColsController@index')->name('user.cols.index');
Route::post('ajax/col/{collID}','Users\ColsController@store')->name('user.cols.store');