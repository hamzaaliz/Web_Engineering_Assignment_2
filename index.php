<?php
// These are global arrays, the first one holds the URLs crawled, 
// and the second one holds their corresponding responses in the form of HTML.

$url_queue = [];
$url_responses_queue = [];
$choice = '';

// Receiving form data from index.html, and adding the seed URL to the queue 
// and calling checkAndAppendHTMLResponse() to save its HTML content via an HTTP request/response cycle.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seedURL = $_POST["url"];

    // Check if the URL is allowed by robots.txt
    if (!isUrlAllowedByRobotsTxt($seedURL)) {
        echo "Crawling is not allowed for this URL based on robots.txt rules.";
        exit;
    }

    if (isset($_POST['task'])) {
        $choice = $_POST['task'];
    }

    if ($choice === 'find') {
        $searchText = $_POST["searchText"];
        searchContent($searchText);
    }

    if (isURLValid($seedURL) && checkAndAppendHTMLResponse($seedURL)) {
        $url_queue[] = $seedURL;

        extractURLs($url_responses_queue[0]);

        for ($i = 1; $i < count($url_queue); $i++) {
            checkAndAppendHTMLResponse($url_queue[$i]);
        }

        if ($choice === 'view') {
            for ($i = 0; $i < count($url_responses_queue); $i++) {
                viewCrawledContent($url_responses_queue[$i]);
            }
        }
    } else {
        echo "The provided Seed URL is invalid or there was no response from that URL.";
        exit;
    }
} else {
    echo "Form not submitted.";
}

// Function to check if a URL is allowed by robots.txt
function isUrlAllowedByRobotsTxt($url) {
    $robotsTxtUrl = rtrim($url, '/') . '/robots.txt';
    $robotsTxtContent = @file_get_contents($robotsTxtUrl);

    if ($robotsTxtContent !== false) {
        // Parsing the content of robots.txt and checking if the URL is allowed.
        return isUrlAllowedByRobotsTxtContent($robotsTxtContent);
    }

    // If unable to fetch robots.txt, assume the URL is allowed
    return true;
}

// Function to parse robots.txt content and check if a URL is allowed
function isUrlAllowedByRobotsTxtContent($content) {
    $lines = explode("\n", $content);
    
    foreach ($lines as $line) {
        $line = trim($line);

        if (strpos($line, 'Disallow:') === 0) {
            $disallowedPath = trim(substr($line, strlen('Disallow:')), " \t\n\r\0\x0B/");
            
            // Check if the URL matches the disallowed path
            if (strpos($_POST["url"], $disallowedPath) === 0) {
                return false;
            }
        }
    }

    // If no Disallow directive matched, assume the URL is allowed
    return true;
}

// Function to check if a URL is valid
function isURLValid($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

// Function to check and append HTML response to the queue
function checkAndAppendHTMLResponse($url) {
    global $url_responses_queue;

    $response = file_get_contents($url);

    if ($response === FALSE) {
        $url_responses_queue[] = "ERROR";
        return false;
    } else {
        $url_responses_queue[] = $response;
        return true;
    }
}

// Function to extract URLs from HTML content and add them to the queue
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
        $url_responses_queue[] = "ERROR";
    }
}

// Function to view crawled content
function viewCrawledContent($html) {
    $dom = new DOMDocument;
    libxml_use_internal_errors(true); 
    $dom->loadHTML($html);
    libxml_clear_errors();

    $title = $dom->getElementsByTagName('title')->item(0)->nodeValue;

    $metaTags = $dom->getElementsByTagName('meta');
    $metaInfo = [];

    foreach ($metaTags as $metaTag) {
        $name = $metaTag->getAttribute('name');
        $content = $metaTag->getAttribute('content');
        $metaInfo[$name] = $content;
    }

    echo "Title: $title <br />Meta Information: $metaInfo<hr />";
}

// Function to search content for a specified string
function searchContent($searchText) {
    global $url_responses_queue;

    foreach ($url_responses_queue as $url_response) {
        if (strpos($url_response, $searchText) !== false) {
            echo "Found in URL: " . getCurrentURL() . "<br>";
            echo "Searched Text: $searchText <br>";
        }
    }
}

// Function to get the current URL from the URL queue
function getCurrentURL() {
    global $url_queue;
    $currentIndex = count($url_queue) - 1;
    return $url_queue[$currentIndex];
}

echo "Total URLs in the queue: " . count($url_queue);
echo "Total Responses in the queue" . count($url_responses_queue);
?>