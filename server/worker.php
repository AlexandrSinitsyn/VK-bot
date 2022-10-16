<?php
	unlink($conf[log]);
	
	$query = file_get_contents($conf['apiurl'].'photos.get?owner_id='.$conf['owner_id'].'&album_id='.$conf['album_id'].'&v='.$conf['v'].'&access_token='.$conf['standalone']);
	$res = json_decode($query, true);
	
	foreach($res as $v) {
		foreach($v['items'] as $q) {
			$result = 'photo'.$q['owner_id'].'_'.$q['id'];
			file_put_contents($conf['photos'], $result."\n", FILE_APPEND | LOCK_EX);
		}
	}
?>