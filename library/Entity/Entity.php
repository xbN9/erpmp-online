<?php
namespace Entity;

use Entity\MDWQueryBuilder;
use Illuminate\Database\Capsule\Manager as IlluminateCapsule;
use Illuminate\Database\Eloquent\Model as IlluminateModel;
use Illuminate\Support\Facades\DB;
use Yaf\Registry as YRegistry;

class Entity extends IlluminateModel {

    protected $config         = null;
    protected static $capsule = null;
    protected static $fakeApp = [];
    protected $connection     = 'default';

    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
        $this->config = YRegistry::get('db_config');
        if (!$this->config['mysql']) {
            throw new Exception("Must configure database in .ini first");
        }
        if (!self::$capsule) {
            self::$capsule = new IlluminateCapsule();
            self::$capsule->bootEloquent();
            self::$fakeApp = [
                'db' => self::$capsule->getDatabaseManager(),
            ];
            \Illuminate\Support\Facades\DB::setFacadeApplication(self::$fakeApp);
        }
        self::$capsule->addConnection($this->config['mysql'], $this->connection);
    }

    protected function newBaseQueryBuilder() {
        $conn    = $this->getConnection();
        $grammar = $conn->getQueryGrammar();
        return new MDWQueryBuilder($conn, $grammar, $conn->getPostProcessor());
    }

    /**
     * @param string $select
     * @param $where
     * @param int $offset
     * @param int $limit
     * @param int $orderby
     * @param string $order
     * @描述：公共查询
     * @用法：
     *  $where = array(
    'product_id'=>$product_id_array,
    'no_stock'=>Goods::HAS_STOCK,
    'is_delete'=>Goods::UN_DELETED,
    'is_onshelf'=>Goods::ON_SHELF,
    );
    $goods_list = Goods::getList($where,['product_id','no_stock','market_price','min(store_price) as store_price'],0,0,0,0,'product_id');//获取 goods 数据
     * @return mixed
     */
    public static function getList($where = [], $select = "*", $offset = 0, $limit = 20, $orderby = 0, $order = 'asc', $groupby = 0) {
        if (is_array($select)) {
            $new_select = join(',', $select);
        } else if (is_string($select)) {
            $new_select = $select;
        } else {
            $new_select = '*';
        }
        $model = self::selectRaw($new_select);
        if (!empty($where) && is_array($where)) {
            foreach ($where as $key => $val) {
                if (is_array($val)) {
                    $model = $model->whereIn($key, $val);
                } else {
                    $temp_key = explode(" ", $key);
                    if (count($temp_key) > 1) {
                        $model = $model->where($temp_key[0], $temp_key[1], $val);
                    } else {
                        $model = $model->where($key, $val);
                    }
                }
            }
        }
        if ($limit) {
            $model = $model->take($limit);
        }
        if ($orderby) {
            if (is_array($orderby)) {
                foreach ($orderby as $filed => $orderType) {
                    $model = $model->orderBy($filed, $orderType);
                }
            } else {
                $model = $model->orderBy($orderby, $order);
            }
        }
        if ($offset) {
            $model = $model->offset($offset);
        }
        if ($groupby) {
            $model = $model->groupBy($groupby);
        }
        //dump($model->toSql());
        $date = $model->get()->toArray();
        return $date;
    }
}
