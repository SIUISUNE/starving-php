<?php

namespace Starving\Laravel;

class Builder extends \Illuminate\Database\Eloquent\Builder
{
    /**
     * @param int|null $page_size
     * @param array|null $columns
     * @param int|null $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function page(?int $page_size = null, ?array $columns = ['*'], ?int $page = null)
    {
        $get_size = config('laravel-starving-php.builder.page_size', '30');
        $get_page = config('laravel-starving-php.builder.page', 'page');
        $get_page_size = config('laravel-starving-php.builder.page_size', 'page_size');
        $get_list_name = config('laravel-starving-php.builder.list_name', 'list');

        $columns = empty($columns) ? ['*'] : $columns;
        if (empty($page)) {
            $page = request()->input($get_page);
        }
        if (is_numeric($page_size)) {
            $page_size = max(1, $page_size);
        } else {
            $page_size = request()->input($get_page_size);
        }
        if (!is_numeric($page_size)) {
            $page_size = max(1, $get_size);
        }

        $pageObj = $this->paginate($page_size, $columns, 'current_page', $page);

        //code
        $collection = $pageObj;
        $collection = $collection->toArray();

        $collection[$get_list_name] = $collection['data'];
        $collection[$get_page_size] = $collection['per_page'];

        $unset = config('laravel-starving-php.builder.unset');
        if (!empty($unset)) {
            foreach ($unset as $v) {
                if ($v != $get_list_name && $v != $get_page_size) {
                    unset($collection[$v]);
                }
            }
        }

        return $collection;
    }
}