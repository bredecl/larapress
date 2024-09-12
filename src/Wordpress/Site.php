<?php
namespace Larapress\Wordpress;

/**
 * Class Site
 *
 * @package Larapress\Wordpress\Wordpress
 * @author Brede Basualdo Serraino <hola@brede.cl>
 */
class Site extends WordPress
{
    public function isMultisite()
    {
        return is_multisite();
    }
    //based on the work of https://medium.com/@ahnabshahin/wordpress-multisite-creating-sites-programmatically-and-handling-auto-increment-ids-e08498ecb82d
    public function createSite($name, $slug, $userID)
    {
        $domain = get_network()->domain;
        
        $path = get_network()->path . $slug . '/';
        if (domain_exists($domain, $path)) {
            return new WP_Error('blog_taken', __('Sorry, that site already exists!'));
        }
        $siteID = wp_insert_site(array(
            'domain' => $domain,
            'path' => $path,
            'title' => $name,
            'user_id' => $userID, // Replace with the desired site administrator's user ID
        ));

        if (!is_wp_error($siteID)) {
            return $siteID;
        }

    }
    public function getBlogInfo($siteID, $field = "")
    {
        return get_bloginfo($siteID, $field);
    }
    public function getBlogDetails($siteID)
    {
        return get_blog_details($siteID);
    }
    public function getSites()
    {
        return get_sites();
    }

}
