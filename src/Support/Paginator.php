<?php

namespace WendellAdriel\LaravelHut\Support;

use Illuminate\Support\Enumerable;
use WendellAdriel\LaravelHut\Support\DTOs\CommonTableDTO;

class Paginator
{
    /**
     * Manually paginates a collection of items
     *
     * @param Enumerable     $items - The filtered
     * @param int            $total - The unfiltered collection count
     * @param CommonTableDTO $dto   - The DTO with the page params
     * @return array
     */
    public static function manualPaginate(Enumerable $items, int $total, CommonTableDTO $dto): array
    {
        $itemsTotal = $items->count();
        $data       = $dto->getAll() ? $items->all() : $items->forPage(
            $dto->getPage(),
            $dto->getPerPage()
        )->values()->all();
        $pageCount  = $dto->getAll() ? 1 : (int)ceil($itemsTotal / $dto->getPerPage());

        return Paginator::formatPagination($data, $pageCount, $itemsTotal, $total);
    }

    /**
     * Manually paginates a collection of streamed items
     * Avoids to transform LazyCollections into arrays
     *
     * @param Enumerable     $items
     * @param int            $total
     * @param CommonTableDTO $dto
     * @param bool           $getTotalCount
     * @return array
     */
    public static function manualPaginateStream(
        Enumerable $items,
        int $total,
        CommonTableDTO $dto,
        bool $getTotalCount = false
    ): array {
        $itemsTotal = $getTotalCount ? $total : $items->count();
        $data       = $dto->getAll() ? $items : $items->forPage($dto->getPage(), $dto->getPerPage())->values();
        $pageCount  = $dto->getAll() ? 1 : (int)ceil($itemsTotal / $dto->getPerPage());

        return Paginator::formatPagination($data, $pageCount, $itemsTotal, $total);
    }

    /**
     * @param array $data
     * @param int   $pageCount
     * @param int   $itemsTotal
     * @param int   $total
     * @return array
     */
    public static function formatPagination(array $data, int $pageCount, int $itemsTotal, int $total): array
    {
        return [
            'data'       => $data,
            'extra'      => [],
            'pagination' => [
                'page_count' => $pageCount,
                'total'      => $itemsTotal,
                'total_all'  => $total
            ],
        ];
    }
}
