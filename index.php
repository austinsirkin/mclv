<?php

require __DIR__ . '/vendor/autoload.php';

$app = new \Slim\Slim(array(
    'view' => new \Slim\Views\Twig()
    ));

$view = $app->view();
$view->parserOptions = array(
    'debug' => true,
);

$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
);

// This inclusion allows the call function I created for making curl calls. Requires parameters $url and $apikey.
include '/classes/call.php';

session_start();

// Main routing for Index page.
    $app->get('/', function() use($app){
	 unset($_SESSION['json']);
	 unset($_SESSION['apikey']);
	 $app->render('index.twig', [
		'lastMod' => date("F d, Y \a\\t h:i:s a e", getlastmod())
	]);
})->name('index');


// Routing for both GET and POST versions of the Lists page.
   $app->get('/lists', function() use($app){
		$apikey = $_SESSION['apikey'];
		$apiArray = explode("-", $apikey);
		$pageSize = 100;
		$offset = 0;
		$shard = $apiArray[1];
		$url = "https://" . $shard . ".api.mailchimp.com/3.0/lists?count=9999";
		$json = json_decode(call($url, $apikey), 1);
		$jsonCount = count($json['lists']);

	$app->render('lists.twig', [
		'lastMod' => date("F d, Y \a\\t h:i:s a e", getlastmod()),
		'api' => $apikey,
		'json' => $json,
		'pageSize' => $jsonCount,
		'offset' => $offset
	]);     
})->name('lists');


	$app->post('/lists', function() use($app){
		$apikey = $app->request->post("apikey");
		$apiArray = explode("-", $apikey);
		$pageSize = 100;
		$offset = 0;

// Not the most elegant error handling here. Need to find a way to improve this.
		if (isset($apiArray[1]) == false) {
		exit("That didn't even look anything like an API key. <br>Come on, you can definitely do better than that. <br><a href=\"/mclv/\">Start over.</a>");
}
		$shard = $apiArray[1];
		$url = "https://" . $shard . ".api.mailchimp.com/3.0/lists?count=9999";
		$json = json_decode(call($url, $apikey), 1);
		$_SESSION['apikey'] = $apikey;
		$jsonCount = count($json['lists']);

// MySQL logging as an inclusion to clean up the code and make it available elsewhere if needed.
include '/classes/logging.php';

	$app->render('lists.twig', [
		'lastMod' => date("F d, Y \a\\t h:i:s a e", getlastmod()),
		'api' => $apikey,
		'json' => $json,
		'pageSize' => $jsonCount,
		'offset' => $offset
	]);     
});

// Routing for GET and POST versions of the Members page.
 $app->get('/members', function() use($app){
	$listId = $app->request()->params('listId');
	$offset = $app->request()->params('offset');
	$apikey = $_SESSION['apikey'];
	$apiArray = explode("-", $apikey);
	$pageSize = 100;
	$shard = $apiArray[1];
	$url = "https://" . $shard . ".api.mailchimp.com/3.0/lists/" . $listId . '/members?count=' . $pageSize . '&offset=' . $offset;
	$json = json_decode(call($url, $apikey), 1);	
	$jsonCount = count($json['members']);
	if ($offset < 0) {
		$offset = 0;
	} elseif ($offset > ($json["total_items"] - $jsonCount)) {
		$offset = ($json["total_items"] - $jsonCount);
	}
	$jsonCount = count($json['members']);
	$app->render('members.twig', [
		'lastMod' => date("F d, Y \a\\t h:i:s a e", getlastmod()),
		'json' => $json,
		'pageSize' => $pageSize,
		'offset' => $offset,
		'listId' => $listId,
		'jsonCount' => $jsonCount		
	]);     
})->name('members');


 $app->post('/members', function() use($app){
		$listId = $app->request()->params('listId');
		$offset = $app->request()->params('offset');
		$apikey = $_SESSION['apikey'];
		$listId = $app->request->post("list");
		$apiArray = explode("-", $apikey);
		$pageSize = 100;
		$offset = 0;
		$shard = $apiArray[1];
		$url = "https://" . $shard . ".api.mailchimp.com/3.0/lists/" . $listId . '/members?count=' . $pageSize . '&offset=' . $offset;
		$json = json_decode(call($url, $apikey), 1);
	if ($offset < 0) {
		$offset = 0;
	} elseif ($offset >= ($json["total_items"] - $jsonCount)) {
		$offset = ($json["total_items"] - $jsonCount);
	}
		$jsonCount = count($json['members']);
	$app->render('members.twig', [
		'lastMod' => date("F d, Y \a\\t h:i:s a e", getlastmod()),
		'json' => $json,
		'pageSize' => $pageSize,
		'offset' => $offset,
		'listId' => $listId,
		'jsonCount' => $jsonCount
	]);
});


// A little easter egg, for fun. :D
 $app->get('/sword', function() use($app){   
	$app->render('sword.twig', [
		'lastMod' => date("F d, Y \a\\t h:i:s a e", getlastmod())   
	]);     
})->name('sword');


// Run the app!
    $app->run();
