<?php
function env($key, $default = null)
{
    return \Arrilot\DotEnv\DotEnv::get($key, $default);
}