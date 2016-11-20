<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	// parameters
	$hubVerifyToken = '*****';
	$accessToken = "*******";
	$salutation=array("hai","da","hello","daaa","kooi","hi","bro");
	$aftersalutation=array("watsup","dude","man","so");
	$positiveaftersalutation=array("yes","yup","athe","ss");
	$negativeaftersalutation=array("no","nope","noway","sorry","alla");
	$questions_h=array("how are you?","how do you do?","enthoke ondu?");
	$questions_w=array("what are you?","what do you do?");
	$glims=array("hmm","ok","mm","k");
	$symbols=array("lol","cool","nice");
	$badwords_m=array("poda","patty","thendi");
	$badwords_e=array("fuck","shit","mother");
	$thanks=array("well done","super","thank you","welcome","awesome");

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
	else if(array_filter($aftersalutation, function($el) use ($messagetext) {
		return ( strpos($el, $messagetext) !== false );
	})){
		$answer = 'Going well, ;)';
	}
	else if(array_filter($questions_w, function($el) use ($messagetext) {
		return ( strpos($el, $messagetext) !== false );
	})){
		$answer = 'I am a Bot';
	}
	else if(array_filter($questions_h, function($el) use ($messagetext) {
		return ( strpos($el, $messagetext) !== false );
	})){
		$answer = 'I am a fine';
	}
	else if(array_filter($questions, function($el) use ($messagetext) {
        return ( strpos($el, $messagetext) !== false );
    })){
  		$answer = 'I am in kochi';
  	}
  	else if(array_filter($glims, function($el) use ($messagetext) {
  		return ( strpos($el, $messagetext) !== false );
  	})){
  		$answer = 'Hmmm.. :)';
  	}
  	else if(array_filter($symbols, function($el) use ($messagetext) {
  		return ( strpos($el, $messagetext) !== false );
  	})){
  		$answer = 'Nice..';
  		
  	}
  	else if(array_filter($badwords_m, function($el) use ($messagetext) {
  		return ( strpos($el, $messagetext) !== false );
  	})){
  		$answer = 'Hey dont use bad words..';
  	}
  	else if(array_filter($badwords_e, function($el) use ($messagetext) {
  		return ( strpos($el, $messagetext) !== false );
  	})){
  		$answer = 'Hey dont use bad words..';
  	}
  	else if(array_filter($thanks, function($el) use ($messagetext) {
  		return ( strpos($el, $messagetext) !== false );
  	})){
  		$answer = 'Thanks.. ;)';
  	}
  	else{
  		//$answer = 'Bot under construction';
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
