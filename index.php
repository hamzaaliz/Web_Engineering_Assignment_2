<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seedURL = $_POST["url"];
    $depth = $_POST["depth"];

    echo "URL: $seedURL<br>";
    echo "Depth: $depth";
} else {
    echo "Form not submitted.";
}
?>