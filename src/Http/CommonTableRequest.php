<?php

namespace WendellAdriel\LaravelHut\Http;

use WendellAdriel\LaravelHut\Support\DTOs\BaseDTO;
use WendellAdriel\LaravelHut\Support\DTOs\CommonTableDTO;

class CommonTableRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            CommonTableDTO::PAGE     => ['sometimes', 'int'],
            CommonTableDTO::PER_PAGE => ['sometimes'],
            CommonTableDTO::SORT     => ['sometimes', 'array'],
            CommonTableDTO::SEARCH   => ['sometimes', 'string']
        ];
    }

    /**
     * @return BaseDTO
     */
    public function getDTO(): BaseDTO
    {
        $dto = new CommonTableDTO();

        return $dto->setPage($this->page())
            ->setPerPage($this->perPage())
            ->setSort($this->sort())
            ->setSearch($this->search())
            ->setFormat($this->format());
    }

    /**
     * @return int
     */
    protected function page(): int
    {
        return (int) $this->input(CommonTableDTO::PAGE, CommonTableDTO::DEFAULT_PAGE);
    }

    /**
     * @return int|string
     */
    protected function perPage()
    {
        return (int) $this->input(CommonTableDTO::PER_PAGE, CommonTableDTO::DEFAULT_PER_PAGE);
    }

    /**
     * @param array $default
     * @return array
     */
    protected function sort(array $default = []): array
    {
        $sort = $this->input(CommonTableDTO::SORT, $default);
        if ($sort) {
            return (array) $sort;
        }
        return $default;
    }

    /**
     * @return string|null
     */
    protected function search(): ?string
    {
        return $this->input(CommonTableDTO::SEARCH);
    }

    /**
     * @param string $default
     * @return string
     */
    protected function format(string $default = CommonTableDTO::FORMAT_JSON): string
    {
        $format = $this->route()->parameter(CommonTableDTO::FORMAT, $default);
        return ltrim($format, '.');
    }
}
