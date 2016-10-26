<?php
/**
 * The main plugin class that handles all other plugin parts.
 * 
 * Defines the plugin name, version, and hooks for enqueing the stylesheet and JavaScript.
 * 
 * @package     JKL_School_Admin
 * @subpackage  JKL_School_Admin/inc
 * @author      Aaron Snowberger <jekkilekki@gmail.com>
 */

/* Prevent direct access */
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'JKL_School_Admin' ) ) {
    
    class JKL_School_Admin {
        
        /**
         * The ID of this plugin.
         * 
         * @since   0.0.1
         * @access  private
         * @var     string  $name       The ID of this plugin.
         */
        private $name;
        
        /**
         * Current version of the plugin.
         * 
         * @since   0.0.1
         * @access  private
         * @var     string  $version    The current version of this plugin.
         */
        private $version;
        
        /**
         * New WP User Roles
         * 
         * @since   0.0.1
         * @access  private
         * @var     WP_Role  $admin     The role for School Administrators.
         * @var     WP_Role  $teacher   The role for School Teachers.
         * @var     WP_Role  $student   The role for School Students.
         * @var     WP_Role  $parent    The role for School Parents.
         */
        private $admin;
        private $teacher;
        private $student;
        private $parent;
        
        
        /**
         * CONSTRUCTOR !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
         * Initializes the JKL_School_Admin object and sets its properties
         * 
         * @since   0.0.1
         * @var     string  $name       The name of this plugin.
         * @var     string  $version    The version of this plugin.
         */
        public function __construct( $name, $version ) {
            
            // Set the name and version number
            $this->name     = $name;
            $this->version  = $version;
            
            // Load the plugin and supplementary files
            $this->load();
            
            // Create and store our new User roles
            $this->admin    = $this->confirm_user_role( 'school_admin' );
            $this->teacher  = $this->confirm_user_role( 'school_teacher' );
            $this->student  = $this->confirm_user_role( 'school_student' );
            $this->parent   = $this->confirm_user_role( 'school_parent' );
            
        }
        
        /**
         * Loads translation directory
         * Adds the call to enqueue styles and scripts
         * 
         * @since   0.0.1
         */
        protected function load() {
            
            add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'jkl_sa_scripts_styles' ) );
            
        }
        
        /**
         * Loads translation directory
         * 
         * @since   0.0.1
         */
        public function load_text_domain() {
            
            load_plugin_textdomain( 'jkl-school-admin', false, basename( dirname( __FILE__) ) . '/languages' );
            
        }
        
        /**
         * Enqueues Styles and Scripts
         * 
         * @since   0.0.1
         */
        public function jkl_sa_scripts_styles() {
                
            // wp_enqueue_style( 'jkl-sa-styles', plugins_url( '../css/style.css', __FILE__ ) );
            // wp_enqueue_script( 'jkl-sa-scripts', plugins_url( '../js/functions.js', __FILE__ ), array( 'jquery' ), '20160327', true );
        
        }
        
        /**
         * Confirms (or rewrites) new user roles for schools
         * 
         * @since   0.0.1
         * 
         * @param   $string String  The name of the User role
         * @return  $role   WP_Role The User role object for the given string value
         */        
        public function confirm_user_role( $string ) {
            
            $role = get_role( $string );
            
            // Check if this role already exists, if so, Delete it and re-add it
            if( $role !== null ) {
                remove_role( $string );
            } else {
                // Add the new user role
                $role = add_role( 
                        $string,
                        __( ucwords( str_replace( '_', ' ', $string ) ), 'jkl-school-admin' ),
                        $this->set_user_capabilities( $string )
                );
            }

            // Send the role to back to be stored in our JKL School Admin Object
            return $role;
            
        } // END confirm_user_role()
        
        /**
         * Returns an array of arguments containing various User roles based on the string passed in
         * 
         * @since   0.0.1
         * 
         * @param   $role   String  The name of the User role
         * @return  $args   array   The array of capabilities for a certain User role
         */
        public function set_user_capabilities( $role ) {
            
            // All new users have the ability to Read posts
            $args = array( 'read'                   => true );
            
            // PARENT capabilities (like Subscribers)
            if( $role === 'school_parent' ) {
                // Add nothing new: Return original array -> they only have read capabilities
            }
            
            // STUDENT capabilities (like Contributors)
            if( $role === 'school_student' || $role === 'school_teacher' || $role === 'school_admin' ) {
                // Everyone except Parents can write and delete Posts
                $args[ 'edit_posts' ]               = true;
                $args[ 'delete_posts' ]             = true;
                $args[ 'publish_posts' ]            = true;
            }
            
            // TEACHER capabilities (like Authors)
            if( $role === 'school_teacher' || $role === 'school_admin') {
                // Teachers and Admin can ALSO 
                $args[ 'edit_published_posts' ]     = true;
                $args[ 'delete_published_posts' ]   = true;
                $args[ 'edit_others_posts' ]        = true; // This is to allow Teachers or Admin to edit Students' Posts if necessary
                $args[ 'delete_others_posts' ]      = true; // This is to allow Teachers or Admin to remove Students' Posts if necessary
                $args[ 'upload_files' ]             = true;
                $args[ 'moderate_comments' ]        = true;
                $args[ 'manage_categories' ]        = true;
                $args[ 'manage_links' ]             = true;
                $args[ 'read_private_posts' ]       = true;
                $args[ 'read_private_pages' ]       = true;
            }
            
            // ADMIN capabilities (like Editors)
            if( $role === 'school_admin' ) {
                $args[ 'publish_pages' ]            = true;
                $args[ 'edit_pages' ]               = true;
                $args[ 'edit_others_pages' ]        = true;
                $args[ 'edit_private_pages' ]       = true;
                $args[ 'edit_private_posts' ]       = true;
                $args[ 'delete_pages' ]             = true;
                $args[ 'delete_others_pages' ]      = true;
                $args[ 'delete_private_pages' ]     = true;
                $args[ 'delete_private_posts' ]     = true;
                $args[ 'delete_published_pages' ]   = true;
                $args[ 'unfiltered_html' ]          = true;
            }
            
            return $args;
            
        } // END set_user_capabilities()
        
    } // END class JKL_School_Admin
    
} // END ! class_exists()