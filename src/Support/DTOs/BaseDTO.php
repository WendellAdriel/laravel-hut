<?php

namespace WendellAdriel\LaravelHut\Support\DTOs;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

abstract class BaseDTO implements Arrayable
{
    /**
     * @param array $data
     * @return BaseDTO|static
     */
    public static function createFromArray(array $data)
    {
        $dto = new static();
        foreach ($data as $key => $value) {
            $methodName = Str::camel('set_' . Str::snake($key));
            if (method_exists($dto, $methodName)) {
                $dto->$methodName($value);
            }
        }

        return $dto;
    }

    /**
     * @return static
     */
    public static function make(): self
    {
        return new static();
    }

    /**
     * Get a copy of DTO variables to replicate into another DTO
     *
     * @return array
     */
    public function getCopy(): array
    {
        return get_object_vars($this);
    }
}
