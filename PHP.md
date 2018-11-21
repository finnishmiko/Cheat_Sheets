# PHP

```php
/**
 * Add Basic Authentication to web page
 */
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
```
