<?php


/**
 * a LIST TABLE of items in the request queue. 
 */

function pz_queue($attributes) {
    global $wpdb;
    //wp_enqueue_script('pzrequesttype', plugin_dir_url(__FILE__) . 'build/blocks/pz_task_list/pzrequestype.js', array('wp-element', 'wp-components'), null, true);
    
    // get the tasks for the specific project 

    $limit = 10;
    $offset = 0;

	if($attributes['isCurrent'] == true ) {
		$current_user = wp_get_current_user();
		$userfilter = "WHERE username = '" . esc_html( $current_user->user_login ) . "'";
	} else if( isset($attributes['user'])) {
		$userfilter = "WHERE username = '" . esc_html( $attributes['user']) . "'";
	} else $userfilter = '';
	// var_dump($userfilter);
	// exit;

		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_request_queue $userfilter ", ARRAY_A );
		if( !isset($results[0])) {
			$offset=0;
			$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_request_queue LIMIT $limit OFFSET $offset ", ARRAY_A );
		};
	
		if( !isset($results[0])) {
			return( "<h6>No queue records found.</h6>");
		};

	// var_dump($wpdb);
	// exit;

    ob_start();
    ?>
    
    <table class="pz-table-style" >
		
		<thead>
			<th>ID</th>
			<th>Category</th>
			<th>Request</th>
			<th>Description</th>
			<th>Details</th>
			
		</thead>

	<?php

	foreach($results as $result) {
		echo "<tr><td>";
		echo $result['slug'] . '</td><td>';
		echo $result['category'] . '</td><td>';
		echo $result['display_name'] . '</td><td>';
		echo $result['request_description'] . '</td><td>';
		echo $result['request_detail'] . '</td></tr>';
	}
	?>

	
	</table>
	<?php 

	return ob_get_clean();


}

