<?php
if ($_GET['randomId'] != "OWfx2ioaWVzzV4BtNK32kvHAjqfzXqfsjiDpDZInSb3ykRqdvLnKre53u5AenEDg") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
