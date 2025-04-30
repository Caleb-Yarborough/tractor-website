<!-- Caleb Yarborough -->
<?php
    // Revert to regular http
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        $url = 'http://' . $host . $uri;
        header("Location: " . $url);
        exit();
    }
?>