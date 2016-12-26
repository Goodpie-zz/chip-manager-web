<?php

class Helpers
{
    // Define connection codes
    public static const CONNECTED = 1;
    public static const DISCONNECTED = 0;

    public static final function get_address()
    {
        $config = parse_ini_file("../config.ini");
        $address = $config['address'];
        return $address;
    }
}