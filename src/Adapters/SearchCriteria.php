<?php

namespace Memuya\Fab\Adapters;

use UnitEnum;
use ReflectionClass;
use ReflectionProperty;
use Memuya\Fab\Utilities\Str;
use Memuya\Fab\Attributes\Parameter;
use Memuya\Fab\Attributes\QueryString;
use Memuya\Fab\Exceptions\PropertyNotSetException;

abstract class SearchCriteria
{
    /**
     * Set up all the properties on the child class with the data provided.
     *
     * @param array<string, mixed> $criteria
     */
    public function __construct(array $criteria = [])
    {
        $this->setSearchCriteriaFromArray($criteria);
    }

    /**
     * Set up the criteria class proerties from the given array.
     *
     * @param array<string, mixed> $criteria
     * @return void
     */
    public function setSearchCriteriaFromArray(array $criteria): void
    {
        foreach ($criteria as $property => $value) {
            $this->setProperty($property, $value);
        }
    }

    /**
     * Return the options that will be a part of the query string as an array.
     *
     * @return array<string, mixed>
     */
    public function getQueryStringValues(): array
    {
        return $this->getValuesFor(QueryString::class);
    }

    /**
     * Return the options that will be a part of the query string as a usable query string.
     *
     * @return string
     */
    public function toQueryString(): string
    {
        return http_build_query($this->getQueryStringValues());
    }

    /**
     * Return the options that are marked as a parameter.
     *
     * @return array<string, mixed>
     */
    public function getParameterValues(): array
    {
        return $this->getValuesFor(Parameter::class);
    }

    /**
     * Return the values associated to the attribute passed in.
     *
     * @param class-string $attribute
     * @return array<string, mixed>
     */
    public function getValuesFor(string $attribute): array
    {
        $reflection = new ReflectionClass($this);
        $data = [];

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $property_name = $property->getName();

            if (count($property->getAttributes($attribute)) === 0) {
                continue;
            }

            if (! isset($this->{$property_name})) {
                continue;
            }

            try {
                // If we have an enum we want to extract the value from it.
                $value = $this->{$property_name} instanceof UnitEnum
                    ? $this->{$property_name}->value
                    : $this->{$property_name};

                $data[$property_name] = $value;
            } catch (PropertyNotSetException) {
                continue;
            }
        }

        return $data;
    }

    /**
     * Set a property's value, using a setter method if found.
     *
     * @param string $property
     * @param mixed $value
     * @return void
     */
    private function setProperty(string $property, mixed $value): void
    {
        if (! property_exists($this, $property)) {
            return;
        }

        $method = sprintf('set%s', Str::toPascalCase($property));

        if (method_exists($this, $method)) {
            $this->{$method}($value);

            return;
        }

        $this->{$property} = $value;
    }

    public function __toString()
    {
        return $this->toQueryString();
    }
}
