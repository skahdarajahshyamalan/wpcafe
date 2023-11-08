<?php

namespace WpCafe_Pro\Traits;

defined("ABSPATH") || exit;

/**
 * Instance of class
 */
trait Singleton
{

    private static $instance;

    /**
     * Singleton trait
     */
    public static function instance(){
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}
