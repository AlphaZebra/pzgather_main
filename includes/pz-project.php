<?php

function pz_project_list() {
    global $wpdb;
    //wp_enqueue_script('pztask', plugin_dir_url(__FILE__) . 'build/blocks/pz_task_list/pztask.js', array('wp-element', 'wp-components'), null, true);
    

    $limit = 10;
    $offset = 0;


	$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_project ", ARRAY_A );
	if( !isset($results[0])) {
		$offset=0;
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pz_project LIMIT $limit OFFSET $offset ", ARRAY_A );
	};
	
    ob_start();

    ?>
    
    <table class="pz-table-style" >
		
		<thead>
			<th>Edit</th>
			<th>Project</th>
			<th>Status</th>
			
		</thead>

	<?php
      if(!isset($results[0])) {
        echo "<tr><td>No projects found.</td></tr>";
    } else {
        foreach($results as $result) {
            echo "<tr><td>edit </td><td>";
            echo $result['project_name'] . '</td><td>';
            echo $result['project_status'] . '</td></tr>';
            
        }
    }
    

	
	?>

	
	</table>
	<?php 

	return ob_get_clean();


}