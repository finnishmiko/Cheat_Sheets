<?php
/**
 * PHP Curl POST function
 */
function send_post_request( $url, $post_content ) {

    $fields = array(
        'data[general][message]' => $post_content['data_general_message'],
        'data[general][type]' => $post_content['data_general_type'],
    );

	// url-ify the data for the POST
	$fields_string = "";
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	$fields_string_trimmed = rtrim($fields_string, '&');

	// open connection
	$ch = curl_init();

	// set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string_trimmed);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // enable if you want
	curl_setopt($ch, CURLOPT_HEADER, false); // we want to receive headers (result json_decode() fails if this is true)
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // we want to receive response

    $API_KEY = ( getenv('API_KEY') ? 'Authorization: Bearer ' . getenv('API_KEY') : null );

	$headers = array( "Content-Type: application/x-www-form-urlencoded", $API_KEY, "Content-length: ".strlen( $fields_string_trimmed ) );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers); 

	// execute post
	$result = curl_exec($ch);

	// Receive HTTP code
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	// $full_response = curl_getinfo($ch);

	// close connection
	curl_close($ch);

	return [$httpcode, $result];

}


/** Example form data */
$form_data = array(
    'data_general_message' => 'Test data',
    'data_general_type' => 'testtype'
);

/**
 * URL for testing post requests.
 * Sends back sent data.
 */
$url = 'http://httpbin.org/post';

try {
    /** Send POST request and receive result */
    [$httpcode, $result]  = send_post_request($url, $form_data);

    if ( $httpcode == 200 ) {
        /** Convert result to JSON */
        $json_result = json_decode($result, true);
        print_r( $json_result );

        /** Received form data  */
        $data = $json_result['form'];
        print_r ( $data );
    } else {
        echo 'Received code: ' . $httpcode . PHP_EOL;
    }
} catch (\Throwable $th) {
    //throw $th;
    echo 'Failed with code: ' . $httpcode;
    throw new Exception('HTTP code: ' . $httpcode);
}