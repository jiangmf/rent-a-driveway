<?php
function validatePatterns(&$errors, $field_list, $field_name, $pattern) {
	if (!isset($field_list[$field_name]) || $field_list[$field_name] == "") {
		$errors[$field_name] = 'Required';
	} else if (!preg_match($pattern, $field_list[$field_name])) {
		$errors[$field_name] = 'Invalid';
	}
}
?>