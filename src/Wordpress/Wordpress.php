<?php
namespace Larapress\Wordpress;

use Illuminate\Support\Facades\Log;

/**
 * Class Wordpress
 *
 * @package Larapress\Wordpress\Wordpress
 * @author Brede Basualdo Serraino <hola@brede.cl>
 */
class Wordpress
{
    function __construct()
    {
        //include_once("wpload.php");
        Log::debug("init construct");

        if (!defined("WP_USE_THEMES")) {
            define('WP_USE_THEMES', false);
        }
        
        !isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] = '' : '';
        !isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] = 'GET' : '';
        
        !isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] = 'larapress' : '';
        
#        define( 'WP_SITEURL', config('larapress.domain'));
 #       define( 'WP_HOME', config('larapress.domain'));
  #      $current_site =1;
   #     $current_blog =1;
        require config('larapress.path') . '/wp-blog-header.php';
        dd("e");
        Log::debug("end construct");

    }
}
