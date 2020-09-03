<?php

use Milestone\Http\Middleware\Onboarding;
use Illuminate\Support\Facades\Route;

Route::namespace('Milestone\Http\Controllers')->group(function () {
    Route::prefix(config('milestone.path'))->middleware(config('milestone.middleware'))->group(function () {

        Route::get('onboarding', 'OnboardingController@index')->name('onboarding.index');
        Route::post('onboarding', 'OnboardingController@store')->name('onboarding.store');

        Route::middleware([Onboarding::class])->group(function () {
            Route::get('/', 'GoalsController@index')->name('milestone.home');
            Route::prefix('goals')->group(function () {
                Route::get('periods', 'GoalsController@index')->name('goals.periods');
                Route::get('periods/create', 'GoalsController@createPeriod')->name('goals.periods.create');
                Route::get('periods/{goalPeriod}', 'GoalsController@showPeriod')->name('goals.periods.show');
                Route::post('periods', 'GoalsController@storePeriod')->name('goals.periods.store');

                Route::get('sets/{goalSet?}', 'GoalsController@sets')->name('goalSets');
                Route::post('sets', 'GoalsController@updateSet')->name('goalSets.update');
                Route::get('import', 'GoalsController@import')->name('goals.import');
                Route::post('import', 'GoalsController@storeImport')->name('goals.storeImport');
            });
            
            Route::get('settings', 'SettingsController@index')->name('settings.index');
            Route::post('settings', 'SettingsController@update')->name('settings.update');
        });
    });
});


// Route::middleware(['auth'])->group(function () {
//     Route::get('onboarding', 'OnboardingController@index')->name('onboarding.index');
//     Route::post('onboarding', 'OnboardingController@store')->name('onboarding.store');
// });

// Route::middleware(['auth', 'onboarding'])->group(function () {
//     Route::prefix('goals')->group(function () {
//         Route::get('periods', 'GoalsController@index')->name('goals.periods');
//         Route::get('periods/create', 'GoalsController@createPeriod')->name('goals.periods.create');
//         Route::get('periods/{goalPeriod}', 'GoalsController@showPeriod')->name('goals.periods.show');
//         Route::post('periods', 'GoalsController@storePeriod')->name('goals.periods.store');

//         Route::get('sets/{goalSet?}', 'GoalsController@sets')->name('goalSets');
//         Route::post('sets', 'GoalsController@updateSet')->name('goalSets.update');
//         Route::get('import', 'GoalsController@import')->name('goals.import');
//         Route::post('import', 'GoalsController@storeImport')->name('goals.storeImport');
//     });

    
    
//     Route::get('settings', 'SettingsController@index')->name('settings.index');
//     Route::post('settings', 'SettingsController@update')->name('settings.update');
// });


