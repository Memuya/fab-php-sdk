<?php

namespace Memuya\Fab\Endpoints;

class BaseConfig
{
    /**
     * Set up all the properties on the child class with the data provided.
     * 
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        foreach ($config as $property => $value) {
            if (property_exists($this, $property)) {
                $camel_case_property = str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
                $method = sprintf('set%s', $camel_case_property);

                // If there's a setter for the given property, we'll use that instead.
                if (method_exists($this, $method)) {
                    $this->{$method}($value);

                    continue;
                }

                $this->{$property} = $value;
            }
        }
    }
}