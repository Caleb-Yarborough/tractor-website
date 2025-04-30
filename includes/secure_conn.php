<!-- Caleb Yarborough -->
<?php
    // Require https
    $https = $_SERVER['HTTPS'];
    if ($https != "on") {
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        $url = 'https://' . $host . $uri;
        header("Location: " . $url);
        exit();
    }
?>