<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Spider</title>
</head>
<body>
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
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="url">Enter the Seed URL:</label>
    <input type="url" id="url">
    <label for="depth">Enter the depth of searching:</label>
    <input type="number" id="depth">
    <input type="submit"> 
</form>
</body>
</html>
