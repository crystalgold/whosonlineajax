<?php
	require_once '../include/config.php';
	require_once '../include/functions.php';
	$connection = getPDOConnection($config['db']);
	$result = getPlayersOnline($connection, 0, $config['per_page']);
	$charsOnline = $result['players'];
	$charsOnlineCount = $result['count'];

	header('Content-Type: application/json');
  echo json_encode($result);
?>