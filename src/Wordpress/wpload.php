<?php
//dd(config('larapress'));
define('DB_USER', config('larapress.dbuser'));
define('DB_NAME', config('larapress.dbname'));
define('DB_PASSWORD', config('larapress.dbpass'));
define('DB_HOST', config('larapress.dbhost'));
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
define('CLIENT_PATH', config('larapress.path'));
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', WP_DEBUG);
if (!defined('ABSPATH')) {
    define('ABSPATH', CLIENT_PATH . '/');
}
define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
define('WPINC', 'wp-includes');
define('WP_LANG_DIR', 'wp-content/languages');

function is_admin(){
    return false;
}

function is_multisite() {
    return (defined( 'SUBDOMAIN_INSTALL' ) || defined( 'VHOST' ) || defined( 'SUNRISE' ));
}

function wp_installing( $is_installing = null ) {
    static $installing = null;

    // Support for the `WP_INSTALLING` constant, defined before WP is loaded.
    if ( is_null( $installing ) ) {
        $installing = defined( 'WP_INSTALLING' ) && WP_INSTALLING;
    }

    if ( ! is_null( $is_installing ) ) {
        $old_installing = $installing;
        $installing     = $is_installing;
        return (bool) $old_installing;
    }

    return (bool) $installing;
}

function wp_load_translations_early() {
    global $wp_locale;

    static $loaded = false;
    if ( $loaded ) {
        return;
    }
    $loaded = true;

    if ( function_exists( 'did_action' ) && did_action( 'init' ) ) {
        return;
    }

    // We need $wp_local_package
    require ABSPATH . WPINC . '/version.php';

    // Translation and localization
    require_once ABSPATH . WPINC . '/pomo/mo.php';
    require_once ABSPATH . WPINC . '/l10n.php';
    require_once ABSPATH . WPINC . '/class-wp-locale.php';
    require_once ABSPATH . WPINC . '/class-wp-locale-switcher.php';

    // General libraries
    require_once ABSPATH . WPINC . '/plugin.php';

    $locales = $locations = array();

    while ( true ) {
        if ( defined( 'WPLANG' ) ) {
            if ( '' == WPLANG ) {
                break;
            }
            $locales[] = WPLANG;
        }

        if ( isset( $wp_local_package ) ) {
            $locales[] = $wp_local_package;
        }

        if ( ! $locales ) {
            break;
        }

        if ( defined( 'WP_LANG_DIR' ) && @is_dir( WP_LANG_DIR ) ) {
            $locations[] = WP_LANG_DIR;
        }

        if ( defined( 'WP_CONTENT_DIR' ) && @is_dir( WP_CONTENT_DIR . '/languages' ) ) {
            $locations[] = WP_CONTENT_DIR . '/languages';
        }

        if ( @is_dir( ABSPATH . 'wp-content/languages' ) ) {
            $locations[] = ABSPATH . 'wp-content/languages';
        }

        if ( @is_dir( ABSPATH . WPINC . '/languages' ) ) {
            $locations[] = ABSPATH . WPINC . '/languages';
        }

        if ( ! $locations ) {
            break;
        }

        $locations = array_unique( $locations );

        foreach ( $locales as $locale ) {
            foreach ( $locations as $location ) {
                if ( file_exists( $location . '/' . $locale . '.mo' ) ) {
                    load_textdomain( 'default', $location . '/' . $locale . '.mo' );
                    if ( defined( 'WP_SETUP_CONFIG' ) && file_exists( $location . '/admin-' . $locale . '.mo' ) ) {
                        load_textdomain( 'default', $location . '/admin-' . $locale . '.mo' );
                    }
                    break 2;
                }
            }
        }

        break;
    }

    $wp_locale = new WP_Locale();
}

$blog_id = 1;
function get_current_blog_id() {
    return 1;
}

function wp_using_ext_object_cache( $using = null ) {
    global $_wp_using_ext_object_cache;
    $current_using = $_wp_using_ext_object_cache;
    if ( null !== $using ) {
        $_wp_using_ext_object_cache = $using;
    }
    return $current_using;
}

function wp_convert_hr_to_bytes( $value ) {
    $value = strtolower( trim( $value ) );
    $bytes = (int) $value;

    if ( false !== strpos( $value, 'g' ) ) {
        $bytes *= GB_IN_BYTES;
    } elseif ( false !== strpos( $value, 'm' ) ) {
        $bytes *= MB_IN_BYTES;
    } elseif ( false !== strpos( $value, 'k' ) ) {
        $bytes *= KB_IN_BYTES;
    }

    // Deal with large (float) values which run into the maximum integer size.
    return min( $bytes, PHP_INT_MAX );
}

function wp_is_ini_value_changeable( $setting ) {
    static $ini_all;

    if ( ! isset( $ini_all ) ) {
        $ini_all = false;
        // Sometimes `ini_get_all()` is disabled via the `disable_functions` option for "security purposes".
        if ( function_exists( 'ini_get_all' ) ) {
            $ini_all = ini_get_all();
        }
    }

    // Bit operator to workaround https://bugs.php.net/bug.php?id=44936 which changes access level to 63 in PHP 5.2.6 - 5.2.17.
    if ( isset( $ini_all[ $setting ]['access'] ) && ( INI_ALL === ( $ini_all[ $setting ]['access'] & 7 ) || INI_USER === ( $ini_all[ $setting ]['access'] & 7 ) ) ) {
        return true;
    }

    // If we were unable to retrieve the details, fail gracefully to assume it's changeable.
    if ( ! is_array( $ini_all ) ) {
        return true;
    }

    return false;
}

function is_wp_error( $thing ) {
    return ( $thing instanceof WP_Error );
}

require_once(CLIENT_PATH.'/wp-includes/class-wp-widget.php');
require_once(CLIENT_PATH.'/wp-includes/class-wp-rewrite.php');
require_once(CLIENT_PATH.'/wp-includes/class-wp-embed.php');
require_once(CLIENT_PATH.'/wp-includes/class-wp-post.php');
require_once(CLIENT_PATH.'/wp-includes/class-wp-error.php');
require_once(CLIENT_PATH.'/wp-includes/class-wp-meta-query.php');
require_once(CLIENT_PATH.'/wp-includes/class-wp-tax-query.php');
require_once(CLIENT_PATH.'/wp-includes/class-wp-query.php');
require_once(CLIENT_PATH.'/wp-includes/rest-api.php');
require_once(CLIENT_PATH.'/wp-includes/theme.php');
require_once(CLIENT_PATH.'/wp-includes/user.php');
require_once(CLIENT_PATH.'/wp-includes/taxonomy.php');
require_once(CLIENT_PATH.'/wp-includes/shortcodes.php');
require_once(CLIENT_PATH.'/wp-includes/plugin.php');
require_once(CLIENT_PATH.'/wp-includes/post.php');
require_once(CLIENT_PATH.'/wp-includes/l10n.php');
require_once(CLIENT_PATH.'/wp-includes/cache.php');
require_once(CLIENT_PATH.'/wp-includes/wp-db.php');
require_once(CLIENT_PATH.'/wp-includes/functions.php');
require_once(CLIENT_PATH.'/wp-includes/formatting.php');
require_once(CLIENT_PATH.'/wp-includes/default-constants.php');

$GLOBALS['wp_plugin_paths'] = array();
wp_cache_init();
$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );

//dd($wpdb);
$wp_locale = '';
wp_initial_constants();
wp_plugin_directory_constants();
$GLOBALS['wp_rewrite'] = new WP_Rewrite();
$GLOBALS['wp_embed'] = new WP_Embed();