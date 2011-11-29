<?
session_start();

if(isset($_POST['setopt'])):

	if(isset($_POST['ckdown']))
		$_SESSION['ckdown'] = filter_var($_POST['ckdown'], FILTER_VALIDATE_BOOLEAN);
	echo 'Salvato';
	exit(0);
endif;

$forcedown = isset($_SESSION['ckdown']) ? $_SESSION['ckdown'] : false;

$dircache = '/var/www/maps/osmtms/';
$r = trim($_SERVER['QUERY_STRING']);
$url = "http://tile.openstreetmap.org/$r";

$ftile = $dircache.$r;

header("Content-type: image/png");

if($forcedown or !is_file($ftile) or filesize($ftile)==0 or is_link($ftile))
{
	if( !is_dir(dirname($ftile)) )
		mkdir(dirname($ftile), 0775, true);

	if($p = file_get_contents($url))
	{
		if(is_link($ftile))
			unlink($ftile);
		file_put_contents($ftile, $p);
	}
	else
		symlink($dircache.'empty.png', $ftile);
}

readfile($ftile);
//*/
?>
