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

$rules1 = array(
	"title" => "required",
);
$messages1 = array(
	"title" => "title is required",
);
if ( $art->validate( $data, $rules1 ) ) {
	echo "<p>rules 1 valid</p>";
} else {
	$errors = $art->errors( $messages1 );
	echo "<p>errors with rules 1:</p>";
	echo "<pre>";
	var_export( $errors );
	echo "</pre>";
}

$rules2 = array(
	"title" => "required",
	"content" => "required",
);
$messages2 = array(
	"title" => "title is required",
	"content" => "content is required",
);
if ( $art->validate( $data, $rules2 ) ) {
	echo "<p>rules 2 valid</p>";
} else {
	$errors = $art->errors( $messages2 );
	echo "<p>errors with rules 2:</p>";
	echo "<pre>";
	var_export( $errors );
	echo "</pre>";
}

$rules = array(
	"email" => array(
		"required" => true,
		"email" => true
	),
);
$messages = array(
	"email" => array(
		"required" => "email is required",
		"email" => "need true email"
	),
);
if ( !$art->validate( array(), $rules ) ) {
	$errors = $art->errors( $messages );
	echo "<pre>";
	var_export( $errors );
	echo "</pre>";
}

if ( !$art->validate( array( "email" => "johndoe" ), $rules ) ) {
	$errors = $art->errors( $messages );
	echo "<pre>";
	var_export( $errors );
	echo "</pre>";
}

// add validation rule

$art->rule( "restrict", function( $data ) {
	return in_array( $data, array( "john", "doe" ) );
});

$rules = array(
	"name" => "restrict"
);
$messages = array(
	"name" => "you are not John Doe"
);
if ( !$art->validate( array( "name" => "bob" ), $rules ) ) {
	echo "<pre>";
	var_export( $art->errors( $messages ) );
	echo "</pre>";
}

// with params

$art->rule( "only", function( $data, $params ) {
	return in_array( $data, $params );
});

$rules = array(
	"name" => array(
		"only" => array( "bob", "marley" )
	)
);
$messages = array(
	"name" => array(
		"only" => "you are not Bob Marley"
	)
);
if ( !$art->validate( array( "name" => "doe" ), $rules ) ) {
	echo "<pre>";
	var_export( $art->errors( $messages ) );
	echo "</pre>";
}
