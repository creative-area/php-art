<?php 
// Active Records Tools

class Art_Model {

	public $defaults = array();

	public $rules = array();

	public $messages = array();

	private $_data = array();

	private $_valid = false;

	private $_errors = array();
}

class Art {
	
	private $_errors = array();

	// TODO: use to be chainable
	private $_data = array();

	private static $_rules = array();

	private static $_contexts = array();
	
	function __construct( $options = array() ) {
		// init default validation rules
		self::$_rules[ "required" ] = function( $data, $params ) {
			return !empty( $data );
		};
		self::$_rules[ "email" ] = function( $data, $params ) {
			return filter_var( $data, FILTER_VALIDATE_EMAIL );
		};
		self::$_rules[ "date" ] = function( $data, $params ) {
			return ( date( "Y-m-d", strtotime( $data ) ) == $data );
		};
	}

	public static function rule( $rule, $callback ) {
		self::$_rules[ $rule ] = $callback;
	}

	public function valide( $data, $rule, $params = false ) {
		if ( isset( self::$_rules[ $rule ] ) ) {
			$fct = self::$_rules[ $rule ];
			return $fct( $data, $params );
		}
		return true;
	}

	public function filter( $data, $fields ) {
		return array_intersect_key( $data, $fields );
	}

	public function defaults( $data, $defaults ) {
		return array_merge( $defaults, $data );
	}

	public function data( $data, $defaults ) {
		// defaults and filter
		return $this->filter( $this->defaults( $data, $defaults ), $defaults );
	}
	
	public function validate( $data, $rules, $filter = false ) {
		$this->_errors = array();
		
		if ( $filter ) $data = $this->filter( $data, $rules );
		
		foreach ( $rules as $field => $rule ) {
			if ( isset( $data[ $field ] ) ) {
				if ( is_array( $rule ) ) {
					foreach ( $rule as $_rule => $params ) {
						if ( !$this->valide( $data[ $field ], $_rule, $params ) ) {
							array_push( $this->_errors, array( $field, $_rule ) );
						}
					}
				} else {
					if ( !$this->valide( $data[ $field ], $rule ) ) {
						array_push( $this->_errors, array( $field, $rule ) );
					}
				}
			} else {
				array_push( $this->_errors, array( $field, "required" ) );
			}
		}
		return ( sizeof( $this->_errors ) == 0 );
	}
	
	public function errors( $messages = array() ) {
		$errors = array();
		foreach ( $this->_errors as $_e ) {
			$field = $_e[ 0 ];
			$rule = $_e[ 1 ];
			if ( isset( $messages[ $field ] ) ) {
				$message = $messages[ $field ];
				if ( is_array( $message ) ) {
					$message = ( isset( $message[ $rule ] ) ) ? $message[ $rule ] : "Required";
				}
			} else {
				$message = "Required";
			}
			$errors[ $field ] = $message;
		}
		return $errors;
	}

	/*
	add a context

	`context( $context_name, $params )`
	$context_name = "context_key"
	$params = array(
		"field_name" => array(
			"defaults" => "default_value",
			"rules" => "rule", // String or ArrayAssoc
			"message" => "error_message", // String or ArrayAssoc
			"custom_key" => "custom_value", // For example: "label" => "field_label"
			...
		),
		...
	)

	`context( $context_name, $defaults, $rules, $messages )`
	$context_name = "context_key"
	$defaults = array(
		"field_name" => "default_value",
		...
	)
	$rules = array(
		"field_name" => "rule", // String or ArrayAssoc
		...
	)
	$messages = array(
		"field_name" => "error_message", // String or ArrayAssoc
		...
	)
	*/
	static public function context( $context_name, $defaults, $rules = false, $messages = false ) {
		self::$_contexts[ $context_name ] = array(
			"defaults" => $defaults,
			"rules" => $rules,
			"messages" => $messages
		);
	}

	public function model( $context_name, $data = array() ) {
		$processed_data = $this->data( $data, self::$_contexts[ $context_name ][ "defaults" ] );
		$validated = $this->validate( $processed_data, self::$_contexts[ $context_name ][ "rules" ] );

		$model = array(
			"data" => $processed_data,
			"valid" => $validated,
			"errors" => $this->errors( self::$_contexts[ $context_name ][ "messages" ] )
		);

		return $model;
	}
}
