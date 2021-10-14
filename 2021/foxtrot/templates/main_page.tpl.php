<?php
/*if(isset($_GET['action']) && $_GET['action'] != '')
{
    include_once(DIR_WS_CONTENT.$content.".tpl.php");
}
else
{*/
    include_once(DIR_FS."header.php");
    include_once(DIR_WS_CONTENT.$content.".tpl.php");
    include_once(DIR_FS."footer.php");
/*}*/
    
?>