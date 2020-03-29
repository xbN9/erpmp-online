<?php
//use Doctrine\ORM\Query\ResultSetMapping;
//use DB;
//use Entities\Order;

class OrderModel {

    public static function getRow($where) {
        //$db = Registry::get('DB');
        //$db::beginTransaction();
        //$sql = 'select * from `order` limit 1,2';
        //$sql  = 'update `order` set status=4 where id=28';
        //$data = $db::connection()->select($sql);
        //die(print_r($data));
        //$db::commit();
        $where = [
            'status' => 2,
        ];
        //$res = Order::arrwhere($where)->select(['id'])->skip(1)->take(1)->get()->toArray();
        $res = Order::arrwhere($where)->select(['id'])->get()->toArray();
        die(print_r($res));
    }

    public static function toDayOrder() {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('Entities\Order', 'u');
        $rsm->addFieldResult('u', 'pay_price', 'pay_price');
        $rsm->addFieldResult('u', 'parent_order_sn', 'parent_order_sn');
        $sql   = 'select o.pay_price from order o limit 1';
        $query = self::$db_resouce->createNativeQuery($sql, $rsm);
        if (!empty($bind)) {
            $query->setParameters($bind);
        }
        $res = $query->getArrayResult();
        die(print_r($res));
    }

    public static function add(array $data): int {
        if (!isset($data['username']) || !isset($data['password']) || !isset($data['group'])) {
            return 0;
        }
        self::$db_resouce->getConnection()->beginTransaction();
        try {
            $admin           = new Admin;
            $admin->account  = $data['username'];
            $admin->password = $data['password'];
            $admin->group    = $data['group'];
            self::$db_resouce->persist($admin);
            self::$db_resouce->flush();
            self::$db_resouce->getConnection()->commit();
            return $admin->getId();
        } catch (Exception $e) {
            self::$db_resouce->getConnection()->rollback();
            self::$db_resouce->close();
            throw $e;
            return 0;
        }
        return 0;
    }
}
