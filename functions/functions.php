<?php
function isActive($filename)
{
    return strpos($_SERVER['PHP_SELF'], $filename) !== false ? 'active' : '';
}

?>