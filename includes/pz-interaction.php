<?php

add_action('admin_post_do-interaction-form', 'do_interaction_form');
add_action('admin_post_nopriv_do-interaction-form', 'do_interaction_form');

function pz_interaction_block($attributes) {
	global $wpdb;
	global $pz_cur_person;

	$item = array(
		'id' => null,
		'per_id' => 0,
		'summary' =>  '',
		'details' => '',
		'created' => '',
	);

	
	if(isset($_GET['int'])) {
		if( $_GET['int'] > 0 ) {
			$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_interaction WHERE id = {$_GET['int']}", ARRAY_A );
			$item = $results[0];
		}
	} else {
		$item['id'] = null;  // new record
	}
	if(isset($_GET['per'])) {
		$item['per_id'] = $_GET['per'];
	}



	ob_start();
    ?>

<form action="<?php echo esc_url(admin_url('admin-post.php')) ?>" method="POST" class="form-style-1">
		<input type="hidden" name="action" value="do-interaction-form" required>
		<input type="hidden" name="per_id" value="<?php echo $item['per_id'] ?>" required>
		<input type="hidden" name="id" value="<?php echo $item['id'] ?>" required>
		<input type="hidden" name="listURL" value="<?php echo $attributes['listURL'] ?>" required>
			
        <label>Summary</label>
        <input
			name="summary"
            type="text"
            class="field-long"
			value="<?php echo stripslashes($item['summary']) ?>"
            placeholder="Basic description of interaction..."
        />
        <label>Details</label>
        <textarea name="details" cols="85" rows="8"><?php  echo stripslashes($item['details']) ?></textarea>
        <input type="submit" value="Save" />
    </form>
    <?php

    return ob_get_clean();
    

}


function pz_inter_list ($attributes) {
    global $wpdb;
	global $pz_cur_person;

    $page = 0;
    $limit = /* $attributes['numRows']; */ 12;
	$offset = $limit * $page;

	// wp_enqueue_script('pzinteractiongrid', plugin_dir_url(__FILE__) . 'build/interactiongrid.js', array('wp-element', 'wp-components'), null, true);



	
	if (isset($_GET['per'])) {
		$person = $_GET['per'];
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_interaction WHERE id IS $person ", ARRAY_A );

	} else {
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_interaction LIMIT $limit OFFSET $offset ", ARRAY_A );
		if( !isset($results[0])) {
			$offset=0;
			$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_interaction LIMIT $limit OFFSET $offset ", ARRAY_A );
		};
	}
	
	

    ob_start();
	if( !isset($results[0])) { return( pz_interaction_block($attributes)); }
	?>
	<table class="pz-table-style" >
		
		<thead>
			<th>Edit</th>
			<th>ID</th>
			<th>Summary</th>
			<th>Details</th>
		</thead>

	<?php

	foreach($results as $result) {
		echo '<tr><td><a href="\"><image src=' . plugin_dir_url(__FILE__) . './pencil.png width="40%"></a></td><td>';
		echo $result['id'] . '</td><td>';
		echo $result['summary'] . '</td><td>';
		echo $result['details'] . '</td></tr>';
		
		

	}
	?>

	<tr>
		<td><img src="<?php echo plugin_dir_url(__FILE__) ?>./left-arrow.png" width="80%"></td>
		<td></td>
		<td><img src="<?php echo plugin_dir_url(__FILE__) ?>./right-arrow.png" width="80%"></td>
	</tr>
	</table>
	<?php 

	return ob_get_clean();
}


function do_interaction_form () {
	global $wpdb;
	$created = date("Y-m-j");
  
	$item = [];
  
	$item['id'] = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : null;
	$item['per_id'] = isset($_POST['per_id']) ? sanitize_text_field($_POST['per_id']) : 1;
	$item['summary'] = isset($_POST['summary']) ? stripslashes(sanitize_text_field($_POST['summary'])) : 'none';
	$item['details'] = isset($_POST['details']) ? stripslashes(sanitize_text_field($_POST['details'])) : 'none';
	$item['created']= $created;
  
  
	$tablnam = $wpdb->prefix . "pz_interaction";
	// if we're updating, we'll use a different SQL command
	if(  $item['id'] > 0  )  {
		$item['id'] = $_POST['id'];
		
		if ($wpdb->update( $tablnam, $item, array('id' => $item['id']) ) < 0) {
		  var_dump($wpdb);
		  exit;
		}
		$pz_int_id = $item['id'];
		  
	} else {
		$item['id'] = null;
		if( $wpdb->insert( $tablnam, $item ) <= 0 ) {  
			var_dump( $wpdb );
			exit;
		}
  
		$pz_int_id = $wpdb->insert_id;  // this is the id number of the record we just inserted
	}

	// we also update the 'last_contact' field of the person record associated with the interaction 
	$tablnam = $wpdb->prefix . "pz_person";
	if(  $wpdb->update( $tablnam, array( 'last_contact' => $created, 'has_notes' => 1 ), array( 'id' => $_POST['per_id'] )) === false ) {
		var_dump( $wpdb );
		exit;
	}
   $redirect_url = trailingslashit( $_POST['listURL']);
	wp_redirect( $redirect_url . '?per=' . $item['per_id']);
	exit;
  }


