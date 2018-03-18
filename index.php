<?php

include_once "vendor/autoload.php";

use Functions\Formatter;
?>


<form method="post" enctype="multipart/form-data">
    Select xml / html to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload file" name="submit">
</form>

<?

if($_FILES) {
    $formatter = new Formatter($_FILES);
    $formatter->format();

    echo "<textarea rows='30' style='border: none; width: 100%'>" . $formatter . "</textarea>";
}

?>