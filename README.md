Art
===

__Art__ (Active Records Tools) is a standalone __php 5.3+__ helper class. It can be used to manipulate simple models.

*version: draft*

## How to use it

Create a new Art :

	$art = new Art();

You can __filter()__ data (for example `$_POST` data).

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
	
    $data = $art->filter( $post, $default );
	
	// result
	array (
		'title' => 'test title',
	)

If you want to inititalize data with default values, just do

	$data = array_merge( $default, $data )
    $data = $art->filter( $data, $default );
    
	// result
	array (
		'line' => 'N',
		'title' => 'test title',
		'content' => '',
		'created_at' => '2013-09-07',
	)

And you can __validate()__ data (return *Boolean*) and get __errors()__

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
	
	// result
	rules 1 valid
	
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
	
	// result
	errors with rules 2:
	array (
		'content' => 'content is required',
	)

You can combine rules

	$rules = array(
		"email" => array(
			"required": true,
			"email": true
		),
	);
	$messages = array(
		"email" => array(
			"required": "email is required",
			"email": "need true email"
		),
	);
	
	if ( !$art->validate( array(), $rules_combined ) ) {
		$errors = $art->errors( $messages_combined );
		echo "<pre>";
		var_export( $errors );
		echo "</pre>";
	}
	// result
	array (
		'email' => 'email is required',
	)

	if ( !$art->validate( array( "email" => "johndoe" ), $rules_combined ) ) {
		$errors = $art->errors( $messages_combined );
		echo "<pre>";
		var_export( $errors );
		echo "</pre>";
	}
	// result
	array (
		'email' => 'need true email',
	)

