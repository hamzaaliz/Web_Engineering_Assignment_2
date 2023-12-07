<?php
// These are global arrays, the first one holds the URLs crawled, 
// and the second one holds their corresponding responses in the form of HTML.

$url_queue = [];
$url_responses_queue = [];

// Receiving form data from index.html, and adding the seed URL to the queue 
// and calling saveHTMLContent() to save it's HTML content via a HTTP request/response cycle.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seedURL = $_POST["url"];
    $depth = $_POST["depth"];

    if (isValidURL($seedURL)) {
        $url_queue[] = $seedURL;
    } else {
        echo "The provided Seed URL is invalid.";
        exit;
    }
    saveHTMLContent($seedURL);
} else {
    echo "Form not submitted.";
}

// This function takes a URL as an input, sends an HTTP request to that URL, 
// saves the response in a variable, and appends the response to a global array.

function saveHTMLContent($url) {
    global $url_responses_queue;

    $response = file_get_contents($url);

    if ($response === FALSE) {
        $url_responses_queue[] = "ERROR: Failed to fetch content from $url";
    } else {
        $url_responses_queue[] = $response;
        extractURLs($response);
    }
}

// This function takes HTML content, which is an HTTP response from a URL,
// and extracts all the URLs in that HTML and appends them to the queue of URLs.

function extractURLs($url_response) {
    global $url_queue;

    if ($url_response !== FALSE) {
        $dom = new DOMDocument;
        $dom->loadHTML($url_response);
        $xpath = new DOMXPath($dom);
        $hrefNodes = $xpath->query('//a[@href]');

        foreach ($hrefNodes as $node) {
            $url_queue[] = $node->getAttribute('href');
        }
    } else {
        $url_responses_queue[] = "ERROR: Invalid HTML response.";
    }
}

// This function takes a URL as an input, and returns true if the URL is valid, else false.

function isValidURL($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

// for ($i = 0; $i < count($url_queue); $i++) {
//     echo "URL: " . $url_queue[$i] . "<br>";
//     echo "<hr>";
// }

echo "Total URLs in the queue: " . count($url_queue);
echo "Total Responses in the queue" . count($url_responses_queue);
?>
