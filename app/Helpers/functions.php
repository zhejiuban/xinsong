<?php
function route_uri($name)
{
    return app('router')->getRoutes()->getByName($name)->uri();
}
