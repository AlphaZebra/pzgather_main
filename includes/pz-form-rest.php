<?php

/**
   * REST API for form processing... 
   */

   add_action('rest_api_init', 'set_up_form_rest_route');
   function set_up_form_rest_route() {
     register_rest_route('pz/v1', 'form', array(
       'methods' => WP_REST_SERVER::READABLE,
       'callback' => 'do_form'
     ));
    //  register_rest_route('pz/v1', 'count', array(
    //    'methods' => WP_REST_SERVER::READABLE,
    //    'callback' => 'do_count'
    //  ));
   }
   
  function do_form($stuff) {
    global $wpdb;
    $limit = 120;
    $offset = 0;

     if( isset($_GET['app'])) {
        $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_task WHERE app_name = '{$_GET['app']}' ", ARRAY_A );
     } else  $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_task WHERE project_id = {$_GET['prj']} ", ARRAY_A );
     
     return $results;
   }
  
  function pz_form_save( $table, $fields ) {
    if( $table === "person" ) {
      // if -- possible instead this should be done automtically by form processing function -- 
      // if there are person fields, write them to person, etc... 
    }
  }