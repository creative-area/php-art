Art
===

__Art__ (Active Records Tools) is a standalone __php 5.3+__ helper class. It can be used to manipulate simple models.

*version: 0.0.1*

## How to use it

### create a new Art

	$art = new Art();

### filter 

You can filter data with `filter( ArrayAssoc $data, ArrayAssoc $filter )`

Example

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

If you want to inititalize data with default values, use `defaults( ArrayAssoc $data, ArrayAssoc $defaults )` before filter

	$data = $art->defaults( $post, $defaults );
    $data = $art->filter( $data, $defaults );
    
	// result
	array (
		'line' => 'N',
		'title' => 'test title',
		'content' => '',
		'created_at' => '2013-09-07',
	)

If you want to both inititalize data with default values and filter data, use `data( ArrayAssoc $data, ArrayAssoc $defaults )` (same result as above)

	$data = $art->data( $post, $defaults );
    
	// result
	array (
		'line' => 'N',
		'title' => 'test title',
		'content' => '',
		'created_at' => '2013-09-07',
	)

### validate and error messages

And you can validate data with `validate( ArrayAssoc $data, ArrayAssoc $rules )` (return *Boolean*) and get errors messages with `errors( ArrayAssoc $data, ArrayAssoc $messages )` (return *ArrayAssoc*)

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

You can __combine rules__ and the associated error messages

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

### add validation rules

You can __add a rule__ with `rule( String $key, Function $callback )` method. The `$callback` function take 2 arguments: `$value` and `$params` and must return a *Boolean*

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
	
	// result
	array (
		'name' => 'you are not John Doe',
	)

An example with rule __parameter__

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
	
	// result
	array (
		'name' => 'you are not Bob Marley',
	)