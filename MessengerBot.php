<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	// parameters
	$hubVerifyToken = '*****';
	$accessToken = "*******";
	$salutation=array("hai","hello","hi","bro");
	
	// check token at setup
	if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
		echo $_REQUEST['hub_challenge'];
		exit;
	}

	// handle bot's anwser
	$input = json_decode(file_get_contents('php://input'), true);

	$senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
	$messagetext = strtolower($input['entry'][0]['messaging'][0]['message']['text']);
	$answer = "I don't understand. Ask me 'hi'.";
	
	if(array_filter($salutation, function($el) use ($messagetext) {
	        return ( strpos($el, $messagetext) !== false );
	    })) {
		$answer = "Hi";
	}
	
	$response = [
	'recipient' => [ 'id' => $senderId ],
	'message' => [ 'text' => $answer ]
	];

	$options=array(
		'http'=>array(
					'method' => 'POST',
					'content' => json_encode($response),
					'header' => "Content-Type: application/json\n"
				)
		);
	$data=stream_context_create($options);

	if($messagetext){
		file_get_contents("https://graph.facebook.com/v2.6/me/messages?access_token=$accessToken",false,$data);
	}
