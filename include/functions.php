<?php
	function sexToInt($sex)
	{
		return ($sex == 'M' ? '1' : '0');
	}

	function translateJobId($jobId)
	{
		switch($jobId){
			case 4060: return 5000; // Rune Knight
			case 4061: return 5002; // Warlock
      case 4062: return 5004; // Ranger
      case 4063: return 5001; // Arch Bishop
			case 4064: return 5003; // Mechanic
			case 4065: return 5005; // Guillotine Cross
			
			// 3rd Class (Regular to 3rd)
			case 4073: return 5007; // Royal Guard
			case 4074: return 5009; // Sorcerer
      case 4075: return 5012; // Minstrel
      case 4076: return 5010; // Wanderer ?
      case 4077: return 5008; // Sura
			case 4078: return 5011;
      case 4079: return 5010; // Shadow Chaser
      
      case 4045: return 23;
			
			default: if ($jobId >= 4001 && $jobId <= 4007)
			          return $jobId - 4001; // High Novice / High 1st Class
			         elseif ($jobId >= 4024 && $jobId < 4043)
			          return $jobId - 4023; // 1st And 2nd Class Babys
			         else
			          return $jobId;
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
			
		// $result['players'] = array_slice($charsOnline, $index, $per_page);
		$result['players'] = $charsOnline;
    
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