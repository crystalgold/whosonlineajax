<?php
	require_once 'include/config.php';
	require_once 'include/functions.php';
	
	$connection = getPDOConnection($config['db']);
	$result = getPlayersOnline($connection, 0, $config['per_page']);
	$charsOnline = $result['players'];
	$charsOnlineCount = $result['count'];
?>

<html>
	<head>
		<link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
		<script type="text/javascript">
		  var refreshTime = <?php echo $config['refresh_time'] ?>;
			function getPlayersOnline()
			{
				$.ajax({
				  url: "ajax/get_players_online.php",
				  dataType: 'json',
				  success: processPlayers
				});
			}
		
			function processPlayers(data, textStatus, XMLHttpRequest)
			{
				var allUsersId = new Array();
				var curUsersId = new Array();
				$.each(data.players, function(index, itemData){allUsersId.push(itemData.char_id)})
				
				$(".char").each(function(){
					var charId = $(this).attr('id').split('_')[1];
					if (jQuery.inArray(charId, allUsersId) == -1)
						makeCharDisappear(charId);
					else
						curUsersId.push(charId);
				});
				
				$(allUsersId).each(function(index, charId){
					if (jQuery.inArray(charId, curUsersId) == -1)
					{
						$.ajax({
							type: "get",
						  url: "ajax/get_player_online.php",
						  data: "charId=" + charId,
						  success: function(html){
								if (index == 0)
									$(html).insertBefore($("#char_" + $(allUsersId).get(0))).show("drop", {direction:"down"}, 1300);
								else
									$(html).insertAfter($("#char_" + $(allUsersId).get(index - 1))).show("drop", {direction:"down"}, 1300);	
							}
						});
					}
				});
				
				if ($('#players-online').text() != data.count)
				{
					$('#players-online').parent().effect("highlight", {}, 2000);
					$('#players-online').text(data.count);
				}
			}
			
			function makeCharDisappear(divId)
			{
				var oDiv = $('#char_' + divId);
				oDiv.effect("pulsate", { times:3 }, 300);
				oDiv.hide("drop", {direction:"down"}, 800, function(){ $(this).replaceWith("") });
			}

			$(document).ready(function(){
			  if (refreshTime < 5)
			    refreshTime = 5;
				setInterval('getPlayersOnline();', refreshTime * 1000); 
			});
			
		</script>
	</head>
	<body>
		<div id="chars">
			<p>
				Il y a <span id="players-online"><?php echo $charsOnlineCount ?></span> joueur<?php if ($charsOnlineCount > 1) echo "s"; ?> en ligne
			</p>
			<?php
				foreach($charsOnline as $charOnline)
				{
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
			<br style="clear:left" />
		</div>
	</body>
</html>