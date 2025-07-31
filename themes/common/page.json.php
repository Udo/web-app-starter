<?php

    header('Content-Type: application/json');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    if(URL::$fragments['json'])
    {
        print(json_encode(URL::$fragments['json']));
    }
    else
    {
        print(URL::$fragments['main']);
    }
