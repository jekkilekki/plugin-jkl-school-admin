<?php
/**
 * @since       0.0.1
 * @package     JKL_School_Admin
 * @author      Aaron Snowberger <jekkilekki@gmail.com>
 * 
 * @wordpress-plugin
 * Plugin Name: JKL School Admin
 * Plugin URI:  https://github.com/jekkilekki/plugin-jkl-timezones
 * Description: A school administration plugin that creates new User Roles for a high school.
 * Version:     0.0.1
 * Author:      Aaron Snowberger
 * Author URI:  http://www.aaronsnowberger.com
 * Text Domain: jkl-school-admin
 * Domain Path: /languages/
 * License:     GPL2
 * 
 * Requires at least: 3.5
 * Tested up to: 4.6
 */

/**
 * JKL School Admin allows you to creates new User Roles for a high school.
 * Copyright (C) 2016  AARON SNOWBERGER (email: JEKKILEKKI@GMAIL.COM)
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/*
 * Plugin Notes:
 * 
 * Similar simple selection like: https://wordpress.org/plugins/timezonecalculator/screenshots/
 */

/* Prevent direct access */
if ( ! defined( 'WPINC' ) ) die;

/*
 * The class that represents the MAIN JKL ADMIN settings page
 */

/*
 * The class that represents and defines the core plugin
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/class-jkl-school-admin.php';

/*
 * The function that creates a new JKL_School_Admin object and runs the program
 */
function run_school_admin() {
    // Instantiate the plugin class
    $JKL_School_Admin = new JKL_School_Admin( 'jkl-school-admin', '0.0.1' );
}

run_school_admin();
