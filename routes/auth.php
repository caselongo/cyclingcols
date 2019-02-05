<?php

Route::middleware(['ajax'])->group(function () {
	/* user */
	Route::get('user/cols','User\ColsController@index')->name('user.cols.index');
	Route::get('user/col','User\ColsController@store')->name('user.cols.store');
});