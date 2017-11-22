<?php 
define('MODX_API_MODE', true);
define('IN_MANAGER_MODE', true);
include_once("../../../index.php");
$modx->db->connect();
if(empty ($modx->config)){
	$modx->getSettings();
}

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
   || (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') 
   || ($_SERVER['REQUEST_METHOD'] != 'POST')
   || !isset($_SESSION['mgrValidated'])
  ) {
	$modx->sendErrorPage();
}




header("Content-type:text/json; Charset:utf-8");
require_once __DIR__ . "/pq.php";

$ch = curl_init();
$url = $_POST['url'];
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
$output = curl_exec($ch);
curl_close($ch);

$html = phpQuery::newDocument($output);
$pagetitle = $html->find('.entry-title')->text();
$img = $html->find('.entry-content img')->attr('src');
if($img){
	$newfile = $modx->config['base_path'] . '/assets/images/' . pathinfo($img, PATHINFO_BASENAME);
	
	if(!copy($img,$newfile)){
		$img = 0;
	}
	else{
		$img = 'assets/images/' . pathinfo($img, PATHINFO_BASENAME);
	}
}

$html->find('.entry-content .buttons_share')->remove();
$entry = $html->find('.entry-content')->html();

$data['img'] = $img;
$data['pagetitle'] = $pagetitle;
$data['entry'] = $entry;

echo json_encode($data);
?>