<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;


class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;


    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\Kreait\Firebase::class, function(){

            $firebase = (new Factory)->withServiceAccount( '/var/www/firebase-private-key.json');

            return $firebase->createAuth();
        });
    }


    /**
     * @return array
     */
    public function provides():array
    {
        return [\Kreait\Firebase::class];
    }


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
