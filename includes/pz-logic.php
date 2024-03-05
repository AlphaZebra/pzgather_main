<?php

/**
 * pz_logic render function
 */



function pz_logic($attributes) {
	$field_value = "nope";
	$shortstr = "[" . $attributes['field'] . "]";
	$field_value = do_shortcode("[" . $attributes['field'] . "]");
	
	if( $attributes['comparison'] == '=') {
		if( $field_value == $attributes['targetValue']) {
			return ( $attributes['displayText']);
		}
	}
	if( $attributes['comparison'] == '!=') {
		if( $field_value != $attributes['targetValue']) {
			return ( $attributes['displayText']);
		}
	}
	if( $attributes['comparison'] == '>') {
		if( $field_value > $attributes['targetValue']) {
			return ( $attributes['displayText']);
		}
	}
	if( $attributes['comparison'] == '>=') {
		if( $field_value >= $attributes['targetValue']) {
			return ( $attributes['displayText']);
		}
	}
	if( $attributes['comparison'] == '<') {
		if( $field_value < $attributes['targetValue']) {
			return ( $attributes['displayText']);
		}
	}
	if( $attributes['comparison'] == '<=') {
		if( $field_value <= $attributes['targetValue']) {
			return ( $attributes['displayText']);
		}
	}
	return "";
	
}