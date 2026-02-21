<?php

namespace Memuya\Fab\Readers;

use Exception;
use ReflectionClass;
use ReflectionProperty;
use ReflectionException;
use Memuya\Fab\Utilities\Str;
use Memuya\Fab\Attributes\Filter;
use Memuya\Fab\Utilities\Extract\Value;
use Memuya\Fab\Readers\Json\Filters\Filterable;

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
     * Set up the criteria class properties from the given array.
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
     * Return the value stored in each property that has a `Filter` attribute.
     *
     * @return array<string, mixed>
     */
    public function getFilterableValues(): array
    {
        return $this->getValuesFor(Filter::class);
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
            $propertyName = $property->getName();

            if (count($property->getAttributes($attribute)) === 0) {
                continue;
            }

            if (! isset($this->{$propertyName})) {
                continue;
            }

            try {
                $data[$propertyName] = Value::from($this->{$propertyName})->extract();
            } catch (Exception) {
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

    /**
     * Return the registered filter classes for each property.
     *
     * @return list<Filterable>
     * @throws ReflectionException
     */
    public function getFilters(): array
    {
        $reflection = new ReflectionClass($this);
        $filters = [];

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $attribute = $property->getAttributes(Filter::class)[0] ?? null;

            if ($attribute === null) {
                continue;
            }

            $instance = $attribute->newInstance();
            $reflection = new ReflectionClass($instance->filterClass);

            if (! $reflection->implementsInterface(Filterable::class)) {
                continue;
            }

            $filters[] = $reflection->newInstance();
        }

        return $filters;
    }
}
