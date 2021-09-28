<?php

function getRequest($key = '')
{
    $result = '';
    
    if(isset($_REQUEST[$key]))
    {
        $result = $_REQUEST[$key];
    }
    
    return $result;
}

