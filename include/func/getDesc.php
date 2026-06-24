<?php function getDesc()
{
    global $pageDesc;
    if (isset($pageDesc)) {
        echo $pageDesc;
    } else {
        echo 'errer';
    }
}