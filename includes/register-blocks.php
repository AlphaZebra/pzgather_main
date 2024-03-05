<?php

function pzdata_register() {

    $blocks = [
        // [ 'name' => 'pz_person_subscribe' ],
        // [ 'name' => 'pz_person_grid'],
        [ 'name' => 'pz_person_edit' ],
        // [ 'name' => 'pz_person_list' ],
        [ 'name' => 'pz_interaction' ],
        // [ 'name' => 'pz_inter_list' ],
        [ 'name' => 'pz_page_access' ],
        // [ 'name' => 'pz_task_list' ],
        [ 'name' => 'pz_task_form' ],
        [ 'name' => 'pz_request_type' ],
        [ 'name' => 'pz_request_type_form' ],
        [ 'name' => 'pz_request_detail_form' ],
        [ 'name' => 'pz_queue' ],
        [ 'name' => 'pz_add_person' ],
        [ 'name' => 'pz_logic' ],
        [ 'name' => 'pz_tag_delete' ],
        [ 'name' => 'pz_link_form' ]
        // [ 'name' => 'pz_shortblock' ]
    ];

    foreach($blocks as $block) {
        // if( $block['name'] == 'shortblock') {
        //     $render_prop = array( 'render_callback' => 'theBlockContent');
        // }
        if( $block['name'] == 'pz_person_edit') {
            $render_prop = array( 'render_callback' => 'pz_person_block');
        }
        else if( $block['name'] == 'pz_person_list') {
            $render_prop = array( 'render_callback' => 'pz_person_list');
        }
        else if( $block['name'] == 'pz_interaction') {
            $render_prop = array( 'render_callback' => 'pz_interaction_block');
        }
        else if( $block['name'] == 'pz_inter_list') {
            $render_prop = array( 'render_callback' => 'pz_inter_list');
        }
        else if( $block['name'] == 'pz_add_person') {
            $render_prop = array( 'render_callback' => 'pz_add_person');
        }
        else if( $block['name'] == 'pz_page_access') {
            $render_prop = array( 'render_callback' => 'pz_access_control');
        }
        else if( $block['name'] == 'pz_task_list') {
            $render_prop = array( 'render_callback' => 'pz_task_list');
        }
        else if( $block['name'] == 'pz_task_form') {
            $render_prop = array( 'render_callback' => 'pz_task_form');
        }
        else if( $block['name'] == 'pz_request_type') {
            $render_prop = array( 'render_callback' => 'pz_request_type');
        }
        else if( $block['name'] == 'pz_request_type_form') {
            $render_prop = array( 'render_callback' => 'pz_request_type_form');
        }
        else if( $block['name'] == 'pz_request_detail_form') {
            $render_prop = array( 'render_callback' => 'pz_request_detail_form');
        }
        else if( $block['name'] == 'pz_queue') {
            $render_prop = array( 'render_callback' => 'pz_queue');
        }
        else if( $block['name'] == 'pz_logic') {
            $render_prop = array( 'render_callback' => 'pz_logic');
        }
        else if( $block['name'] == 'pz_tag_delete') {
            $render_prop = array( 'render_callback' => 'pz_tag_delete');
        }
        else if( $block['name'] == 'pz_link_form') {
            $render_prop = array( 'render_callback' => 'pz_link_form');
        }
        else {
            $render_prop = array([]);
        }
            register_block_type(
                PZ_PLUGIN_DIR . 'build/blocks/' . $block['name'],
                $render_prop
            );
        }
}


// function theBlockContent($attributes) {
//     global $pz_personID;
//     if( isset($pz_personID)) {
//         $my_content = '<p>' . $attributes['content'] . '</p>';
//         // $my_content = $attributes['content'];
//         $my_content = do_shortcode( $my_content );
//         return $my_content;
//     }
//     else {
//         $my_content = '<p>' . $attributes['altContent'] . '</p>';
//         return $my_content;
//     }
    
// }

