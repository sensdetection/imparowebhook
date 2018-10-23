<?php 
require 'simple_html_dom.php';

$siti = [];
$notizie = [];
$method = $_SERVER['REQUEST_METHOD'];

function scrapingNews() {
	$html = file_get_html('http://www.comune.barletta.bt.it/retecivica/avvisi18.htm');
	foreach($html->find('#bordovideo-112') as $item)
	{
	    // Find all <td> in <tr> 
	    foreach($item->find('tr') as $tr) 
	    {
		foreach($tr->find('td') as $news) 
		{
		    echo "tabella";
		    print_r($news->innertext);
		    $notizie = $news->innertext;
		}
		foreach($tr->find('a') as $link) 
		{
		    echo "sito";
		    print_r("http://www.comune.barletta.bt.it/retecivica/".$link->href);
		    $sito = "http://www.comune.barletta.bt.it/retecivica/".$link->href;
		}
	    }
	}
}

// Process only when method is POST
if($method == 'POST'){
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);

	$text = $json->result->parameters->text;

	switch ($text) {
		case 'hi':
			$speech = "Hi, Nice to meet you";
			break;

		case 'bye':
			$speech = "Bye, good night";
			break;

		case 'news':
			for ($x = 0; $x < 5; $x++) {
				$speech = $news[1].'\n'.$news[2];
				$response = new \stdClass();

				$response->speech = $speech;
				$response->displayText = $speech;
				$response->source = "webhook";
				echo json_encode($response);
			}
			break;
		
		default:
			$speech = "Sorry, I didnt get that. Please ask me something else.";
			break;
	}

	$response = new \stdClass();
	$response->speech = $speech;
	$response->displayText = $speech;
	$response->source = "webhook";
	echo json_encode($response);
}
else
{
	echo "Method not allowed";
}

?>
