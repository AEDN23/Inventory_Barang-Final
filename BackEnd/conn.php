<?php
define("CONF_DIR_PROJECT",         "PT ELASTOMIX IND/inventory-final");

function site_url($slash = false)
{
    $dir_project = "";
    $http_host = $_SERVER['HTTP_HOST'];
    $https_check = (!empty($_SERVER['HTTPS']) ? 'https' : 'http');

    if ($slash) {
        $siteurl =  $https_check . '://' . $http_host . '/' . $dir_project . '/';
    } else {
        $siteurl =  $https_check . '://' . $http_host . '/' . $dir_project;
    }

    return rtrim($siteurl, '/');
}
