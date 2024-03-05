<?php

 /**
   * REST API for retrieving project list... 
   */

   add_action('rest_api_init', 'set_up_project_rest_route');
   function set_up_project_rest_route() {
     register_rest_route('pz/v1', 'project', array(
       'methods' => WP_REST_SERVER::READABLE,
       'callback' => 'do_project'
     ));
    //  register_rest_route('pz/v1', 'delete-configuration', array(
    //    'methods' => WP_REST_SERVER::READABLE,
    //    'callback' => 'do_delete_configuration'
    //  ));
    //  register_rest_route('pz/v1', 'putconfiguration', array(
    //    'methods' => 'POST',
    //    'callback' => 'do_putconfiguration'
    //  ));
   }
   
 
   function do_project($stuff) {
     global $wpdb;
     $limit = 120;
     $offset = 0;
   
     if( isset($_GET['project'])) {
       $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_project WHERE id = {$_GET['project']} ", ARRAY_A );
       return $results;
     }
     if( isset($_GET['tail'])) {
       $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_project {$_GET['tail']} ", ARRAY_A );
       return $results;
     }
     $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_project ", ARRAY_A );
     if( !isset($results[0])) {
       $offset=0;
       //$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_person LIMIT $limit OFFSET $offset ", ARRAY_A );
     };
   
     return $results;
   }