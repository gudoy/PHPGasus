<?php

# Security
ini_set('register_globals', 0); 							//
ini_set('magic_quotes_gpc', 0); 							//
ini_set('magic_quotes_runtime', 0); 						//
ini_set('magic_quotes_sybase', 0); 							//

# Dev/Debug
ini_set('xdebug.var_display_max_children', 1024); 			//
ini_set('xdebug.var_display_max_data', 99999); 				//
ini_set('xdebug.var_display_max_depth', 6); 				//
ini_set('xdebug.max_nesting_level', 500); 					// default is 100, which can be cumbersome


/* 
errors
	key: 'MISSING_REQUIRED_FIELD',
	summary': 'Missing required fields: %s'
	message': 'Please pass the following missing fields: %s'
	actions:
		primary:
		secondary:
		secondary:
		...
*/

$_errors = array(

	// CONTENT/REQUEST VALIDATION
	'MISSING_PARAM' 			=> array('msg' => _('Missing required parameter'), 'args' => array('name')),
	'MISSING_PARAMS' 			=> array('msg' => _('Missing required parameters'), 'args' => array('names')),
	'MISSING_FIELD' 			=> array('msg' => _('Missing required field'), 'args' => array('name')),
	'MISSING_FIELDS' 			=> array('msg' => _('Missing required fields'), 'args' => array('names')),
	
	'INVALID_CONDITION_FORMAT' 	=> array('msg' => _('Invalid condition format'), 'args' => array('key', 'value')),

	// DATABASE
	'DB_CONNEXION_ERROR' 		=> array('msg' => _('We could not connect to the database. Please retry later.'), 'args' => array('error')),
	'DB_SELECTION_ERROR' 		=> array('msg' => _('We could not connect to the database. Please retry later.'), 'args' => array('error')),
	'DB_QUERY_ERROR' 			=> array('msg' => _('An error occured on the database.')),

	// SECURITY
	'UNAUTHORIZED' 				=> array('msg' => _('You are not allowed to perform this action.')),
	'UNAUTHORIZED_SECTION' 		=> array('msg' => _('You don\'t have the permissions to access this page/section.')),
	'INVALID_CSRF_TOKEN' 		=> array('We think you may not really wanted to do this. If we are wrong, please retry.'),
	
	
	/* 
	'4020' => array('back' => 'Database query syntax error',
					'front' => _('There\'s a syntax error in a database request.')),
	'4030' => array('back' => 'Creation error due to unique key constraint',
					'front' => _('The resource(s) could not be created because there\'s already an unique record with passed data association(s).')),
	'4050' => array('back' => 'Creation/update error due to fk constraint(s)',
					'front' => _('The resource(s) could not be created/updated because one or more required field(s) are missing/empty.')),
	'4061' => array('back' => 'Column(s) cannot be null',
					'front' => _('The resource(s) could not be created because one or more field(s) have not been filled.')),
	'4080' => array('back' => '%s',
					'front' => _('Your query contains one or more unknown column(s)')),
	'4081' => array('back' => 'Unknown column: %s',
					'front' => _('Your query contains one or more unknown column(s): %s')),
	'4100' => array('back' => 'DB error message: %s',
					'front' => _('Returned database error message: %s')),
	'4110' => array('back' => 'Deletion error due to fk constraint(s)',
					'front' => _('The resource(s) could not be deleted because other resource(s) are pointing to it (them). Please update/delete sibling resources first.')),
	'4150' => array('back' => 'Tables alias conflict.',
					'front' => _('Some resources seems to use the same alias, creating a conflict.')),
					
	'4210' => array('back' => 'Wrong condition format: %s',
					'front' => _('Wrong condition format. Expected: field, [operator], $values. Received: %s')),
	'4213' => array('back' => 'Unknown condition field/column: %s',
					'front' => _('Unknown condition field/column: %s')),
	'4215' => array('back' => 'Unknown condition operator: %s',
					'front' => _('Unknown condition operator: %s')),
	'4220' => array('back' => 'Invalid values for condition: %s',
					'front' => _('Expected an unique value for the passed condition field/operator couple: %s/%s')),
					
	'5000' => array('back' => 'Common error.',
					'front' => _('An error occured during the process, please retry later')),
					
	'6050' => array('back' => 'Password update error.',
					'front' => _('You are not allowed to edit the password of this user.')),
	'6100' => array('back' => 'Unauthorised uploaded filetype.',
					'front' => _('The filetype of (one of) the uploaded file(s) is not an authorised filetype.')),
	'6110' => array('back' => 'File upload error.',
					'front' => _('An error occured during the file upload.')),

	'10000' => array('back' => 'Missing required fields',					// Deprecated: use 1002 instead
					'front' => _('Please fill all the required fields')), 	// Deprecated: use 1002 instead
	'10001' => array('back' => 'Incorrect email',
					'front' => _('This email is not correct')),
	'10002' => array('back' => 'Unknown email',
					'front' => _('Invalid email and/or password')),
	'10003' => array('back' => 'Password not correct',
					'front' => _('Invalid email and/or password')),
	'10004' => array('back' => 'Passwords not matching',
					'front' => _('The password and it\'s confirmation are not identical')),
	'10005' => array('back' => 'Account not confirmed',
					'front' => _('Your account has not already been confirmed.')),
	'10006' => array('back' => 'Incorrect login format',
					'front' => _('Login not correct. Only letters or numerics (3 to 32 characters) are allowed.')),
	'10007' => array('back' => 'Incorrect password format',
					'front' => _('Password not correct. Only letters and numbers are allowed.')),
	'10008' => array('back' => 'Incorrect email format',
					'front' => _('Email address not valid.')),
	'10009' => array('back' => 'Incorrect captcha format',
					'front' => _('Anti-spam filter not correct. Only numerics are allowed.')),
	'10010' => array('back' => 'Incorrect captcha value',
					'front' => _('Anti-spam filter value not correct. Figure of 2 numerics expected.')),
	'10011' => array('back' => 'Terms of use not agreeded',
					'front' => _('You have to accept the Terms of Use.')),

	'10013' => array('back' => 'Birth year not valid',
					'front' => _('The birth year you entered is not correct.')),
	'10014' => array('back' => 'Incorrect old password format',
					'front' => _('Old password not correct. Only letters and numbers are allowed.')),
	'10015' => array('back' => 'Incorrect new password format',
					'front' => _('New password not correct. Only letters and numbers are allowed.')),
	'10016' => array('back' => 'Wrong current password',
					'front' => _('Your current password is not valid')),
	'10017' => array('back' => 'Missing reset password key in database',
					'front' => _('You are not allowed reset your password. Please use the \'lost password?\' feature first.')),
	'10018' => array('back' => 'Wrong security reset password key',
					'front' => _('The security key you are using is the expected one. Return to the \'lost password?\' page to get a new mail with a new reset password link.')),
	'10020' => array('back' => 'Login already exists',
					'front' => _('Login already existing. Choose another one.')),
	'10021' => array('back' => 'Email already exists',
					'front' => _('Email already existing. Choose another one.')),
	'10022' => array('back' => 'Incorrect firstname format',
					'front' => _('Only letters and dashes are allowed (1 to 32 characters).')),
	'10023' => array('back' => 'Incorrect firstname format',
					'front' => _('Only letters and dashes are allowed (1 to 32 characters).')),
	'10024' => array('back' => 'Incorrect laststname format',
					'front' => _('Only letters and dashes are allowed.')),
	'10030' => array('back' => 'Too many login attemps',
					'front' => _('You have reached the max unsucessful login attemps number. For security issue, you won\'t be able to login during ' . _APP_MAX_LOGIN_ATTEMPTS_BAN_TIME . ' minutes.')),
	'10031' => array('back' => 'Failed login attemp',
					'front' => _('Remaining login attemps before this account to be blocked: %')),
	'10032' => array('back' => 'Account blocked',
					'front' => _('Your account has been blocked. Please contact us by email: %')),
	'10040' => array('back' => 'WS Invalid login/password',
					'front' => _('Invalid login and/or password')),
	'10050' => array('back' => 'Parental codes not matching',
					'front' => _('The parental code and it\'s confirmation are not identical')),
	'10051' => array('back' => 'ParentalCodeOld not correct',
					'front' => _('Your current parental code is not valid.')),
	'10052' => array('back' => 'ParentalCode not correct',
					'front' => _('Invalid parental code')),
					
	'10100' => array('back' => 'Session expired',
					'front' => _('Your session has expired. Please login again.'),
					'buttons' => array(array(
						'label' => _('Re-connect'),
						'href' 	=> _URL_LOGIN,
						'id' 	=> 'errorReconnectBtn',
					))),
	'10101' => array('back' => 'Session corrupted',
				'front' => _('Your session has been corrupted. Please login again.')),
					
	'10200' => array('back' => 'Unknown user',
					'front' => _('This user does not seem to be registered')),
					
	'10310' => array('back' => 'Incorrect creditCardOwnerName format',
					'front' => _('Credit card owner name not correct. Only letters and spaces (3 to 64 characters).')),
	'10315' => array('back' => 'Incorrect creditCardCardNumber value',
					'front' => _('Credit card number value not correct. Figure of 16 numerics expected.')),
	'10320' => array('back' => 'Incorrect creditCardCryptogram value',
					'front' => _('Credit card cryptogram value not correct. Figure of 3 numerics expected.')),

	// TODO: change rule when decided
					
	'11000' => array('back' => 'No matching search result',
					'front' => _('Your search didn\'t return any result.')),
					
	'12000' => array('back' => 'WS call exception',
					'front' => _('An error occured during the request. Please retry later.')),
					
	'13000' => array('back' => 'Payment declined',
					'front' => _('The payment has been declined.')),
					
					
	'15010' => array('back' => 'Controller folder unwritable.',
					'front' => _('The file %s could not be created. The parent folder is not writable.')),
					
	### APP SPECIFICS ###
	// Required params
	'20010' => array('back' => 'Missing email',
					'front' => _('Please provide an email.')),
	'20012' => array('back' => 'Missing password',
					'front' => _('Please provide a password.')),
	'20016' => array('back' => 'Missing first_name',
					'front' => _('Please provide a first_name.')),
	'20017' => array('back' => 'Missing last_name',
					'front' => _('Please provide a last_name.')),
	'20020' => array('back' => 'Missing address',
					'front' => _('Please provide an address.')),
	'20021' => array('back' => 'Missing country',
					'front' => _('Please provide a country.')),
	'20022' => array('back' => 'Missing city',
					'front' => _('Please provide a city.')),
	'20023' => array('back' => 'Missing zipcode',
					'front' => _('Please provide a zipcode.')),
	'20051' => array('back' => 'Missing application id',
					'front' => _('Please select an application.')),
	'20052' => array('back' => 'Missing application version id',
					'front' => _('Please select an application version.')),
	'20053' => array('back' => 'Missing file environment',
					'front' => _('Please select a file environment.')),
	'20054' => array('back' => 'Missing product pack id',
					'front' => _('Please select a product pack.')),
	'20055' => array('back' => 'Missing device id',
					'front' => _('Please select a device.')),
	'20056' => array('back' => 'Missing message id',
					'front' => _('Please select/provide a message.')),
	'20057' => array('back' => 'Missing coupon coode',
					'front' => _('Please provide a coupon code.')),
	'20058' => array('back' => 'Missing product id',
					'front' => _('Please provide product id.')),
	'20061' => array('back' => 'Missing token',
					'front' => _('Please provide a token.')),
	'20080' => array('back' => 'Missing recipient_email',
					'front' => _('Please provide a recipient email.')),
	'20081' => array('back' => 'Missing msg_object',
					'front' => _('Please provide a message object.')),
	'20082' => array('back' => 'Missing msg_text',
					'front' => _('Please provide a message text.')),
					
	
	// Coupon codes
	'20100' => array('back' => 'User already requested a free coupon code',
					'front' => _('Sorry but you\'re not allowed to request more than 1 free coupon code')),
	'20200' => array('back' => 'Already existing promotion code',
					'front' => _('This promotion code already exists')),
	'20201' => array('back' => 'One or more already existing promotion code(s)',
					'front' => _('One or several of this promotion code(s) seems to already exist. Please chose another name prefix.')),
	'20202' => array('back' => 'All already existing promotion codes',
					'front' => _('All this promotion codes seems to already exist. Please chose another name prefix.')),
	'20205' => array('back' => 'Invalid coupon code',
					'front' => _('This coupon code is not valid.')),
	'20210' => array('back' => 'Coupon not allowed for device id',
					'front' => _('You cannot use couponcodes you have shared.')),

	'20260' => array('back' => 'Already registered device',
					'front' => _('This device seems to already have registered for this app\'s push messages')),
					
	'20300' => array('back' => 'Already purchased content',
					'front' => _('This device seems to already have purchased this content')),
	'20301' => array('back' => 'Already purchased content [account]',
					'front' => _('This account seems to already have purchased this content')),					
	'20310' => array('back' => 'Invalid apple transaction receipt',
					'front' => _('The passed apple transaction receipt is not valid')),
	'20330' => array('back' => 'Productpack not found',
					'front' => _('The product pack could not be found.')),

	'20350' => array('back' => 'No purchased content for this device id',
					'front' => _('There is no purchased content for the passed device id')),

	'20400' => array('back' => 'No remaining sharing rights left',
					'front' => _('This device has no more remaining sharing rights left')),
					
	'20430' => array('back' => 'No shared contents for this device ID',
					'front' => _('There is no shared content for the passed device id')),
					
	'20440' => array('back' => 'Coupon code already used with this device ID.',
					'front' => _('You cannot use this coupon code more than once for this device.')),
	 */
);

?>