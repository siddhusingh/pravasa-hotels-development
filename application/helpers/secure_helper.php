<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function encrypt_id($id)
{
    $CI =& get_instance();

    return rtrim(
        strtr($CI->encryption->encrypt($id), '+/', '-_'),
        '='
    );
}

function decrypt_id($id)
{
    $CI =& get_instance();

    $id = strtr($id, '-_', '+/');

    $padding = strlen($id) % 4;

    if ($padding) {
        $id .= str_repeat('=', 4 - $padding);
    }

    return $CI->encryption->decrypt($id);
}