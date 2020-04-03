<?php

const MASK = "/%(.*?)%/";

$user = (object)[
    "id" => 20,
    "name" => "John Dow",
    "role" => "QA",
    "salary" => 100
];

const apiTemplatesSet1 = [
    "/api/items/%id%/%name%",
    "/api/items/%id%/%role%",
    "/api/items/%id%/%salary%"
];


function getApiPath($obj, $template)
{
    if (!is_object($obj)) {
        throw new InvalidArgumentException("First parameter must either be an object");
    }
    while (preg_match(MASK, $template, $matches)) {
        if (property_exists($obj, $matches[1])) {
            $template = str_replace($matches[0], $obj->{$matches[1]}, $template);
        } else {
            throw new InvalidArgumentException("Object must have a property: " . $matches[1]);
        }
    }
    return $template;
}

try {
    $result = array_map(function ($value) use ($user) {
        return getApiPath($user, $value);
    }, apiTemplatesSet1);
    echo(json_encode($result, JSON_UNESCAPED_SLASHES));
} catch (InvalidArgumentException $e) {
    echo($e->getMessage());
}
