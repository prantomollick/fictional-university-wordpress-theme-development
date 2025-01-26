<?php

/*
    Plugin Name: Our First Unique Plugin
    Plugin URI: https://prantomollick.com
    Description: This is our first unique plugin.
    Version: 1.0
    Author: Pranto Mollick
    Author URI: https://prantomollick.com
    License: GPLv2 or later
    Text Domain: our-first-unique-plugin
    Our First Unique Plugin is free software: you can redistribute it and/or modify
*/

function addToEndOfPost($content) {

    if ( is_single() && is_main_query() ) {
        return $content . '<p>Thank you for reading our first unique plugin!</p>';
    }

}
add_filter('the_content', 'addToEndOfPost');