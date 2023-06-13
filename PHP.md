# PHP

## Wrapper functions

```php
/** Wrapper to check if operation fails */
function JsonDecode(string $stringData, $associative = null ) {
	$output = json_decode($stringData, $associative);
	if ($output == false) {
		throw new \Exception('JSON Decode exception');
	}
	return $output;
}

/** Wrapper to check if operation fails */
function JsonEncode($data) {
	$output = json_encode($data, JSON_PRETTY_PRINT);
	if ($output == false) {
		throw new \Exception('JSON Encode exception');
	}
	return $output;
}

/** Wrapper to check if operation fails */
function fileGetContents($fileName) {
	$output = file_get_contents($fileName);
	if ($output == false) {
		throw new \Exception('File get contents exception');
	}
	return $output;
}
```

## Unit-tests with PHPUnit

This assumes that you have added PHPUnit-9.3.7.phar file to project folder.

### Run all files that end with Test.php

php .\phpunit-9.3.7.phar --testdox .\tests

### Run specific test

php .\tests\phpunit-9.3.7.phar --testdox .\tests\themeFunctionsTest.php

### Run with Docker (which has older version of PHPUnit)

docker run -v ${pwd}:/app --rm phpunit/phpunit:latest .\tests

## Unit test examples

```php
<?php
/**
 * Download phpunit:
 * wget https://phar.phpunit.de/phpunit-9.5.phar -OutFile phpunit-9.5.phar
 *
 * Run unit tests with:
 * php.exe .\phpunit-9.3.7.phar --testdox .\tests
 */
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require_once('inc/theme-functions.php');

final class MyThemeFunctionsTest extends TestCase
{
    public function testTestFunction(): void
    {
        $this->assertEquals(
            'Expected output value',
            testFunction('parameter')
        );
    }

   /** Test fail case */
    public function testShouldFailWhenInputIsNull(): void
    {
        $this->expectException(\Exception::class);
        $testValue = null;
        writeFunction( $testValue );
    }

    public function testCanCreateFile(): void
    {
        $outputFile = getFile();

        /** Create test data */
        $jsonDocument = Array();
        $app = new \stdClass();
            $app->images = Array();
            $app->data = Array();
        $jsonDocument[]=$app;

        /** Function to test */
        writeJsonFile( $outputFile, $jsonDocument );

        $this->assertEquals(
            file_exists( $outputFile ),
            true
        );
        $string = file_get_contents( $outputFile );
        $json_compare = JsonDecode($string, true);
        $this->assertIsArray( $json_compare );
        $this->assertEquals(
            count( $json_compare ),
            1
        );
        /** Remove test file */
        unlink( $outputFile );
    }
}

```

## Measure load times:

```php
$starttime = microtime(true);
// Code to test here
$endtime = microtime(true);

printf("Loading time %f seconds", $endtime - $starttime );
```

## Basic auth

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

## Run external command in Azure Windows web app

```php
$command = $cwebpFile . ' -q 80 ' . $filePath . DIRECTORY_SEPARATOR . $fileName . '.' . $extension . ' -o ' . $filePath . DIRECTORY_SEPARATOR . $fileName . '.' . $extension . '.webp';
$proc=proc_open($command,
    array(
        array("pipe","r"),
        array("pipe","w"),
        array("pipe","w")
    ),
    $pipes);
print stream_get_contents($pipes[1]);
```