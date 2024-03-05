<?php

add_action('admin_post_do-tag-delete', 'do_tag_delete');
add_action('admin_post_nopriv_do-tag-delete', 'do_tag_delete');

/**
 * pz_tag_delete form
 */

function pz_tag_delete($attributes) {
	global $wpdb;

	// get the list of possible tags from configuration
	// if( $tag_list = pz_get_config( 'pz_person_tags') == -1 ) {
	// 	$tag_list = "";
	// } else {
	// 	$tag_array = explode(' ', $tag_list ); 
	// }
	
	
	$tag_array = explode(',', trim($tag_list = pz_get_config('pz_person_tags')) ); 
	$i=0;
	foreach($tag_array as $tag ) {

		if( $tag != '') {
			$temp[$i] = trim($tag, " ,\n\r\t\v\x00");
			$i++;
		}
	}
	$tag_array = $temp;
	$i = 0;

	// Let the form creation begin! 
	ob_start();
	?>
	
	<form action="<?php echo esc_url(admin_url('admin-post.php')) ?>" method="POST" class="form-style-1">
		<input type="hidden" name="action" value="do-tag-delete" required>
		
		<label>Interest tags</label>
		<fieldset style="margin-top: 10px">
		<legend>Check any tags you'd like to DELETE:</legend>
		<table><tr><td>
				
				<?php 
				foreach( $tag_array as $tag ) { 
				?>
				<div>
					<label for="<?php echo $tag ?>"><?php echo $tag ?></label>
					<input type="checkbox" id="<?php echo $tag ?>" name="<?php echo $tag ?>"  
					 />
				</div>
				<?php  
					$i++; 
					if( $i > 6 ) {
						echo "</td><td style='vertical-align: top'>";
						$i = 1;
					}
				} 
				echo "</td></tr></table>";
				?>
				
			</fieldset>
			<p >
		<input type="text" name="pz_tags" id="pz_tags" class="field-long" value="" placeholder="new tag(s) separated by a space" />

				<input type="submit" value="Save Modifications" />

	
	<?php
	return ob_get_clean();
}





function do_tag_delete () {
	global $wpdb;

	// get the list of possible tags from configuration
	// if( $tag_list = pz_get_config( 'pz_person_tags') == -1 ) {
	// 	$tag_list = "";
	// }
	$tag_list = trim(pz_get_config( 'pz_person_tags'));

	
	// post array, beginning with element 1, contains name values of boxes that were checked (on)
	// so these are what need to be removed from tag options.
	foreach( $_POST as $post_key => $post_item ) {
		if( $post_key == ' ') ;
		else if( $post_key == 'action') ;
		else if( $post_key == 'pz_tags') ;
		else {
			var_dump($post_key);
			var_dump($tag_list);
			if( str_contains( $tag_list, $post_key)) {
				$tag_list = str_replace( $post_key, '', $tag_list );
				var_dump($tag_list);
			}
		}
		
	}
	$tag_list = $tag_list . ' ' . $_POST['pz_tags'];
	
	pz_set_config( 'pz_person_tags', $tag_list );
	// put modified tag string back into configuration table

	// wp_redirect( $_POST['listURL'] . '?prj=' . $item['project_id']);
	exit;
  }