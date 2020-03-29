<?php
use Entity\Brand;

class BrandModel extends BaseModel {

    public static function getRows(array $where, array $columns, int $offset, int $size): array
    {
        $where = [
            'is_test' => 0,
        ];
        $res = Brand::arrwhere($where)->select($columns)->orderBy('id', 'desc')
            ->take($size)->skip($offset)
            ->get()->toArray();
        if (empty($res)) {
            return [];
        }
        return $res;
    }

    public static function getRow(array $where, array $columns): array
    {
        $res = Brand::arrwhere($where)->select($columns)
            ->take(1)->skip(0)
            ->get()->toArray();
        if (empty($res)) {
            return [];
        }
        return $res[0];
    }
}
