<?php

 /**
   * REST API for retrieving person list... 
   */

   add_action('rest_api_init', 'set_up_person_rest_route');
   function set_up_person_rest_route() {
     register_rest_route('pz/v1', 'person', array(
       'methods' => WP_REST_SERVER::READABLE,
       'callback' => 'do_person'
     ));
     register_rest_route('pz/v1', 'delete-person', array(
       'methods' => WP_REST_SERVER::READABLE,
       'callback' => 'do_delete_person'
     ));
     register_rest_route('pz/v1', 'putperson', array(
       'methods' => 'POST',
       'callback' => 'do_putperson'
     ));
   }
   
   function do_putperson () {
     
   }
 
   function do_delete_person ($params) {
     global $wpdb;
 
     if( isset($_GET['per'])) {
       $results = $wpdb->delete( $wpdb->prefix . 'pz_person',  array( 'id' => $_GET['per'] ));
       return $results;
     }
   }
 
   function do_person($stuff) {
     global $wpdb;
     $limit = 120;
     $offset = 0;
   
     if( isset($_GET['per'])) {
       $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_person WHERE id = {$_GET['per']} ", ARRAY_A );
       return $results;
     }
     if( isset($_GET['tail'])) {
       $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_person {$_GET['tail']} ", ARRAY_A );
       return $results;
     }
     $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_person ", ARRAY_A );
     if( !isset($results[0])) {
       $offset=0;
       //$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_person LIMIT $limit OFFSET $offset ", ARRAY_A );
     };
   
     return $results;
   }