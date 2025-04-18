<?php
    // f = open($_GET['file'], mode='r');
    $attachment_location = 'uploads/'. $_GET['file'];

    if (file_exists($attachment_location)) {

        header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
        header('Content-Type: application/octet-stream');
        header("Cache-Control: public");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length:".filesize($attachment_location));
        header("Content-Disposition: attachment; filename=".$_GET['file']);

        flush();
        $fp = fopen($attachment_location, 'rb');
        while (!feof($fp)) {
            echo fread($fp, 1024 * 1024 * 5);
            flush();
        }      
        fclose($fp);
    } else {
        die("Error: File not found.");
    } 
?>