<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action('admin_post_do-form', 'do_form');
add_action('admin_post_nopriv_do-form', 'do_form');

function do_coolform () {
	global $wpdb;
	$created = date("Y/m/j");
	$person = [];
	$person['id'] = '';

	// botcheck
	if( $_POST['name'] != '' ) {
		wp_redirect( '/');
	}

	$_POST = apply_filters( 'pz_pre_form_processing', $_POST );

	$_POST = wp_unslash( $_POST ); // wordpress escapes form input, sigh... so we remove the slashes
	extract( $_POST, EXTR_OVERWRITE ); // let's break the form input into separate variables
	if( isset( $_POST['phpText'])) {
		eval( $_POST['phpText']); // and evaluate the code written by the form creator (not the end user)
	}



	if( isset($qc)) $qna = $slug . ':' . $qc;

	if( isset( $_COOKIE['pzcontext'] )) {
		$pz_token = $_COOKIE['pzcontext']; 
	} else { $pz_token = ''; }
		
	$tableid = pz_test_token( $pz_token );  // test returns person id assoc. w/ token
		if( $tableid < 1 ) {
			$person['id'] = 0;
		} else $person['id'] = $tableid;
	$interaction['per_id'] = $person['id'];


	pz_log( 'do_coolform: read cookie, id: ' . $tableid );
	
	// now let's store all the fields to the proper tables... 
	if( isset($lastname) ) $person['lastname'] = $lastname;
	if( isset($firstname) ) $person['firstname'] = $firstname;
	if( isset($company) ) $person['company'] = $company;
	if( isset($title) ) $person['title'] = $title;
	if( isset($addr_line1) ) $person['addr_line1'] = $addr_line1;
	if( isset($addr_line2) ) $person['addr_line2'] = $addr_line2;
	if( isset($addr_city) ) $person['addr_city'] = $addr_city;
	if( isset($addr_state )) $person['addr_state'] = $addr_state;
	if( isset($addr_zip )) $person['addr_zip'] = $addr_zip;
	if( isset($email) ) $person['email'] = $email;
	if( isset($phone1) ) $person['phone1'] = $phone1;
	if( isset($phone1_type )) $person['phone1_type'] = $phone1_type;
	if( isset($phone2 )) $person['phone2'] = $phone2;
	if( isset($phone2_type )) $person['phone2_type'] = $phone2_type;
	if( isset($username )) $person['username'] = $username;
	if( isset($has_notes )) $person['has_notes'] = $has_notes;
	if( isset($last_contact )) $person['last_contact'] = $last_contact;
	if( isset($pz_level) ) $person['pz_level'] = $pz_level;
	if( isset($pz_status )) $person['pz_status'] = $pz_status;
	if( isset($pz_tags )) $person['pz_tags'] = $pz_tags;
	if( isset($expires )) $person['expires'] = $expires;
	if( isset($tags )) $person['tags'] = $tags;
	if( isset($created )) $person['created'] = $created;
	if( isset($qna)) $person['qna'] = $qna;

	// interaction fields
	$interaction['inter_summary'] = isset( $inter_summary ) ? $inter_summary : '' ; 
	$interaction['inter_details'] = isset( $inter_details ) ? $inter_details : '' ; 

	// send an email if the form included a send-to address
	if( isset($_POST['formEmail']) && $_POST['formEmail'] != '' ) {
		$message = json_encode( $_POST );
		$subject = "Form data from " . $_POST['formName'];
		wp_mail( $_POST['formEmail'], $subject, $message );
	}
	
	$person = apply_filters( 'pz_pre_record_save', $person, $interaction );

	if( $tableid ) {
		// update person record
		$pz_temp = $person['id'];
		pz_dbwrite( $wpdb->prefix . "pz_person", $person['id'], $person );
	} else {
		$pz_temp = pz_dbwrite( $wpdb->prefix . "pz_person", $person['id'], $person );
		$interaction['per_id'] = $pz_temp;  // need new person's new id number to write other, connected records
		pz_log( 'inserting  to person  ' . $pz_temp );
	
		//save the new id back to the token record to keep context current
		pz_log('update token ' . $pz_token . ' to show person id ' . $pz_temp );
		$wpdb->update( $wpdb->prefix . "pz_token", array( 'tableid' => $pz_temp), array( 'token' => $pz_token ) ) ;
	}

	// write interaction if needed

	if( $interaction['inter_summary'] || $interaction['inter_details']) {
		pz_dbwrite( $wpdb->prefix . "pz_interaction", NULL, $interaction );
		pz_log( 'do_coolform: write interaction: ' . $interaction['inter_details']);
	}
	do_action( 'pz_data_written', $pz_temp );

	$redirectURL = apply_filters( 'pz_filter_redirectURL', $redirectURL );
	if( isset( $redirectURL )) {
		wp_redirect( $redirectURL . '?token=' . $pz_token );
		exit;
	} else {
		wp_redirect( '/?token=' . $pz_token );
		exit;
	}
  
	 
}  

function pz_dbwrite( $table_name, $id, $item_array ) {	
	global $wpdb;

	
	if( $id == 0 ) {
		if( str_contains( $table_name, 'person' )) {
			$item_array['tenant_id'] = 'none';
		}
		$item_array['id'] = NULL;



		$wpdb->insert(
			$table_name,
			$item_array
		);
		$temp = $wpdb->insert_id;
		pz_log( 'pz_dbwrite: wrote to id ' . $temp );

		return( $temp );
	} else {
		$wpdb->update( $table_name, $item_array, array( 'id' => $id ) );
		pz_log( 'pz_dbwrite: updated ' . $id );
		return( $id );

	}

	return true;

}
