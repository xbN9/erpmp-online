<?php
use Entity\Admin;

class AdminModel extends BaseModel {

    public static function getRow(array $where, array $columns): array
    {
        $res = Admin::arrwhere($where)->select($columns)
            ->take(1)->skip(0)
            ->get()->toArray();
        if (empty($res)) {
            return [];
        }
        return $res[0];
    }
}
