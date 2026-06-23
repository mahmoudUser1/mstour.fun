<?php function getdesc()
{
    global $pagedesc;
    if (isset($pagedesc)) {
        echo $pagedesc;
    } else {
        echo 'errer';
    }
}