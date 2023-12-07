<?php
$url_queue = [];
$url_responses_queue = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seedURL = $_POST["url"];
    $depth = $_POST["depth"];

    global $url_queue;
    $url_queue[] = $seedURL;
    saveHTMLContent($seedURL);
} 
else {
    echo "Form not submitted.";
}

// This function takes a URL as an input, sends an HTTP request to that URL, saves the response in a variable, and appends the response to a global array.
function saveHTMLContent($url) {
    global $url_queue;
    global $url_responses_queue;

    $response = file_get_contents($url);

    if ($response === FALSE) {
        $url_responses_queue[] = "ERROR";
    } 
    else {
        $url_responses_queue[] = $response;
    }

    $dom = new DOMDocument;
    $dom->loadHTML($response);
    $xpath = new DOMXPath($dom);
    $hrefNodes = $xpath->query('//a[@href]');

    foreach ($hrefNodes as $node) {
        $url_queue[] = $node->getAttribute('href');
    }
}

for ($i=0 ; $i<count($url_queue) ; $i++) {
    echo $url_queue[$i] . "<br>";
}
echo count($url_queue);
?>


