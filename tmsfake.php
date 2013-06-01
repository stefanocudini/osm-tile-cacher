<?
session_start();

if(isset($_POST['setopt']))://force cache re-generation

	if(isset($_POST['ckdown']))
		$_SESSION['ckdown'] = filter_var($_POST['ckdown'], FILTER_VALIDATE_BOOLEAN);
	echo 'Option Saved';
	exit(0);
	
endif;

$forcedown = isset($_SESSION['ckdown']) ? $_SESSION['ckdown'] : false;

$dircache = './cache-tiles/';

$r = trim($_SERVER['QUERY_STRING']);
$url = "http://tile.openstreetmap.org/$r";

$ftile = $dircache.$r;

header("Content-type: image/png");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

if($forcedown or !is_file($ftile) or filesize($ftile)==0 or is_link($ftile))
{
	if( !is_dir(dirname($ftile)) )
		mkdir(dirname($ftile), 0775, true);
		
	//Important!
	//http://wiki.openstreetmap.org/wiki/Tile_usage_policy
	//Cache Tile downloads locally according to HTTP Expiry Header, alternatively a minimum of 7 days.
	if( (time() - filemtime($ftile)) >= pow(60,3)*24*7 )
	{
		if($p = file_get_contents($url))
		{
			if(is_link($ftile))
				unlink($ftile);
			file_put_contents($ftile, $p);
			chmod($ftile,0775);
		}
		else
			symlink($dircache.'empty.png', $ftile);
	}
}

readfile($ftile);
//*/
?>
