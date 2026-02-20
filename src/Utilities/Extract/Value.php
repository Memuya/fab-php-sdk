<?php

namespace Memuya\Fab\Utilities\Extract;

use UnitEnum;
use Exception;
use BackedEnum;
use Stringable;
use ReflectionClass;
use ReflectionException;
use Memuya\Fab\Utilities\Extract\Extractor\Extractor;
use Memuya\Fab\Utilities\Extract\Extractor\UnitEnumExtractor;
use Memuya\Fab\Utilities\Extract\Extractor\BackedEnumExtractor;
use Memuya\Fab\Utilities\Extract\Extractor\StringableExtractor;

class Value
{
    /**
     * The supported instance types that a value can be extracted from
     * and their corresponding 'extractor' class.
     *
     * @return array<class-string, class-string>
     */
    private static array $supportedTypes = [
        BackedEnum::class => BackedEnumExtractor::class,
        UnitEnum::class => UnitEnumExtractor::class,
        Stringable::class => StringableExtractor::class,
    ];

    /**
     * The 'value' that we need to try and extract from.
     *
     * @var mixed
     */
    private mixed $value;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    /**
     * Create a new instance.
     *
     * @param mixed $value
     * @return self
     */
    public static function from(mixed $value): self
    {
        return new self($value);
    }

    /**
     * Extract the value depending on its instance type.
     *
     * @return mixed
     * @throws ReflectionException
     */
    public function extract(): mixed
    {
        foreach (self::$supportedTypes as $type => $extractor) {
            if (! $this->value instanceof $type) {
                continue;
            }

            $reflection = new ReflectionClass($extractor);

            if (! $reflection->implementsInterface(Extractor::class)) {
                throw new Exception(sprintf('"%s" does not implement interface "%s"', $extractor, Extractor::class));
            }

            return $reflection
                ->newInstance($this->value)
                ->extract();
        }

        return $this->value;
    }
}
