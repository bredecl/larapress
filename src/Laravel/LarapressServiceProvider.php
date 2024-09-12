<?php
namespace Larapress\Laravel;
use Illuminate\Support\ServiceProvider;

/**
 * Class LarapressServiceProvider
 *
 * @package Larapress\Providers\Laravel
 * @author Brede Basualdo Serraino <hola@brede.cl>
 */
class LarapressServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->publishConfigFile();
    }

    /**
     * @return void
     */
    private function publishConfigFile()
    {
        $this->publishes([
            __DIR__ . '/config.php' => base_path('config/larapress.php'),
        ]);
    }



    /**
     * @return void
     */
    public function register()
    {
        
    }
}
