<?php
use Entity\WarehouseProduct;
use Entity\WarehouseSupplier;

class WarehouseProductModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    public static function getList(array $columns, int $offset, int $size): array
    {
        $where = [
            'is_test' => 0,
        ];
        $res = WarehouseProduct::arrwhere($where)->select($columns)->orderBy('id', 'desc')
            ->take($size)->skip($offset)
            ->get()->toArray();
        if (empty($res)) {
            return [];
        }
        return $res;
    }

    public static function getProductStock(array $where, array $columns) {
        if (empty($where) || empty($columns)) {
            return [];
        }
        $wproduct = WarehouseProduct::arrwhere(['status' => 1])->select($columns);
        $wproduct->whereIn('product_id', $where);
        $res = $wproduct->get()->toArray();
        if (empty($res)) {
            return [];
        }
        return $res;
    }

    public static function getRow(array $where, array $columns): array
    {
        $res = WarehouseProduct::arrwhere($where)->select($columns)
            ->take(1)->skip(0)
            ->get()->toArray();
        if (empty($res)) {
            return [];
        }
        return $res[0];
    }

    public static function getProductSuppiler(string $where): array
    {
        /*$warehouse = Warehouse::arrwhere($where)->select($columns)
        ->take(1)->skip(0)
        ->get()->toArray();
        if (empty($warehouse)) {
        return [];
        }
        $where = ['id' => $warehouse[0]['warehouse_sup_id']];
        $res   = WarehouseSupplier::arrwhere($where)->select(['name'])
        ->take(1)->skip(0)
        ->get()->toArray();
        if (empty($res)) {
        return [];
        }*/

        $sql = 'select w.id,ws.name from warehouse w join warehouse_supplier ws on ';
        $sql .= 'w.warehouse_sup_id=ws.id where w.id in (' . $where . ')';
        echo $sql;die;
        $res = self::$dbconn::connection()->select($sql);
        die(print_r($res));

        return $res[0];
    }

    public static function getSuppiler(): array
    {
        $where = ['status' => 1];
        $res   = WarehouseSupplier::arrwhere($where)->select(['id', 'name'])->get()->toArray();
        if (empty($res)) {
            return [];
        }
        return $res;
    }
}
