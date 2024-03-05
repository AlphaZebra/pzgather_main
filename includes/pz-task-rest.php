<?php

 /**
   * REST API for retrieving task list... 
   */

   add_action('rest_api_init', 'set_up_task_rest_route');
   function set_up_task_rest_route() {
     register_rest_route('pz/v1', 'task', array(
       'methods' => WP_REST_SERVER::READABLE,
       'callback' => 'do_task'
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
   
 
   function do_task($stuff) {
     global $wpdb;
     $limit = 120;
     $offset = 0;
   
     if( isset($_GET['task'])) {
       $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_task WHERE id = {$_GET['task']} ", ARRAY_A );
       return $results;
     }
     if( isset($_GET['tail'])) {
       $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_task {$_GET['tail']} ", ARRAY_A );
       return $results;
     }
     $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_task ", ARRAY_A );
     if( !isset($results[0])) {
       $offset=0;
       //$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_person LIMIT $limit OFFSET $offset ", ARRAY_A );
     };
   
     return $results;
   }