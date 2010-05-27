<?php
	function sexToInt($sex)
	{
		return ($sex == 'M' ? '1' : '0');
	}

	function translateJobId($jobId)
	{
		switch($jobId){
			case 4060: return 5000; // Rune Knight
			case 4063: return 5001; // Arch Bishop
			case 4061: return 5002; // Warlock
			case 4078: return 5003; // Genetic
			case 4062: return 5004; // Ranger
			case 4065: return 5005; // Guillotine Cross
			case 4073: return 5006; // Royal Guard
			case 4077: return 5007; // Sura
			case 4074: return 5008; // Sorcerer
			case 4079: return 5009; // Shadow Chaser
			case 4076: return 5010; // Wanderer ?
			case 4075: return 5011; // Minstrel
			case 4023: return 23; // Super novice
			default: return $jobId;
		}
	}
	
	function getPDOConnection($config_db)
	{
		$connection = new PDO( "mysql:host=" . $config_db['host'] . ";port=" . $config_db['port']
									. ";dbname=" . $config_db['dbname'], $config_db['username'],
									$config_db['password'] );
		$connection->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
									
		return $connection;
	}
	
	function getPlayersOnline($pdoConnection, $index, $per_page)
	{
		$result = array('players' => array(),
										'count' => 0);
										
		$charsOnlineData = $pdoConnection->query( 'SELECT c.char_id, c.name, c.class, c.hair, c.hair_color, c.clothes_color, c.head_top,
											 		 										c.head_mid, c.head_bottom, a.sex                     
																							FROM `char` c                                        
																							JOIN login a ON a.account_id = c.account_id          
																							WHERE c.online = 1 ORDER BY c.name ASC' );

		$charsOnline = array();

		while ($charData = $charsOnlineData->fetch( PDO::FETCH_OBJ ))
			$charsOnline[] = $charData;
		$result['count'] = sizeof($charsOnline);
			
		$result['players'] = array_slice($charsOnline, $index, $per_page);
		
		return $result;
	}
	
	function getPlayerOnline($pdoConnection, $charId)
	{
		$charsOnlineData = $pdoConnection->query( 'SELECT c.char_id, c.name, c.class, c.hair, c.hair_color, c.clothes_color, c.head_top,
											 		 										c.head_mid, c.head_bottom, a.sex                     
																							FROM `char` c                                        
																							JOIN login a ON a.account_id = c.account_id          
																							WHERE c.online = 1 AND c.char_id = '. $charId .' ORDER BY c.name ASC LIMIT 0,1' );
		return $charsOnlineData->fetch( PDO::FETCH_OBJ );
	}
?>