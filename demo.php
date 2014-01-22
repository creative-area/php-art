<?php
require dirname(__FILE__).'/Art.php';

echo "<h1>php-art - demo</h1>";

// data samples

echo "<h2>data samples</h2>";

$post = array(
	"title" => "test title",
	"unused_field" => "lorem ipsum"
);
$defaults = array(
	"line" => "N",
	"title" => "",
	"content" => "",
	"created_at" => date( "Y-m-d" )
);

echo "<pre>";
echo '$post = ';
var_export( $post );
echo "</pre>";

echo "<pre>";
echo '$defaults = ';
var_export( $defaults );
echo "</pre>";

// init Art

echo "<h2>init Art()</h2>";

$art = new Art();

echo "<pre>";
echo '$art = new Art()';
echo "</pre>";

// change data

echo "<h2>change data</h2>";

// filter() data

echo "<h3>filter() data</h3>";

echo "<pre>";
echo '$data = $art->filter( $post, $defaults );';
echo "</pre>";

$data = $art->filter( $post, $defaults );

echo "<pre>";
echo "// result:\n";
echo '$data = ';
var_export( $data );
echo "</pre>";

// defaults() data

echo "<h3>defaults() data</h3>";

echo "<pre>";
echo '$data = $art->defaults( $post, $defaults );';
echo "</pre>";

$data = $art->defaults( $post, $defaults );

echo "<pre>";
echo "// result:\n";
echo '$data = ';
var_export( $data );
echo "</pre>";

// defaults() and filter() data

echo "<h3>defaults() and filter() data</h3>";

echo "<pre>";
echo '$data = $art->defaults( $post, $defaults );
$data = $art->filter( $data, $defaults );';
echo "</pre>";

$data = $art->defaults( $post, $defaults );
$data = $art->filter( $data, $defaults );

echo "<pre>";
echo "// result:\n";
echo '$data = ';
var_export( $data );
echo "</pre>";

// data() data: defaults() and filter()

echo "<h3>data() data: defaults() and filter()</h3>";

echo "<pre>";
echo '$data = $art->data( $post, $defaults );';
echo "</pre>";

$data = $art->data( $post, $defaults );

echo "<pre>";
echo "// result:\n";
echo '$data = ';
var_export( $data );
echo "</pre>";

// validate data

echo "<h2>validate data</h2>";

echo "<pre>";
echo "// use previous processed data:\n";
echo '$data = ';
var_export( $data );
echo "</pre>";


// example 1: valid data

echo "<h3>example 1: valid data</h3>";

$rules1 = array(
	"title" => "required",
);
$messages1 = array(
	"title" => "title is required",
);

echo "<pre>";
echo '$rules1 = ';
var_export( $rules1 );
echo "</pre>";

echo "<pre>";
echo '$messages1 = ';
var_export( $messages1 );
echo "</pre>";

echo "<pre>";
echo 'if ( $art->validate( $data, $rules1 ) ) {
    echo "rules 1 valid";
} else {
    $errors = $art->errors( $messages1 );
    echo = "errors with rules 1:";
    var_export( $errors );
}';
echo "</pre>";

echo "<pre>";
echo "// result:\n";
echo "</pre>";

if ( $art->validate( $data, $rules1 ) ) {
	echo "<p>rules 1 valid</p>";
} else {
	$errors = $art->errors( $messages1 );
	echo "<p>errors with rules 1:</p>";
	echo "<pre>";
	var_export( $errors );
	echo "</pre>";
}

// example 2: invalid data

echo "<h3>example 2: invalid data</h3>";

$rules2 = array(
	"title" => "required",
	"content" => "required",
);
$messages2 = array(
	"title" => "title is required",
	"content" => "content is required",
);

echo "<pre>";
echo '$rules2 = ';
var_export( $rules2 );
echo "</pre>";

echo "<pre>";
echo '$messages2 = ';
var_export( $messages2 );
echo "</pre>";

echo "<pre>";
echo 'if ( $art->validate( $data, $rules2 ) ) {
    echo "rules 2 valid";
} else {
    $errors = $art->errors( $messages2 );
    echo = "errors with rules 2:";
    var_export( $errors );
}';
echo "</pre>";

echo "<pre>";
echo "// result:\n";
echo "</pre>";

if ( $art->validate( $data, $rules2 ) ) {
	echo "<p>rules 2 valid</p>";
} else {
	$errors = $art->errors( $messages2 );
	echo "<p>errors with rules 2:</p>";
	echo "<pre>";
	var_export( $errors );
	echo "</pre>";
}

// example 3: multi rules

echo "<h3>example 3: multi rules</h3>";

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

echo "<pre>";
echo '$rules = ';
var_export( $rules );
echo "</pre>";

echo "<pre>";
echo '$messages = ';
var_export( $messages );
echo "</pre>";

echo "<pre>";
echo 'if ( !$art->validate( array(), $rules ) ) {
    $errors = $art->errors( $messages );
    var_export( $errors );
}';
echo "</pre>";

echo "<pre>";
echo "// result:\n";
echo "</pre>";

if ( !$art->validate( array(), $rules ) ) {
	$errors = $art->errors( $messages );
	echo "<pre>";
	var_export( $errors );
	echo "</pre>";
}

echo "<pre>";
echo 'if ( !$art->validate( array( "email" => "johndoe" ), $rules ) ) {
    $errors = $art->errors( $messages );
    var_export( $errors );
}';
echo "</pre>";

echo "<pre>";
echo "// result:\n";
echo "</pre>";

if ( !$art->validate( array( "email" => "johndoe" ), $rules ) ) {
	$errors = $art->errors( $messages );
	echo "<pre>";
	var_export( $errors );
	echo "</pre>";
}

// add custom validation rule

echo "<h3>add validation rule</h3>";

$art::rule( "restrict", function( $data ) {
	return in_array( $data, array( "john", "doe" ) );
});

echo "<pre>";
echo '$art::rule( "restrict", function( $data ) {
    return in_array( $data, array( "john", "doe" ) );
});';
echo "</pre>";

$rules = array(
	"name" => "restrict"
);
$messages = array(
	"name" => "you are not John Doe"
);

echo "<pre>";
echo '$rules = ';
var_export( $rules );
echo "</pre>";

echo "<pre>";
echo '$messages = ';
var_export( $messages );
echo "</pre>";

echo "<pre>";
echo 'if ( !$art->validate( array( "name" => "bob" ), $rules ) ) {
    var_export( $art->errors( $messages ) );
}';
echo "</pre>";

echo "<pre>";
echo "// result:\n";
echo "</pre>";

if ( !$art->validate( array( "name" => "bob" ), $rules ) ) {
	echo "<pre>";
	var_export( $art->errors( $messages ) );
	echo "</pre>";
}

// add custom validation rule with params

echo "<h3>add custom validation rule with params</h3>";

$art::rule( "only", function( $data, $params ) {
	return in_array( $data, $params );
});

echo "<pre>";
echo '$art::rule( "only", function( $data, $params ) {
    return in_array( $data, $params );
});';
echo "</pre>";

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

echo "<pre>";
echo '$rules = ';
var_export( $rules );
echo "</pre>";

echo "<pre>";
echo '$messages = ';
var_export( $messages );
echo "</pre>";

echo "<pre>";
echo 'if ( !$art->validate( array( "name" => "doe" ), $rules ) ) {
    var_export( $art->errors( $messages ) );
}';
echo "</pre>";

echo "<pre>";
echo "// result:\n";
echo "</pre>";

if ( !$art->validate( array( "name" => "doe" ), $rules ) ) {
	echo "<pre>";
	var_export( $art->errors( $messages ) );
	echo "</pre>";
}

echo "<pre>";
echo 'if ( $art->validate( array( "name" => "marley" ), $rules ) ) {
    echo "You ARE Bob Marley!";
} else {
    var_export( $art->errors( $messages ) );
}';
echo "</pre>";

echo "<pre>";
echo "// result:\n";
echo "</pre>";

if ( $art->validate( array( "name" => "marley" ), $rules ) ) {
	echo "You ARE Bob Marley!";
} else {
	echo "<pre>";
	var_export( $art->errors( $messages ) );
	echo "</pre>";
}
