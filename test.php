<?php
require dirname(__FILE__).'/Art.php';

echo "<h1>php-art tests</h1>";

Art::rule( "restrict", function( $data ) {
	return in_array( $data, array( "john", "doe" ) );
});

$art = new Art();

$post = array(
	"name" => "bob"
);
$defaults = array(
	"name" => "",
	"email" => "john.doe@mail.com"
);
$rules = array(
	"name" => "restrict",
	"email" => "required"
);
$messages = array(
	"name" => "you are not John Doe",
	"email" => "email?"
);

echo "<pre>";
echo '$post = ';
var_export( $post );
echo "</pre>";

echo "<pre>";
echo '$defaults = ';
var_export( $defaults );
echo "</pre>";

echo "<pre>";
echo '$rules = ';
var_export( $rules );
echo "</pre>";

echo "<pre>";
echo '$messages = ';
var_export( $messages );
echo "</pre>";


// without context

echo "<hr>";

// $data = $art->data( $post, $defaults );

if ( $art->validate( $art->data( $post, $defaults ), $rules ) ) {
	echo "You ARE John Doe!";
} else {
	echo "ERROR:";
	echo "<pre>";
	var_export( $art->errors( $messages ) );
	echo "</pre>";
}

// with context

echo "<hr>";

Art::context( "context", $defaults, $rules, $messages );

$model = $art->model( "context", array( "name" => "bob" ) );

echo "<pre>";
var_export( $model );
echo "</pre>";


$model = $art->model( "context", array( "name" => "doe" ) );

echo "<pre>";
var_export( $model );
echo "</pre>";


$model = $art->model( "context" );

echo "<pre>";
var_export( $model );
echo "</pre>";
