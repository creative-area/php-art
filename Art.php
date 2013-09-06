<?php 
// Active Records Tools

class Art {
	
	private $_errors = array();

	private $_rules = array();
	
	function __construct( $options = array() ) {
		// init default validation rules
		$this->_rules[ "required" ] = function( $data, $params ) {
			return !empty( $data );
		};
		$this->_rules[ "email" ] = function( $data, $params ) {
			return filter_var( $data, FILTER_VALIDATE_EMAIL );
		};
		$this->_rules[ "date" ] = function( $data, $params ) {
			return ( date( "Y-m-d", strtotime( $data ) ) == $data );
		};
	}

	public function rule( $rule, $callback ) {
		$this->_rules[ $rule ] = $callback;
	}

	public function valide( $data, $rule, $params = false ) {
		if ( isset( $this->_rules[ $rule ] ) ) {
			return $this->_rules[ $rule ]( $data, $params );
		}
		return true;
	}

	public function filter( $data, $fields ) {
		return array_intersect_key( $data, $fields );
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
}
