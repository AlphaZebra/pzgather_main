<?php

 /**
   * REST API for retrieving person list... 
   */

   add_action('rest_api_init', 'set_up_configuration_rest_route');
   function set_up_configuration_rest_route() {
     register_rest_route('pz/v1', 'configuration', array(
       'methods' => WP_REST_SERVER::READABLE,
       'callback' => 'do_configuration'
     ));
     register_rest_route('pz/v1', 'delete-configuration', array(
       'methods' => WP_REST_SERVER::READABLE,
       'callback' => 'do_delete_configuration'
     ));
     register_rest_route('pz/v1', 'putconfiguration', array(
       'methods' => 'POST',
       'callback' => 'do_putconfiguration'
     ));
   }
   
   function do_putconfiguration () {
     
   }
 
   function do_delete_configuration ($params) {
     global $wpdb;
 
     if( isset($_GET['per'])) {
       $results = $wpdb->delete( $wpdb->prefix . 'pz_configuration',  array( 'id' => $_GET['config'] ));
       return $results;
     }
   }
 
   function do_configuration($stuff) {
     global $wpdb;
     $limit = 120;
     $offset = 0;
   
     if( isset($_GET['config'])) {
       $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_configuration WHERE id = {$_GET['config']} ", ARRAY_A );
       return $results;
     }
     if( isset($_GET['tail'])) {
       $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_configuration {$_GET['tail']} ", ARRAY_A );
       return $results;
     }
     $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_configuration ", ARRAY_A );
     if( !isset($results[0])) {
       $offset=0;
       //$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_person LIMIT $limit OFFSET $offset ", ARRAY_A );
     };
   
     return $results;
   }