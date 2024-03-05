<?php

// $pz_current = array();

// function pz_get_current_JSON() {
//     global $pz_current;
//     global $wpdb;


//     // read first record from configuration file

// return( /* JSON */ );
// }

// function pz_get_current() {
//     global $pz_current;

//     $json = pz_get_current_JSON(); 

//     // parse JSON string into associative array $pz_current

//     return true; // we're setting as global array, so no need to return anything meaningful

// }

// function pz_set_current( $current_value_pair ) {
//     global $pz_current;
//     global $wpdb;

//     $json = pz_get_current_JSON(); 

//     // find pair if already exists and replace it

//     // else add pair to JSON string

//     // update JSON string in the config table

//     return true;


// }

/** 
 * Keys:
 * $pz_person_tags  -- all "interest" tags being used for people in this specific pz instance
 */

function pz_set_config( $key, $value ) {
    global $wpdb;

    $tablnam = $wpdb->prefix . "pz_configuration";

    
    // if we're updating, we'll use a different SQL command
    $item = array();
    $item['config_key'] = $key;
    $item['config_value'] = $value;
    $item['created'] = '';

    
    if( !$wpdb->update( $tablnam, $item, array('config_key' => $item['config_key'] ))) {
        if( $wpdb->insert( $tablnam, $item ) <= 0 ) {  
			if(strpos($wpdb->last_error, "Duplicate entry") !== false) {
				wp_redirect( 'add-request-type/?err=dup');
				exit;
			}
            var_dump( $wpdb );
            exit;
        }
    }
        
}

function pz_get_config( $key ) {
    global $wpdb;
    $tablnam = $wpdb->prefix . "pz_configuration";
    

    $results = $wpdb->get_results( "SELECT * FROM $tablnam WHERE config_key = '$key' ", ARRAY_A );
    if( !isset($results[0])) {
        return -1;
    }
   
    return( $results[0]['config_value']);

}

