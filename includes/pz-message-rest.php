<?php

 /**
   * REST API for retrieving list of people who've sent contact messages along with those messages... 
   */

   add_action('rest_api_init', 'set_up_message_rest_route');
   function set_up_message_rest_route() {
     register_rest_route('pz/v1', 'message', array(
       'methods' => WP_REST_SERVER::READABLE,
       'callback' => 'do_message'
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
   
 
   function do_message($stuff) {
     global $wpdb;
     $limit = 120;
     $offset = 0;
   
     if( isset($_GET['message'])) {
       $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_interaction WHERE per_id = {$_GET['message']} ", ARRAY_A );
       return $results;
     }
     $results = $wpdb->get_results( "SELECT pz_person.id, pz_person.firstname, pz_person.lastname, pz_person.email, pz_interaction.inter_details FROM {$wpdb->prefix}pz_person AS pz_person INNER JOIN {$wpdb->prefix}pz_interaction AS pz_interaction ON pz_person.id = pz_interaction.per_id");
     return $results;
   }