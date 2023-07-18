

Follow this : 

https://docs.themekraft.com/article/789-how-to-use-invite-codes-in-the-ultimate-member-registration


Pay attention !!! there's a lot of typos on that doc .


1 - Add a custom field with a custom validation , field meta has to be "invite_code" and custom_action too.

2 - Append this to function.php in your theme ( better use a child theme )


/**
 * Validate invite code
 * @param string $key
 * @param attay  $array
 * @param array  $args
 */
function um_custom_validate_invite_code( $key, $array, $args ) {

	if(!function_exists('all_in_one_invite_codes_validate_code')){
		error_log('all_in_one_invite_codes_validate_code do not exist');
		return;
	}

	if ( isset( $args[$key] ) ) {
		error_log("$args[$key] exist $key");
		$result = all_in_one_invite_codes_validate_code( $args[$key], $args['invite_code'], );
		if ( isset( $result['error'] ) ) {
			UM()->form()->add_error( $key, $result['error'] );
		}
	} else {
		error_log('all_in_one_invite_codes_validate_code do not exist');
	}

}
add_action( 'um_custom_field_validation_invite_code', 'um_custom_validate_invite_code', 30, 3 );


3 - Add some invite codes in all in on invite codes


4 - Test register form
