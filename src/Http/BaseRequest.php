<?php

namespace WendellAdriel\LaravelHut\Http;

use Illuminate\Foundation\Http\FormRequest;
use WendellAdriel\LaravelHut\Support\DTOs\BaseDTO;
use WendellAdriel\LaravelHut\Support\Formatter;

abstract class BaseRequest extends FormRequest
{
    protected Formatter $formatterObject;

    /**
     * @return BaseDTO
     */
    abstract public function getDTO(): BaseDTO;

    /**
     * @return Formatter
     */
    protected function formatter(): Formatter
    {
        if (is_null($this->formatterObject)) {
            $this->formatterObject = new Formatter();
        }

        return $this->formatterObject;
    }
}
