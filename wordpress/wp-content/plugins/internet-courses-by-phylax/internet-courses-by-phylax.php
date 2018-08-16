<?php
/*
Plugin Name: Internet Courses by phylax.pl
Plugin URI: https://phylax.pl/
Description: Create your internet courses as posts and easy navigation
Version: 0.1.1
Author: Łukasz Nowicki
Author URI: https://lukasznowicki.info/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: internet-courses-by-phylax
Domain Path: /languages
Requires at least: 4.9
Tested up to: 4.9.8
Requires PHP: 7.2
Stable tag: 0.1

Internet Courses by phylax.pl is free software: you can redistribute it and/or
modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or any later
version.

Internet Courses by phylax.pl is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
details.

You should have received a copy of the GNU General Public License along with
Internet Courses by phylax.pl. If not, see https://wordpress.org/about/license/.

*/
namespace Phylax\IC;

// let's see if we are in the WordPress call
defined( 'ABSPATH' ) || exit('This file cannot be executed outside the WordPress environment.');

define( __NAMESPACE__ . '\DS', \DIRECTORY_SEPARATOR );
define( __NAMESPACE__ . '\PLUGIN_DIR', str_replace( [ '\\', '/' ], DS, plugin_dir_path( __FILE__ ) ) );

require_once PLUGIN_DIR . 'Phylax' . DS . 'IC' . DS . 'Autoloader.php';

$ic_autoloader = new Autoloader();
$ic_autoloader->register();
if ( TRUE === $ic_autoloader->error ) {
	// SPL error, we have to quit but we do not have to disrupt WordPress
	return;
}

$ic_autoloader->addNamespace(__NAMESPACE__, PLUGIN_DIR . 'Phylax' . DS . 'IC' );

new Plugin();