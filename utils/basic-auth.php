<?php
/**
 * Add Basic Authentication to web page
 * 
 * Check from env variable if auth is used. 
 * Username and password are hardcoded in this example.
 * 
 * Env variable can be set to .htaccess file with:
 * SetEnv USE_BASIC_AUTH true
 */
if ( (strpos(getenv('USE_BASIC_AUTH'), 'true') !== false) ) {

	function AuthHeaders() {
		header('WWW-Authenticate: Basic realm="Customer"');
		header('HTTP/1.0 401 Unauthorized');
		exit;
	}
	if (!isset($_SERVER['PHP_AUTH_USER'])) {
		AuthHeaders();
	} else {
		if ( $_SERVER['PHP_AUTH_USER'] != 'admin' || $_SERVER['PHP_AUTH_PW'] != 'password' ) {
			AuthHeaders();
		}
	}

}