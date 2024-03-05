<?php

/**
 * Access token stuff...
 */
  
 define( 'PZ_NONCE_DURATION', "+24 hours" ); 


/**
  * REST API for creating/retrieving pz specific nonce... 
  * sample request URL: example.com/wp-json/pz/v1/pzn
**/

add_action('rest_api_init', 'set_up_pzn_rest_route');
function set_up_pzn_rest_route() {
  register_rest_route('pz/v1', 'pzn', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'do_pzn'
  ));
  register_rest_route('pz/v1', 'pzn_test', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'do_pzn_test'
  ));
  
 
}

// create a 'nonce' with a timestamp, add it to table, and return the random token portion of it
function do_pzn($stuff) {
  global $wpdb;

  // check if a token is already in play (stored in cookie)
  if( isset($_COOKIE['pzcontext'])) {
    // test this token 
    $id = pz_test_token( $_COOKIE['pzcontext']);
    // pz_log('do_pzn: read context token, id= ' . $id );
    if( !$id ) {
      echo 'Error: invalid token :' . $_COOKIE['pzcontext'];
      exit;
      return false; // invalid cookie -- should call err manager
    } else {
      // we have a valid token 
    }
  } else $id = null;  // we'll insert rather than update

  // calculate expiration date and time
  $d=strtotime("now");
  $e = strtotime( PZ_NONCE_DURATION, $d );
  $item['id'] = $id;
  $item['tableid'] = 0; // we won't have the real id till after inserting person record
  $item['expiry'] = $e;
  
  // only generate new token if there isn't one already in play
  if( $id === null ) {
    $results = wp_generate_password( 32, false, false );  // 32 random alphanumeric characters 
    $item['token'] = $results;
  }
  
  
  // insert to nonce table
  // pz_log('do_pzn: inserting token with tableid= ' . $item['tableid']);
  if( $wpdb->insert( "{$wpdb->prefix}pz_token", $item ) <= 0 ) {  
   echo "Error:\n";
   var_dump( $wpdb );
   exit;
  }

  return $results;
}

// general function for testing 'nonce' token
function pz_test_token($token) {
 global $wpdb;
 $now=strtotime("now");
 
 $item = [];
 if( isset($_GET['token'])) {
  $item['token'] = $_GET['token'];
 } else $item[ 'token' ] = $token;
//  pz_log( 'pzn_test_token: $token = ' . $token );
 
 // get record from token table
 $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_token WHERE token = '{$item['token']}'", ARRAY_A );
 
 if( isset($results[0])) {
   $item = $results[0];
   if ($now >= $item['expiry']  ) {
     // delete the expired token
     $wpdb->delete( $wpdb->prefix . "pz_token", array('id' => $item['id']));
     return false;
   } else { 
      // pz_log('pzn_test_token: tableid= ' . $item['tableid']);
      return $item['tableid'];
   }

   } else {
     
     return false; // no matching token was found in table
   }
}

// wrapper for above function
function do_pzn_test($stuff) {
 
 // get token from request URL and test for validity
  $result = pz_test_token($_GET['token']);
  if( $result ) return $result;
  return "invalid";
}




