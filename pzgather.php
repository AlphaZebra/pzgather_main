<?php
/**
 * Plugin Name:       pzGather
 * Description:       The base PeakZebra components for progressively gathering site visitor info..
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Robert Richardson
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       peakzebra
 *
 * @package           PeakZebra
 */

 if ( ! defined( 'ABSPATH' ) )  exit;

 define('PZ_PLUGIN_DIR', plugin_dir_path(__FILE__));
 //define('PZN_LOADED', "TRUE");

 include( PZ_PLUGIN_DIR . 'includes/register-blocks.php');

 //  include( PZ_PLUGIN_DIR . 'includes/pz-interaction.php');
 //  include( PZ_PLUGIN_DIR . 'includes/pz-request_type.php');
 //  include( PZ_PLUGIN_DIR . 'includes/pz-queue.php');
 //  include( PZ_PLUGIN_DIR . 'includes/pz-logic.php');
 //  include( PZ_PLUGIN_DIR . 'includes/pz-link.php');
 //  include( PZ_PLUGIN_DIR . 'includes/pz-tag-delete.php');
 
 //  include( PZ_PLUGIN_DIR . 'includes/pzn-rest.php');
  include( PZ_PLUGIN_DIR . 'includes/pz-person-rest.php');
  include( PZ_PLUGIN_DIR . 'includes/pz-configuration-rest.php');
 
 //  include( PZ_PLUGIN_DIR . 'includes/pz-project-rest.php');
 //  include( PZ_PLUGIN_DIR . 'includes/pz-task-rest.php');
 //  include( PZ_PLUGIN_DIR . 'includes/pz-message-rest.php');
 


/**
 * Defer loading of javascript files
 */
// function defer_parsing_of_js( $url ) {
//   if ( is_user_logged_in() ) return $url; //don't break WP Admin
//   if ( FALSE === strpos( $url, '.js' ) ) return $url;
//   if ( strpos( $url, 'jquery.js' ) ) return $url;
//   return str_replace( ' src', ' defer src', $url );
// }
// add_filter( 'script_loader_tag', 'defer_parsing_of_js', 10 );


/**
 * Build database at plugin activation... 
 */

 register_activation_hook(
	__FILE__,
	'pz_onActivate'
);

function pz_onActivate() {
  global $wpdb;
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php');

  $charset = $wpdb->get_charset_collate();
  $table_str = $wpdb->prefix . "pz_table_str";

  // this table contains the field definitions for all the other tables in the pz system. 
  dbDelta("CREATE TABLE $table_str (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    table_name varchar(255) NOT NULL DEFAULT '',
    field_string varchar(2000) NOT NULL DEFAULT '',
    created varchar(12) NOT NULL DEFAULT '',
    PRIMARY KEY  (id)
  ) $charset;");


  // fill the table with field definition records for all the tables... 
  // ------------------------------------------------------------------

  
  // configuration table
  $table_name = $wpdb->prefix . "pz_configuration";
 
  $item = array();
  $item['id'] = null;
  $item['table_name'] = $table_name;
  $item['field_string'] = "CREATE TABLE $table_name (
    id bigint(10) unsigned NOT NULL AUTO_INCREMENT,
    config_key varchar(255) NOT NULL DEFAULT '',
    config_value varchar(255) NOT NULL DEFAULT '',
    created varchar(12) NOT NULL DEFAULT '',
    PRIMARY KEY  (id)
  ) $charset;";

  handle_def_record($item);
  dbDelta($item['field_string']);
 

  // interaction table

  $table_name = $wpdb->prefix . "pz_interaction";

  $item = array();
  $item['id'] = null;
  $item['table_name'] = 'pz_interaction';
  $item['field_string'] = "CREATE TABLE $table_name (
    
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    per_id bigint(20) NOT NULL DEFAULT 1,
    inter_summary varchar(255) NOT NULL DEFAULT '',
    inter_details varchar(2000) NOT NULL DEFAULT '',
    inter_created varchar(12) NOT NULL DEFAULT '',
    PRIMARY KEY  (id)
  ) $charset;";

  handle_def_record($item);
  dbDelta($item['field_string']);


  // log table

  $table_name = $wpdb->prefix . "pz_log";

  $item = array();
  $item['id'] = null;
  $item['table_name'] = 'pz_log';
  $item['field_string'] = "CREATE TABLE $table_name (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    msg varchar(255) NOT NULL DEFAULT '',
    created int(12) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY  (id)
  ) $charset;";

  handle_def_record($item);
  dbDelta($item['field_string']);


  // person table

  $table_name = $wpdb->prefix . "pz_person";

  $item = array();
  $item['id'] = null;
  $item['table_name'] = 'pz_person';
  $item['field_string'] = "CREATE TABLE $table_name ( 
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    tenant_id varchar(20) NOT NULL DEFAULT '',
    firstname varchar(30) NOT NULL DEFAULT '',
    lastname varchar(40) NOT NULL DEFAULT '',
    title varchar(60) NOT NULL DEFAULT '',
    company varchar(60) NOT NULL DEFAULT '',
    company_url varchar(255) NOT NULL DEFAULT '',
    addr_line1 varchar(60) NOT NULL DEFAULT '',
    addr_line2 varchar(60) NOT NULL DEFAULT '',
    addr_city varchar(60) NOT NULL DEFAULT '',
    addr_state varchar(2) NOT NULL DEFAULT '',
    addr_zip varchar(12) NOT NULL DEFAULT '',
    email varchar(60) NOT NULL DEFAULT '',
    phone1 varchar(20) NOT NULL DEFAULT '',
    phone1_type varchar(20) NOT NULL DEFAULT '', 
    phone2 varchar(20) NOT NULL DEFAULT '',
    phone2_type varchar(20) NOT NULL DEFAULT '',
    username varchar(20) NOT NULL DEFAULT '',
    has_notes int(4) NOT NULL DEFAULT 0,
    current_interaction varchar(1000) NOT NULL DEFAULT '',
    last_contact date NOT NULL DEFAULT '2023-01-01',
    tags varchar(255) NOT NULL DEFAULT '',
    pz_level varchar(12) NOT NULL DEFAULT '',
    pz_status varchar(10) NOT NULL DEFAULT '', 
    pz_tags varchar(120) NOT NULL DEFAULT '',
    qna varchar(255) NOT NULL DEFAULT '',
    expires date NOT NULL DEFAULT '2023-01-01',
    created varchar(12) NOT NULL DEFAULT '',
      PRIMARY KEY  (id)
  ) $charset;";

handle_def_record($item);
dbDelta($item['field_string']);


// token
$table_name = $wpdb->prefix . "pz_token";

$item = array();
$item['id'] = null;
$item['table_name'] = 'pz_token';
$item['field_string'] = "CREATE TABLE $table_name (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  token varchar(40) NOT NULL DEFAULT '',
  tableid varchar(255) NOT NULL DEFAULT '',
  expiry varchar(32) NOT NULL DEFAULT '',
 PRIMARY KEY  (id)
) $charset;";

handle_def_record($item);
dbDelta($item['field_string']);


}

  function handle_def_record($item) {
    global $wpdb;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');

    $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_table_str WHERE table_name = '{$item['table_name']}'", ARRAY_A );
   
    if( isset($results[0])) {
      $wpdb->update( "{$wpdb->prefix}pz_table_str", $item, array('table_name' => $item['table_name']) );
    } else {
      if( $wpdb->insert( "{$wpdb->prefix}pz_table_str", $item ) <= 0 ) {  
        var_dump( $wpdb );
        exit;
      }
    }
    return true;
  }

// Includes



  /**
   * Shortcode setup
   */

add_action( 'init', 'pz_add_custom_shortcode' );

function pz_add_custom_shortcode() {
	add_shortcode( 'firstname', 'pz_handle_shortcode' );

}

/**
 * Handle shortcodes for all the Person table fields, plus Interaction inter_details
 * 
 */
function pz_handle_shortcode( $atts, $content, $shortcode_tag ) {
  global $wpdb;

  $token = '';  
  if( isset($_COOKIE['pzcontext'])) {
		$token = $_COOKIE['pzcontext'];
		// get data about the person record associated with the token we've just retrieved
		$current_person = pz_test_token($token); 
	} else $current_person = null;
	if( $token )
	$table = $wpdb->prefix . "pz_person";
	if( $current_person ){
		// read pz_person_data array from table -- pz_person_data is global, created at top of this file
		$pz_select = $wpdb->get_results("SELECT * FROM $table WHERE id = $current_person", ARRAY_A);
	} else $pz_select[0] = [];

  $pz_person_data = $pz_select[0];

  switch ($shortcode_tag) {
    case 'firstname':
        if( isset( $pz_person_data['firstname'] )) {
          return( $pz_person_data['firstname']);
        } else return ( 'friend' );
        break;
    case 'lastname':
      if( isset( $pz_person_data['lastname'] )) {
        return( $pz_person_data['lastname']);
      } else return ( '' );
        break;
    case 'name':
      $name = '';
      $space = '';
      if( isset( $pz_person_data['firstname'] )) {
        $name = $pz_person_data['firstname'];
        $space = ' ';
      } 
      if( isset( $pz_person_data['lastname'] )) {
        $name = $name + $space + $pz_person_data['lastname'];
      } 
      if( strlen($name) ) return $name; else return '';
        break;
    case 'title':
      if( isset( $pz_person_data['title'] )) {
        return( $pz_person_data['title']);
      } else return ( '' );
        break;
    case 'company':
      if( isset( $pz_person_data['company'] )) {
        return( $pz_person_data['company']);
      } else return ( '' );
        break;
    case 'addr_line1':
      if( isset( $pz_person_data['addr_line1'] )) {
        return( $pz_person_data['addr_line1']);
      } else return ( '' );
        break;
    case 'addr_line2':
      if( isset( $pz_person_data['addr_line2'] )) {
        return( $pz_person_data['addr_line2']);
      } else return ( '' );
        break;
    case 'addr_city':
      if( isset( $pz_person_data['addr_city'] )) {
        return( $pz_person_data['addr_city']);
      } else return ( '' );
        break;
    case 'addr_city':
      if( isset( $pz_person_data['addr_city'] )) {
        return( $pz_person_data['addr_city']);
      } else return ( '' );
        break;
    case 'addr_city':
      if( isset( $pz_person_data['addr_city'] )) {
        return( $pz_person_data['addr_city']);
      } else return ( '' );
        break;
    case 'addr_state':
      if( isset( $pz_person_data['addr_state'] )) {
        return( $pz_person_data['addr_state']);
      } else return ( '' );
        break;
    case 'addr_zip':
      if( isset( $pz_person_data['addr_zip'] )) {
        return( $pz_person_data['addr_zip']);
      } else return ( '' );
        break;
    case 'email':
      if( isset( $pz_person_data['email'] )) {
        return( $pz_person_data['email']);
      } else return ( '' );
        break;
    case 'phone1':
      if( isset( $pz_person_data['phone1'] )) {
        return( $pz_person_data['phone1']);
      } else return ( '' );
        break;
    case 'phone1_type':
      if( isset( $pz_person_data['phone1_type'] )) {
        return( $pz_person_data['phone1_type']);
      } else return ( '' );
        break;
    case 'phone2':
      if( isset( $pz_person_data['phone2'] )) {
        return( $pz_person_data['phone2']);
      } else return ( '' );
        break;
    case 'phone2':
      if( isset( $pz_person_data['phone2'] )) {
        return( $pz_person_data['phone2']);
      } else return ( '' );
        break;
    case 'phone2_type':
      if( isset( $pz_person_data['phone2_type'] )) {
        return( $pz_person_data['phone2_type']);
      } else return ( '' );
        break;
    case 'username':
      if( isset( $pz_person_data['username'] )) {
        return( $pz_person_data['username']);
      } else return ( '' );
        break;
    case 'has_notes':
      if( isset( $pz_person_data['has_notes'] )) {
        return( $pz_person_data['has_notes']);
      } else return ( '' );
        break;
    case 'last_contact':
      if( isset( $pz_person_data['last_contact'] )) {
        return( $pz_person_data['last_contact']);
      } else return ( '' );
        break;
    case 'tags':
      if( isset( $pz_person_data['tags'] )) {
        return( $pz_person_data['tags']);
      } else return ( '' );
        break;
    case 'pzlevel':
      if( isset( $pz_person_data['pzlevel'] )) {
        return( $pz_person_data['pzlevel']);
      } else return ( '' );
        break;
    case 'pzstatus':
      if( isset( $pz_person_data['pzstatus'] )) {
        return( $pz_person_data['pzstatus']);
      } else return ( '' );
        break;
    case 'expires':
      if( isset( $pz_person_data['expires'] )) {
        return( $pz_person_data['expires']);
      } else return ( '' );
        break;
    case 'created':
      if( isset( $pz_person_data['created'] )) {
        return( $pz_person_data['created']);
      } else return ( '' );
        break;
  } // switch
}  // shortcode function



/** 
 * write qna answer to person record
 * 
 * $id - person id
 * $slug - identifier for particular question
 * $answer - value for particular answer from select dropdown
 */
function pz_qa_add_person_answer( $id, $slug, $answer ) {
  global $wpdb;
  $table = $wpdb->prefix . "pz_person";

  // get the person record as it stands
  $pz_select = $wpdb->get_results("SELECT * FROM $table WHERE id = $id", ARRAY_A);
  $pz_person_data = $pz_select[0];

  // decode the JSON array stored in the qa field
  $pz_q_array = json_decode( $pz_person_data['qna'], true );

  // add slug: answer to the array
  $pz_q_array[$slug] = $answer;

  // JSON encode
  $pz_encoded = json_encode($pz_q_array);

  // update the person record
  $wpdb->update($table, array('qna' => $pz_encoded), array('id' => $id ));

}


/**
 * Alternate between log function that just returns (low impact) and 
 * one that actually writes to the log. Quick and dirty switch for logging. 
 */
// function pz_log( $msg ) {}

function pz_log( $msg ) {
  global $wpdb;
  $table = $wpdb->prefix . "pz_log";
  $date = date_create();

  $wpdb->insert(
    $table,
    array(
        'id' => null,
        'msg' => $msg,
        'created' =>  date_timestamp_get($date)
    )
);

}


