<?php
use Entity\Product;
use Entity\Warehouse;

class ProductModel extends BaseModel {

    public static function getList(array $where, array $columns, int $offset, int $size): array
    {
        if (empty($where) || empty($columns)) {
            return 0;
        }
        $where_tmp = $where;
        if (isset($where['supplier'])) {
            unset($where_tmp['supplier']);
        }
        if (isset($where['name'])) {
            unset($where_tmp['name']);
        }
        $product = Product::arrwhere($where_tmp)->select($columns);
        if (isset($where['supplier'])) {
            $warehouse = Warehouse::arrwhere(['warehouse_sup_id' => $where['supplier']])
                ->select(['id'])->get()->toArray();
            if (!$warehouse) {
                return 0;
            }
            $wareArr = array_column($warehouse, 'id');
            $product->whereIn('warehouse_id', $wareArr);
        }
        if (isset($where['name'])) {
            $product->where('name', 'like', '%' . $where['name'] . '%');
        }
        $res = $product->orderBy('id', 'desc')
            ->take($size)->skip($offset)
            ->get()->toArray();
        if (empty($res)) {
            return [];
        }
        return $res;
    }

    public static function totalRow(array $where, array $columns): int {
        if (empty($where) || empty($columns)) {
            return 0;
        }
        $where_tmp = $where;
        if (isset($where['supplier'])) {
            unset($where_tmp['supplier']);
        }
        if (isset($where['name'])) {
            unset($where_tmp['name']);
        }
        $product = Product::arrwhere($where_tmp)->select($columns);
        if (isset($where['supplier'])) {
            $warehouse = Warehouse::arrwhere(['warehouse_sup_id' => $where['supplier']])
                ->select(['id'])->get()->toArray();
            if (!$warehouse) {
                return 0;
            }
            $wareArr = array_column($warehouse, 'id');
            $product->whereIn('warehouse_id', $wareArr);
        }
        if (isset($where['name'])) {
            $product->where('name', 'like', '%' . $where['name'] . '%');
        }
        $res = $product->orderBy('id', 'desc')->get()->toArray();
        if (empty($res)) {
            return 0;
        }
        return count($res);
    }

}
