<?php

/**
 * Contains:
 * 
 * pz_request_type - A TABLE-based LIST
 * * pz_request_type_form
 */

add_action('admin_post_do-request-type-form', 'do_request_type_form');
add_action('admin_post_nopriv_do-request-type-form', 'do_request_type_form');
add_action('admin_post_do-queue-add', 'do_queue_add');
add_action('admin_post_nopriv_do-queue-add', 'do_queue_add');
add_action('admin_post_do-request-type-delete', 'do_request_type_delete');
add_action('admin_post_nopriv_do-request-type-delete', 'do_request_type_delete');

/**
 * pz_request_type - A TABLE-based LIST
 */

function pz_request_type($attributes) {
    global $wpdb;
    //wp_enqueue_script('pzrequesttype', plugin_dir_url(__FILE__) . 'build/blocks/pz_task_list/pzrequestype.js', array('wp-element', 'wp-components'), null, true);
    
    // get the tasks for the specific project 

    $limit = 10;
    $offset = 0;
	$catFilter = '';

	if( $attributes['catFilter'] != '') {
		$catFilter = "WHERE category = '" . $attributes['catFilter'] . "'" ;
	}

		
 
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_request_type " . $catFilter, ARRAY_A );
		if( !isset($results[0])) {
			$offset=0;
			$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_request_type LIMIT $limit OFFSET $offset " . $catFilter, ARRAY_A );
		};
	
		if( !isset($results[0])) {
			return( "<h6>No request type records found.</h6>");
		};

    ob_start();

    ?>
    
    <table class="pz-table-style" >
		
		<thead>
			<th style="margin: 10px; min-width: 40px; max-width: 70px;" >Enqueue</th">
			<?php
			
			if($attributes['viewCategory'] == true) {
				echo '<th>Category</th>';
			}
			?>
			
			<th>Request</th>
			<th>Description</th>
			<th>Level</th>
			<?php  echo '<th>Actions</th>'; ?>
				
		</thead>

	<?php

	foreach($results as $result) {
		echo "<tr><td>";
		echo "<form action=" . esc_url(admin_url('admin-post.php')) . " method='POST' class='form-style-1' style='height: 40; min-width: 70px; max-width: 70px;'>";
		?>
	
			
			<input type="hidden" name="action" value="do-queue-add" required>
			<input type="hidden" name="slug" value="<?php echo $result['slug'] ?>" required>
			<input type="hidden" name="tenant_ID" value="<?php echo $result['tenant_ID'] ?>" required>
			<input type="hidden" name="category" value="<?php echo $result['category'] ?>" required>
			<input type="hidden" name="display_name" value="<?php echo $result['display_name'] ?>" required>
			<input type="hidden" name="request_description" value="<?php echo $result['request_description'] ?>" required>
			<input type="hidden" name="request_level" value="<?php echo $result['request_level'] ?>" required>
			<input type="hidden" name="redirectURL" value=<?php echo $attributes['redirectURL'] ?> required>
			<button type="submit" style="border: 0; background-color: white; ">
			<img src='<?php echo plugin_dir_url(__FILE__)  ?>assets/add2q.png' width='30px'>
			</button>
			</form></td><td>
		<?php
		
		if($attributes['viewCategory']) {
			echo $result['category'] . '</td><td>';
		}
		
		echo $result['display_name'] . '</td><td>';
		echo $result['request_description'] . '</td><td>';
		echo $result['request_level'] . '</td><td>';
		if( current_user_can('administrator')) { 
			echo "<form action=" . esc_url(admin_url('admin-post.php')) . " method='POST' class='form-style-1'>";
			?>
			<input type="hidden" name="action" value="do-request-type-delete" required>
			<input type="hidden" name="slug" value="<?php echo $result['slug'] ?>" required>
			<input type="hidden" name="redirectURL" value="/" required>
			<button type="submit" >
			<img src=' <?php echo plugin_dir_url(__FILE__)  ?>trash.png' width='15px'>
			</button>
			</form>
			<a href="<?php echo $attributes['editURL']  ?>"><img src=' <?php echo plugin_dir_url(__FILE__)  ?>pencil.png' width='20px'></a>
			
		<?php
		} 
		
	}
	?>

	
	</table>
	<?php 

	return ob_get_clean();


}

/**
 * pz_request_type_form
 */

function pz_request_type_form($attributes) {
	global $wpdb;

	$item = array(
		'slug' => 'hi',
		'category' => (isset($_POST['category'])) ? $_POST['category'] : '',
		'display_name' => '',
		'request_description' => '',
		'post_url' => '',
		'request_level' => ''
	
	);
	
	// The isEdit attribute is true if an existing record should be read in for editing in the form. 
	// If true, the record with the id stored in the global $pz_cur_person is read in, even though
	// it's theoretically loaded in the $pz_cur_person array -- just a safety precaution in case the 
	// record has been changed elsewhere since the last time we read it in. 
	// $update = '';
	// if( $attributes['isEdit'] ) {
	// 	$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_person WHERE id = {$pz_cur_person['id']}", ARRAY_A );
	// 	$item = $results[0];
	// 	$update = "update";
	// } else if (isset($_GET['per'])) {
	// 	$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_person WHERE id = {$_GET['per']}", ARRAY_A );
	// 	$item = $results[0];
	// 	$update = "update";
	// }

	ob_start();
	?>
	
	<form action="<?php echo esc_url(admin_url('admin-post.php')) ?>" method="POST" class="form-style-1">
		<input type="hidden" name="action" value="do-request-type-form" required>
		<input type="hidden" name="redirectURL" value="<?php echo $attributes['redirectURL']   ?>" required >
		
			<label>Shortname</label>
			<input type="text" name="slug" class="field-divided" value="<?php echo $item['slug'] ?>" placeholder="Abbrev." />
			<label>Category</label>
			<input type="text" name="category" class="field-divided" value="<?php echo $item['category'] ?>" placeholder="Category" />
			<label>Request name</label>
			<input type="text" name="display_name" class="field-long" value="<?php echo $item['display_name'] ?>" placeholder="Request name..." />
			<label>Description</label>
			<input type="text" name="request_description" class="field-long" value="<?php echo $item['request_description'] ?>" placeholder="Request description..." />
			<label>Post URL</label>
			<input type="text" name="post_url" class="field-long" value="<?php echo $item['post_url'] ?>" placeholder="URL to post to on selection..." />
			<label>Level</label>
			<input type="text" name="request_level" class="field-divided" value="<?php echo $item['request_level'] ?>" placeholder="Effort level 1-4" />
			<input type="submit" value="Save" />
		</form>
	<div class="pz-target-div"><pre><?php /* echo wp_json_encode($attributes);*/ ?></pre></div>
	
	<?php
	return ob_get_clean();
}


/**
 * FORM: Request Detail Form
 */

 function pz_request_detail_form($attributes) {
	global $wpdb;

	$item = array(
		'slug' => (isset($_POST['slug'])) ? $_POST['slug'] : '',
		'category' => (isset($_POST['category'])) ? $_POST['category'] : '',
		'display_name' => (isset($_POST['display_name'])) ? $_POST['display_name'] : '',
		'request_description' => (isset($_POST['request_description'])) ? $_POST['request_description'] : '',
		'request_level' => (isset($_POST['request_level'])) ? $_POST['request_level'] : '',
		'redirectURL' => (isset($_POST['redirectURL'])) ? $_POST['redirectURL'] : '/',
		'request_detail' => ''
	
	);
	
	// read request type record for preset data elements


	ob_start();
	?>
	
	<form action="<?php echo esc_url(admin_url('admin-post.php')) ?>" method="POST" class="form-style-1">
		<input type="hidden" name="action" value="do-queue-add" required>
		<input type="hidden" name="redirectURL" value="<?php echo $item['redirectURL'] ?>" >
		
			<label>Shortname</label>
			<input type="text" name="slug" class="field-divided" value="<?php echo $item['slug'] ?>" placeholder="Abbrev." />
			<label>Category</label>
			<input type="text" name="category" class="field-divided" value="<?php echo $item['category'] ?>" placeholder="Category" />
			<label>Request name</label>
			<input type="text" name="display_name" class="field-long" value="<?php echo $item['display_name'] ?>" placeholder="Request name..." />
			<label>Description</label>
			<input type="text" name="request_description" class="field-long" value="<?php echo $item['request_description'] ?>" placeholder="Request description..." />
			<label>Detail</label>
			<input type="text" name="request_detail" class="field-long" value="<?php echo $item['request_detail'] ?>" placeholder="Request description..." />
			<label>Level</label>
			<input type="text" name="request_level" class="field-divided" value="<?php echo $item['request_level'] ?>" placeholder="Effort level 1-4" />
			<input type="submit" value="Save" />
		</form>
	<div class="pz-target-div"><pre><?php /* echo wp_json_encode($attributes);*/ ?></pre></div>
	
	<?php
	return ob_get_clean();
}



/**
 * POST processing for request type form
 * ******************************************************************
 * *******************************************************************
 * 
 */

function do_request_type_form( ) {
    global $wpdb;
    $created = date("m/j/Y");


    $item = [];

    $item['slug'] = sanitize_text_field($_POST['slug']);
    $item['tenant_ID'] = "None";
    $item['category'] = sanitize_text_field($_POST['category']);
    $item['display_name'] = sanitize_text_field($_POST['display_name']);
    $item['request_description'] = sanitize_text_field($_POST['request_description']);
    $item['post_url'] = sanitize_text_field($_POST['post_url']);
	$item['request_level'] = sanitize_text_field($_POST['request_level']);
    $item['created']= $created;


    // var_dump($item);
    // exit;

    $tablnam = $wpdb->prefix . "pz_request_type";
    // if we're updating, we'll use a different SQL command
    if( /* isset($_POST['update']) */ false ) {
        $item['slug'] = $_POST['slug'];
        $wpdb->update( $tablnam, $item, array('slug' => $item['slug']) );
        $pz_id = $item['slug'];

    } else {
        if( $wpdb->insert( $tablnam, $item ) <= 0 ) {  
			if(strpos($wpdb->last_error, "Duplicate entry") !== false) {
				wp_redirect( 'add-request-type/?err=dup');
				exit;
			}
            var_dump( $wpdb );
            exit;
        }
    
        $pz_id = $wpdb->insert_id;  // this is the id number of the record we just inserted
    }

	wp_redirect($_POST['redirectURL']);
    exit;
}


function do_queue_add () {
	global $wpdb;
    $created = date("m/j/Y");

	

	// read the request_type record for all the odds and ends
	$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_request_type WHERE slug = '{$_POST['slug']}' ", ARRAY_A );
	$result = $results[0];

	

	// tie to current user
	$current_user = wp_get_current_user();
	$username = esc_html( $current_user->user_login );
	
    $item = [];

    $item['requestID'] = null;
    $item['tenant_ID'] = "None";
    $item['username'] =$username;
	$item['category'] = (isset($result['category'])) ? $result['category'] : 'none';
    $item['slug'] = isset($result['slug']) ? $result['slug'] : 'none';
    $item['display_name'] = isset($result['display_name']) ? $result['display_name'] : 'none';
    $item['req_priority'] = "1";
    $item['request_description'] = isset($result['request_description']) ? $result['request_description'] : 'none';
    $item['request_detail'] = isset($_POST['request_detail']) ? $_POST['request_detail'] : 'none';
    $item['username'] = isset($result['username']) ? $result['username'] : $username;
    $item['created']= $created;

    $tablnam = $wpdb->prefix . "pz_request_queue";
    // if we're updating, we'll use a different SQL command
    if( /* isset($_POST['update']) */ false ) {
        $item['slug'] = $_POST['slug'];
        $wpdb->update( $tablnam, $item, array('slug' => $item['slug']) );
        $pz_id = $item['slug'];

    } else {
        if( $wpdb->insert( $tablnam, $item ) <= 0 ) {  
            
        }
    
        $pz_id = $wpdb->insert_id;  // this is the id number of the record we just inserted
    }

	
	// redirectURL is set as a block attribute, but is written into the form as a hidden variable,
	// which is how we have it here. 
	wp_redirect( $_POST['redirectURL']);
	exit;
}

function do_request_type_delete() {
	global $wpdb;

	$wpdb->delete( 'wp_pz_request_type', array('slug' => $_POST['slug']));
	wp_redirect( '/');
	exit;
}