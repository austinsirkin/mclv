<?php

    require __DIR__ . '/vendor/autoload.php';
    //date_default_timezone_set('America/New_York');

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

include '/classes/url.php';

    session_start();
    
   $app->get('/lists', function() use($app){
		$apikey = $_SESSION['apikey'];
		$pageSize = 100;
		$offset = 0;
		$shard = substr($apikey, 33, strlen($apikey));
		$url = "https://" . $shard . ".api.mailchimp.com/3.0/lists?count=9999";
		$json = json_decode(call($url, $apikey), 1);

	$app->render('lists.twig', array(
		'lastMod' => date("F d, Y \a\\t h:i:s a e", getlastmod()),
		'api' => $apikey,
		'json' => $json,
		'pageSize' => $pageSize,
		'offset' => $offset
));     
})->name('lists');


    $app->get('/', function() use($app){
	 unset($_SESSION['json']);
	 unset($_SESSION['apikey']);
	 $app->render('index.twig', array(
		'lastMod' => date("F d, Y \a\\t h:i:s a e", getlastmod())
));
})->name('index');


	$app->post('/lists', function() use($app){
		$apikey = $app->request->post("apikey");
		$pageSize = 100;
		$offset = 0;
		$shard = substr($apikey, 33, strlen($apikey));
		$url = "https://" . $shard . ".api.mailchimp.com/3.0/lists?count=9999";
		$json = json_decode(call($url, $apikey), 1);
		$_SESSION['apikey'] = $apikey;

	$app->render('lists.twig', array(
		'lastMod' => date("F d, Y \a\\t h:i:s a e", getlastmod()),
		'api' => $apikey,
		'json' => $json,
		'pageSize' => $pageSize,
		'offset' => $offset
));     

              });

	
 $app->get('/members', function() use($app){
	$listId = $app->request()->params('listId');
	$offset = $app->request()->params('offset');
	$apikey = $_SESSION['apikey'];
	$pageSize = 100;
	$shard = substr($apikey, 33, strlen($apikey));
	$url = "https://" . $shard . ".api.mailchimp.com/3.0/lists/" . $listId . '/members?count=' . $pageSize . '&offset=' . $offset;
	$json = json_decode(call($url, $apikey), 1);	
	
	$app->render('members.twig', array(
		'lastMod' => date("F d, Y \a\\t h:i:s a e", getlastmod()),
		'json' => $json,
		'pageSize' => $pageSize,
		'offset' => $offset,
		'listId' => $listId
		
));     
})->name('members');


 $app->post('/members', function() use($app){
		$listId = $app->request()->params('listId');
		$offset = $app->request()->params('offset');
		$apikey = $_SESSION['apikey'];
		$listId = $app->request->post("list");
		$pageSize = 100;
		$offset = 0;
		$shard = substr($apikey, 33, strlen($apikey));
		$url = "https://" . $shard . ".api.mailchimp.com/3.0/lists/" . $listId . '/members?count=' . $pageSize . '&offset=' . $offset;
		$json = json_decode(call($url, $apikey), 1);
	if ($offset < 0) {
		$offset = 0;
	} elseif ($offset >= ($json["total_items"] - $pageSize)) {
		$offset = ($json["total_items"] - $pageSize);
	}
	$app->render('members.twig', array(
		'lastMod' => date("F d, Y \a\\t h:i:s a e", getlastmod()),
		'json' => $json,
		'pageSize' => $pageSize,
		'offset' => $offset,
		'listId' => $listId
));
});





 $app->get('/sword', function() use($app){   
	$app->render('sword.twig', array(
		'lastMod' => date("F d, Y \a\\t h:i:s a e", getlastmod())   
));     
})->name('sword');






    $app->run();
?>