<?php

add_action('admin_post_do-link-form', 'do_link_form');
add_action('admin_post_nopriv_do-link-form', 'do_link_form');

/**
 * pz_task_form
 */

function pz_link_form($attributes) {
	global $wpdb;


	$item = array(
		'id' => 0,
		'tenant_ID' =>  '',
		'app_id' => '',
		'link_name' => '',
		'link_url' => '',
		'link_image_url' => '',
		'link_description' => '',
		'link_tag' => '',
		'live_date' => '',
		'end_date' => '',
		'link_owner' => '',
		'created' => '',
	);
	
	if(isset($_GET['link'])) {
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_link WHERE id = {$_GET['link']}", ARRAY_A );

		$item = $results[0];
	} else {
		
	}

	
	ob_start();
	?>
	
	<form action="<?php echo esc_url(admin_url('admin-post.php')) ?>" method="POST" class="form-style-1">
		<input type="hidden" name="action" value="do-link-form" required>
		<input type="hidden" name="id" value="<?php echo $item['id'] ?>" required>
		<input type="hidden" name="tenant_ID" value="<?php echo $item['tenant_ID'] ?>" required>
		<input type="hidden" name="redirectURL" value="<?php echo $attributes['redirectURL'] ?>" required>
			
		
			<label>Link</label>
			<input type="text" name="link_name" class="field-long" value="<?php echo $item['link_name'] ?>" />
			<label>Link URL</label>
			<input type="text" name="link_url" class="field-long" value="<?php echo $item['link_url'] ?>" />
			
			<label>Link image URL</label>
			<input type="text" name="link_image_url" class="field-long" value="<?php echo $item['link_image_url'] ?>"  />
			<label>Link description</label>
			<input type="text" name="link_description" class="field-long" value="<?php echo $item['link_description'] ?>"  />
			<label>Link tag</label>
			<input type="text" name="link_tag" class="field-long" value="<?php echo $item['link_tag'] ?>"  />
			<label>Live date</label>
			<input type="date" name="live_date" class="field-divided" value="<?php echo $item['live_date'] ?>"  />
			<label>End date</label>
			<input type="date" name="end_date" class="field-divided" value="<?php echo $item['end_date'] ?>"  />
			<input type="submit" value="Save" />
		</form>
	
	<?php
	return ob_get_clean();
}




function do_link_form () {
	global $wpdb;
	$created = date("m/j/Y");
  
	$item = [];
  
	$item['id'] = sanitize_text_field($_POST['id']);
	$item['app_id'] = isset($_POST['app_ID']) ? sanitize_text_field($_POST['app_ID']) : 'none';
	$item['tenant_ID'] = isset($_POST['tenant_ID']) ? sanitize_text_field($_POST['tenant_ID']) : 'none';
	$item['link_name'] = isset($_POST['link_name']) ? sanitize_text_field($_POST['link_name']) : 'none';
	$item['link_url'] = isset($_POST['link_url']) ? sanitize_text_field($_POST['link_url']) : '';
	$item['link_description'] = isset($_POST['link_description']) ? sanitize_text_field($_POST['link_description']) : '';
	$item['link_tag'] = isset($_POST['link_tag']) ? sanitize_text_field($_POST['link_tag']) : '';
	$item['link_image_url'] = isset($_POST['link_image_url']) ? sanitize_text_field($_POST['link_image_url']) : '';
	$item['live_date'] = isset($_POST['live_date']) ? sanitize_text_field($_POST['live_date']) : '';
	$item['end_date'] = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : 'none';
	$item['link_owner'] = isset($_POST['link_owner']) ? sanitize_text_field($_POST['link_owner']) : 'none';
	$item['created']= $created;
  
  
	$tablnam = $wpdb->prefix . "pz_link";
	// if we're updating, we'll use a different SQL command
	if(  $_POST['id'] > 0  )  {
		$item['id'] = $_POST['id'];
		
		if ($wpdb->update( $tablnam, $item, array('id' => $item['id']) ) < 0) {
		  var_dump($wpdb);
		  exit;
		}
		$pz_id = $item['id'];
		  
	} else {
		if( $wpdb->insert( $tablnam, $item ) <= 0 ) {  
			var_dump( $wpdb );
			exit;
		}
  
		$pz_id = $wpdb->insert_id;  // this is the id number of the record we just inserted
	}
  
   
	// var_dump($pz_id);
	// exit;
   
	// setcookie('pz_num', $pz_id, time()+31556926);
	
	wp_redirect( $_POST['redirectURL']);
	exit;
  }