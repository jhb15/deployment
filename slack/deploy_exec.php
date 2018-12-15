<?php
$output = shell_exec('/var/www/default/aberfitness/deploy.sh');

$response = [

	'response_type' => 'in_channel',
	'text' => 'Deployment output...',
	'attachments' => [
		['text' => '```' . $output . '```']
	]
];

$response = json_encode($response);

$ch = curl_init($argv[1]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");             
curl_setopt($ch, CURLOPT_POSTFIELDS, $response);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($response))                                                                       
);                                                                                                                   
                                                                                                                     
$result = curl_exec($ch);