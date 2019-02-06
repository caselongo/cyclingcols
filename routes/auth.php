<?php

Route::middleware(['ajax'])->group(function () {
	Route::get('user/col','User\ColsController@store')->name('user.cols.store');
});

Route::get('user/cols','User\ColsController@index')->name('user.cols.index');