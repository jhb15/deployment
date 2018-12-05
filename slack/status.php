<?php
header('Content-Type: application/json');

$output = shell_exec('/var/www/default/aberfitness/status.sh');
$response = [

	'response_type' => 'in_channel',
	'text' => $_POST['user_name'] . ' ran `docker ps` on docker2',
	'attachments' => [
		['text' => '```' . $output . '```']
	]
];
echo json_encode($response);
