<?php
	require_once '../include/config.php';
	require_once '../include/functions.php';

	if ( ( isset($_GET['charId'] ) ) && ( preg_match( '/[0-9]+/i', $_GET['charId'] ) ) )
	{
		$connection = getPDOConnection( $config['db'] );
		$charOnline = getPlayerOnline( $connection, $_GET['charId'] );
?>

<div id="char_<?php echo $charOnline->char_id ?>" class="char">
	<div class="char-name"><?php echo $charOnline->name ?></div>
	<div class="char-avatar">
		<img src="<?php
								echo 'http://ro-character-simulator.ratemyserver.net/charsim.php?gender=' . sexToInt($charOnline->sex)
								. '&job=' . translateJobId($charOnline->class) . '&hair=' . $charOnline->hair . '&direction=1&action=0&hdye='
								. $charOnline->hair_color . '&dye=' . $charOnline->clothes_color . '&framenum=0&bg=0'
								. '&rand=5286137'; ?>" />
	</div>
</div>

<?php
	}
?>

