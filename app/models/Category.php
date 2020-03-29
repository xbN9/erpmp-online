<?php
use Entity\Category;

class CategoryModel extends BaseModel {

    public static function getRows(array $where, array $columns, int $offset, int $size): array
    {
        $res = Category::arrwhere($where)->select($columns)->orderBy('id', 'desc')
            ->take($size)->skip($offset)
            ->get()->toArray();
        if (empty($res)) {
            return [];
        }
        return $res;
    }

    public static function getRow(array $where, array $columns): array
    {
        $res = Category::arrwhere($where)->select($columns)
            ->take(1)->skip(0)
            ->get()->toArray();
        if (empty($res)) {
            return [];
        }
        return $res[0];
    }

    public static function getList(): array{
        $where   = ['is_delete' => 1];
        $columns = ['id', 'parent_id', 'level', 'category_name'];
        $res     = Category::arrwhere($where)->select($columns)->get()->toArray();
        if (empty($res)) {
            return [];
        }
        $tmp = [];
        foreach ($res as $value) {
            $tmp[$value['parent_id']][$value['id']]['id']   = $value['id'];
            $tmp[$value['parent_id']][$value['id']]['name'] = $value['category_name'];
        }
        $data = [];
        foreach ($res as $val) {
            if (isset($tmp[$val['id']])) {
                $data[$val['id']]['id']   = $val['id'];
                $data[$val['id']]['name'] = $val['category_name'];
                $data[$val['id']]['list'] = $tmp[$val['id']];
            }
        }
        if (!empty($data)) {
            $key_1   = array_column($tmp[0], 'id');
            $key_2   = array_column($data, 'id');
            $key_tmp = array_diff($key_1, $key_2);
            $data    = array_values($data);
            if (!empty($key_tmp)) {
                foreach ($key_tmp as $v) {
                    $data[] = $tmp[0][$v];
                }
            }
        }
        return $data;
    }
}
