<?php
if(isset($_POST['file'])){
    $file = 'uploadsgamme/' . $_POST['file'];
    if(file_exists($file)){
        unlink($file);
    }
}
?>
