<?php
require dirname(__FILE__).'/Art.php';

// data samples

$post = array(
	"title" => "test title",
	"unused_field" => "lorem ipsum"
);
$default = array(
	"line" => "N",
	"title" => "",
	"content" => "",
	"created_at" => date( "Y-m-d" )
);
$rules1 = array(
	"title" => "required",
);
$rules2 = array(
	"title" => "required",
	"content" => "required",
);
$messages1 = array(
	"title" => "title is required",
);
$messages2 = array(
	"title" => "title is required",
	"content" => "content is required",
);

// init Art

echo "<h2>php-art tests</h2>";

$art = new Art();

// filter data

$data = $art->filter( $post, $default );

echo "<pre>";
var_export( $data );
echo "</pre>";

$data = array_merge( $default, $post );
$data = $art->filter( $data, $default );

echo "<pre>";
var_export( $data );
echo "</pre>";

// validate data

if ( $art->validate( $data, $rules1 ) ) {
	echo "<p>rules 1 valid</p>";
} else {
	$errors = $art->errors( $messages1 );
	echo "<p>errors with rules 1:</p>";
	echo "<pre>";
	var_export( $errors );
	echo "</pre>";
}

if ( $art->validate( $data, $rules2 ) ) {
	echo "<p>rules 2 valid</p>";
} else {
	$errors = $art->errors( $messages2 );
	echo "<p>errors with rules 2:</p>";
	echo "<pre>";
	var_export( $errors );
	echo "</pre>";
}

$rules_combined = array(
	"email" => array(
		"required" => true,
		"email" => true
	),
);
$messages_combined = array(
	"email" => array(
		"required" => "email is required",
		"email" => "need true email"
	),
);
if ( !$art->validate( array(), $rules_combined ) ) {
	$errors = $art->errors( $messages_combined );
	echo "<pre>";
	var_export( $errors );
	echo "</pre>";
}

if ( !$art->validate( array( "email" => "johndoe" ), $rules_combined ) ) {
	$errors = $art->errors( $messages_combined );
	echo "<pre>";
	var_export( $errors );
	echo "</pre>";
}
