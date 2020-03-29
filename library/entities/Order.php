<?php
namespace Entities;
use Entities\Base;

class Order extends Base {

    protected $primaryKey = 'id';

    /**
     *数据连接名
     *
     */
    //protected $connection = 'mdw';

    /**
     * Indicates if the model should be timestamped. 不使用自己维护插入及更新时间
     *
     * @var  bool
     */
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var  string
     */
    protected $table = 'order';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var  array
     */
    protected $hidden = [];

    /**
     *获取表名
     *
     */
    public static function tableName() {
        return 'order';
    }
}
