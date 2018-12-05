<?php
header('Content-Type: application/json');
$response = [

	'response_type' => 'in_channel',
	'text' => $_POST['user_name'] . ' began deployment on docker2.aberfitness.biz, please wait...'
];

$response = json_encode($response);

echo $response;

exec('screen -dmS deploy /usr/bin/php /var/www/default/aberfitness/deploy_exec.php "' . $_POST['response_url'] . '"');