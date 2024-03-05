<?php

/**
   * REST API for retrieving interaction list... 
   */

   add_action('rest_api_init', 'set_up_interaction_rest_route');
   function set_up_interaction_rest_route() {
     register_rest_route('pz/v1', 'interaction', array(
       'methods' => WP_REST_SERVER::READABLE,
       'callback' => 'do_interaction_rest'
     ));
     register_rest_route('pz/v1', 'interaction-count', array(
       'methods' => WP_REST_SERVER::READABLE,
       'callback' => 'do_interaction_count_rest'
     ));
   }
   
   function do_interaction_rest($stuff) {
     global $wpdb;
     $limit = 120;
     $offset = 0;

     if( isset($_GET['per'])) {
        $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_interaction WHERE per_id = '{$_GET['per']}' ", ARRAY_A );
     } else  $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_interaction  ", ARRAY_A );
     
     return $results;
   }
  
   function do_interaction_count() {
    global $wpdb;

    if( isset($_GET['per'])) {
      $per_id = $_GET['per'];
    } else $per_id = 0;
    if( isset($_GET['count'])) {
      $project_tasks = $_GET['count'];
    } else $project_tasks = 0;

    $item = array();
    $item['tasks'] = $project_tasks;
    
    $wpdb->update( "{$wpdb->prefix}pz_interaction", $item, array('per_id' => $per_id ));


   }

//    function do_putproj($data) {
//     global $wpdb;
//     $item = array();
//     if( $data[id] == 0 ) {
//       $item['id'] = null;
//     } else {
//       $item['id'] = $data['id'];
//     }
//     $item['project_name'] = $data['project_name'];
//     $item['tenant_ID'] = 'TEST';
//     $item['project_status'] = $data['project_status'];
//     $item['project_lead'] = 2;
//     $item['team_members'] = '';
//     $item['team_members'] = '';
//     $item['project_description'] = 'Be a good lad, then.';
//     $item['kickoff_date'] = $data['kickoff_date'];
//     $item['due_date'] = $data['due_date'];
//     $item['budget'] = $data['budget'];
//     $item['created'] = '';

//     var_dump($item);
//     exit;

    
//       $wpdb->update( "{$wpdb->prefix}pz_project", $item, array('id' => $data['id']) );

//     return $data['project_name'];
 
    
//    }
