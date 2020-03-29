<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sky
 * Date: 9/16/14
 * Time: 6:42 PM
 * email:doifusdsky@gmail.com
 */
class BaseMongo {
    //Mongodb连接     
    protected $mongo;
    //当前数据库名 
    protected $curr_db_name;
    //当前表名     
    protected $curr_table_name;
    //错误     
    protected $error;
    //最后一条插入的id
    public static $last_ins_id;
    //获取最后一条语句
   	protected static $sql;
    //存储对象
    private static $_instance;
	//性能数组
	protected $explain = array();
	//调试语句
	public $get_last_sql = '';

    /**   
    * 构造函数   
    * 多个数据库连接
    * 参数：
    * $mongo_server:数组或字符串-array("127.0.0.1:1111", "127.0.0.1:2222")-"127.0.0.1:1111"   
    * $connect:初始化mongo对象时是否连接，默认连接   
    * $auto_balance:是否自动做负载均衡，默认是   
    * 返回值：
    * 成功：mongo object   
    * 失败：false   
    */
	protected function __construct(){
    	//导入配置文件
		ini_set('mongo.native_long', 0);
		$config = Yaf_Registry::get("config")->mongoDB->toArray();
    	$mongo_host = $config['config']['host'];
	    $this->curr_db_name = $config['config']['mongo_db'];
	    $connect = true;
		//$memIP = $config['memcache_host'];
		//$memPort = '11211';
		//连接memcache获取主服务器的ip地址
		//$m1 = new Memcached();
		//$m1->addServer($memIP,$memPort);

		//mongo的主服务器ip
		//$mongoMasterIp = $m1->get('MONGO_MASTER');
//		$mongoMasterIp = S('MONGO_MASTER');
//		$configmc['replicaSet'] = 'mongoshard1';
//		$configmc['slaveOkay'] = true;
		//$configmc['timeout'] = 15;
		//$configmc['socketTimeoutMS'] = 30;
		//如果这个结果不存在，检测获取
	   
		$mongo_server = 'mongodb://'.$mongo_host.'/'.$this->curr_db_name;

		try {
			$this->mongo = new MongoClient($mongo_server, array('connect'=>$connect));
		}catch (MongoConnectionException $e){
			$this->error = $e->getMessage();
			return false;
		}
		/*if($mongoMasterIp === false){
			if(is_array($mongo_host)){
				$len = count($mongo_host);
				$i = 0;
				while($len > $i){
					$hostIP = "mongodb://".$mongo_host[$i];
					try {
						$this->mongo = new MongoClient($hostIP, $config);
						$slave = $this->mongo->getConnections();
						foreach ($slave as $key => $value) {
							if($value['connection']['connection_type_desc']=='PRIMARY'){
								$mongoMasterIp = $value['server']['host'];
								//S('MONGO_MASTER',$mongoMasterIp,30);
									goto end;
								break;
							}
						}

					}catch (MongoConnectionException $e){
						$this->error = $e->getMessage();
						return false;
					}
					++$i;
				}
			}else{
			  	if(isset($config['mongo_user']) && isset($config['mongo_pwd'])){
				    $mongo_server = 'mongodb://'.$config['mongo_user'].':'.$config['mongo_pwd'].'@'.$mongo_host.'/'.$config['db'];
			    }else{
				    $mongo_server = 'mongodb://'.$mongo_host.'/'.$config['db'];
			    }
				try {
					$this->mongo = new MongoClient($mongo_server, array('connect'=>$config['connect']));
		    	}catch (MongoConnectionException $e){
		    		$this->error = $e->getMessage();
		    		return false;
		    	}
    		}
		}else{
			end:
			//S('MONGO_MASTER',$mongoMasterIp,30);
			try {
				$this->mongo = new MongoClient($mongoMasterIp, array('connect'=>$config['connect']));
			}catch (MongoConnectionException $e){
				$this->error = $e->getMessage();
				return false;
			}
		}*/
    }

	/**
	 * @return BaseMongo
	 */
	public  static function getInstance(){
    	if(FALSE == (self::$_instance instanceof self)){
			self::$_instance = new self;
		}
		return self::$_instance;	
	}

	private function __clone(){}


	/**连接mongodb server  测试用
	 * @return bool
	 */
	 public function connect(){
		 try {
			 $this->mongo->connect();
			 return true;
		 }catch (MongoConnectionException $e){
			 $this->error = $e->getMessage();
			 return false;
		 }
	 }


	/**创建索引：如索引已存在，则返回
	 * @param $table_name 表名
	 * @param array $index 索引-array("id"=>1)-在id字段建立升序索引
	 * @param array $index_param 其它条件-是否唯一索引等
	 * @return bool
	 */
	public function addIndex($table_name, $index, array $index_param = array()){
         $db_name = $this->curr_db_name;
         try {
             $this->mongo->$db_name->$table_name->ensureIndex($index, $index_param);
             return true;
         }catch (MongoCursorException $e){
             $this->error = $e->getMessage();
             return false;
         }
	}

	/**获得索引
	 * @param $table_name
	 * @return array|bool
	 */
	public function findIndex($table_name){
		$db_name = $this->curr_db_name;

		$i = $this->mongo->$db_name->$table_name->getIndexInfo();
		if($i[1]){
			return $i;
		}else{
			return false;
		}
	}

     
	/**插入记录
	* @param $table_name 表名
	* @param array $record 数据
	* @return bool
	*/
	public function insert($table_name, array $record,$lid = ''){

		$db_name = $this->curr_db_name;
		try{
			$this->get_last_sql = $this->mongo.'->'.$db_name.'->'.$table_name.'->insert('.json_encode($record).')';
			$result = $this->mongo->$db_name->$table_name->insert($record);
			if($result) {
				if($lid){
					BaseMongo::$last_ins_id = $record[$lid];
				}else{
					$_id = $record['_id'];
					if(is_object($_id)) {
						$_id = $_id->__toString();
					}
					BaseMongo::$last_ins_id = $_id;
				}
			}
			return true;
		}catch (MongoCursorException $e){
			$this->error = $e->getMessage();
			return false;
		}
	}

	/**获得最后插入id
	 * @return mixed
	 */
	public function lastInsId(){
		return BaseMongo::$last_ins_id;
	}

	/**全部插入
	 * @param $table_name
	 * @param array $data
	 * @param array $options
	 * @return bool|mixed
	 */
	public function insertAll($table_name, array $data,array $options = array()){
		$db_name = $this->curr_db_name;
		try{
			$this->get_last_sql = $this->mongo.'->'.$db_name.'->'.$table_name.'->batchInsert('.json_encode($data).','.json_encode($options).')';
			return $this->mongo->$db_name->$table_name->batchInsert($data,$options);
		}catch (MongoCursorException $e){
			$this->error = $e->getMessage();
			return false;
		}
	}

	/**查询表的记录数
	 * @param $table_name 表名
	 * @param array $where 条件
	 * @return bool|int
	 * TODO 反应反最相应的字段先创建索引
	 */
	public function counts($table_name, array $where = array()){
		$db_name = $this->curr_db_name;
		try{
			$this->get_last_sql = $this->mongo.'->'.$db_name.'->'.$table_name.'->count('.json_encode($where).')';
			return $this->mongo->$db_name->$table_name->count($where);
		}catch (MongoCursorException $e){
			$this->error = $e->getMessage();
			return false;
		}
	}

	/**更新记录
	 * @param $table_name 表名
	 * @param array $condition 更新条件
	 * @param array $new_data 新的数据记录
	 * @param array $options 更新选择-upsert/multiple
	 * @return bool
	 */
	public function update($table_name,array $condition, array $new_data, array $options=array()){

         $db_name = $this->curr_db_name;
         if (!isset($options['multiple'])){
             $options['multiple'] = false;
         }
         try{
             $this->mongo->$db_name->$table_name->update($condition, $new_data, $options);
	         $this->get_last_sql = $this->mongo.'->'.$db_name.'->'.$table_name.'->update('.json_encode($condition).', '.json_encode($new_data).', '.json_encode($options).')';
             return true;
         }catch (MongoCursorException $e){
             $this->error = $e->getMessage();
             return false;
         }
	}

	/**删除记录
	 * @param $table_name 表名
	 * @param array $condition 删除条件
	 * @param array $options 删除选择-justOne
	 * @return bool
	 */
	public function remove($table_name, array $condition, array $options=array()){
		$db_name = $this->curr_db_name;
		try {
			$this->mongo->$db_name->$table_name->remove($condition, $options);
			return true;
		}catch (MongoCursorException $e){
			$this->error = $e->getMessage();
			return false;
		}
	}

	/**查找记录
	 * @param $table_name 表名
	 * @param array $query_condition 字段查找条件
	 * @param array $result_condition 查询结果限制条件-limit/sort等
	 * @param array $fields 获取字段
	 * @return array|bool
	 */
	public function find($table_name, array $query_condition = array(), array $result_condition = array(), array $fields=array()){
		$db_name = $this->curr_db_name;
		$cursor = '';
		if(!empty($this->mongo) && !empty($db_name) && !empty($table_name)){

			if(count($query_condition)>0 || count($fields)>0){
				$this->get_last_sql = $this->mongo."->".$db_name."->".$table_name."->find(".json_encode($query_condition).",".json_encode($fields).")";
				$cursor = $this->mongo->$db_name->$table_name->find($query_condition, $fields);
			}elseif(count($query_condition) === 0 && count($fields)>0){
				$cursor = $this->mongo->$db_name->$table_name->find(array(),$fields);
				$this->get_last_sql = $this->mongo."->".$db_name."->.".$table_name."->find(".json_encode(array()).",".json_encode($fields).")";
			}elseif(count($query_condition) === 0 && count($fields) === 0){
				$cursor = $this->mongo->$db_name->$table_name->find();
				$this->get_last_sql = $this->mongo."->".$db_name."->.".$table_name."->find()";
			}

			if(!empty($result_condition['start'])){
				$cursor->skip($result_condition['start']);
				$this->get_last_sql = $this->get_last_sql."->skip(".$result_condition['start'].")";
			}
			if(!empty($result_condition['limit'])){
				$cursor->limit($result_condition['limit']);
				$this->get_last_sql = $this->get_last_sql."->limit(".$result_condition['limit'].")";

			}
			
			if(!empty($result_condition['order'])){
				$cursor->sort($result_condition['order']);
				$this->get_last_sql = $this->get_last_sql."->sort(".json_encode($result_condition['order']).")";
			}

			$result = array();		
			try{
				if(isset($cursor)){
					$cursor->timeout(-1);
					
					foreach ($cursor as $key => $value) {
						$result[] = $value;
					}
					
					// while($cursor->hasNext()){
					// 	$result[] = $cursor->getNext();
					// }
					
					// if($cursor->explain()){
					// 	$tmp = $cursor->explain();
					// 	$expl['cursor'] = $tmp['cursor'];
					// 	$expl['n'] = $tmp['n'];
					// 	$expl['nscanned'] = $tmp['nscanned'];
					// 	$expl['nscannedObjectsAllPlans'] = $tmp['nscannedObjectsAllPlans'];
					// 	$expl['nscannedAllPlans'] = $tmp['nscannedAllPlans'];
					// 	$expl['scanAndOrder'] = $tmp['scanAndOrder'];
					// 	$expl['nChunkSkips'] = $tmp['nChunkSkips'];
					// 	$expl['millis'] = $tmp['millis'];
					// 	$this->explain = $expl;
					// }
					
					return $result;

				}else{
					return false;
				}
			}catch (MongoConnectionException $e){
				$this->error = $e->getMessage();
				return false;
			}catch (MongoCursorTimeoutException $e){
				$this->error = $e->getMessage();
				return false;
			}
		}else{
			return false;
		}
	}
     
	/**查找一条记录
	 * @param $table_name 表名
	 * @param array $condition 查找条件
	 * @param array $fields 获取字段
	 * @return array|bool|null
	 */
	public function findOne($table_name, array $condition, array $fields=array()){
		$db_name = $this->curr_db_name;
		try {
			$this->get_last_sql = $this->mongo."->".$db_name."->".$table_name."->findOne(".json_encode($condition).",".json_encode($fields).")";
			return $this->mongo->$db_name->$table_name->findOne($condition, $fields);
		}catch (MongoConnectionException $e){
			$this->error = $e->getMessage();
			return false;
		}
	}

	/**分组
	 * @param $table_name 表名
	 * @param array $keys 分组对象,例:类别分组array("category" => 1);
	 * @param array $initial 分组结果存放于此array("items" => array(),'count'=>0);
	 * @param $reduce 分组执行的js代码"function (obj, prev) { prev.items.push(obj.name); prev.count++;}"
	 * @param array $options
	 * @return array|bool
	 */
	public function group($table_name, array $keys, array $initial, $reduce, array $options = array()){
		$db_name = $this->curr_db_name;
		try {
			$this->get_last_sql = $this->mongo.'->'.$db_name.'->'.$table_name.'->group('.json_encode($keys).', '.json_encode($initial).', '.json_encode($reduce).','.json_encode($options).')';
			return $this->mongo->$db_name->$table_name->group($keys, $initial, $reduce,$options);
		}catch (MongoCursorException $e){
			$this->error = $e->getMessage();
			return false;
		}
	}

	/**获取下一个字段值
	 * @param $table_name 表名
	 * @param array $condition 查询的字段
	 * @param array $fields 查询字段的排序
	 * @return bool
	 */
	public function getNextId($table_name, array $condition, array $fields=array()){
		$db_name = $this->curr_db_name;

		try {
			$cursor = $this->mongo->$db_name->$table_name->find(array(),$fields)->sort($condition)->limit(1);
			$this->get_last_sql =  $this->mongo.'->'.$db_name.'->'.$table_name.'->find(array(),'.implode($fields).')'.'->sort('.implode($condition).')->limit(1);';
			$data = $cursor->getNext();
			return isset($data[key($fields)])?$data[key($fields)]+1:1;
			//return isset($data[key($condition)])?$data[key($condition)]+1:1;

		}catch (MongoConnectionException $e){
			$this->error = $e->getMessage();
			return false;
		}
	}

	/**执行语句
	 * @param array $command sql指令
	 * @param array $options 参数
	 * @return array|bool
	 */
	public function query(array $command, array $options=array()) {
	     $db_name = $this->curr_db_name;
	     try {
		     $this->get_last_sql = $this->mongo.'->'.$db_name.'->command('.json_encode($command).','.json_encode($options).')';
		     return $this->mongo->$db_name->command($command,$options);
	     }catch (MongoConnectionException $e){
		     $this->error = $e->getMessage();
		     return false;
	     }
	}

	/**性能分析
	 * @return array
	 */
	public function explains(){
		/**
		 * cursor:如果后面是BtreeCursor后面是索引，表示本次查询使用了索引
		 * isMultiKey:表示本次查询是否使用了多健索引
		 * n：本次查询返回的文档数
		 * nscannedObjects：按照索引查找项次数，如果不是索引字段，依次查找每个字段数
		 * nscanned:查找过的索引条数,如果是全表查找，就是查找过文档数
		 * scanAndOrder:是否排序
		 * indexOnly：是否只使用所哦应就能完成查询
		 * nYields:写亲球就会暂停,写请求次数
		 * millis:查询消耗毫秒数
		 * 注：结果集占原集合的比重越大，索引越慢，
		 */
		var_dump($this->explain);
	}

	/**创建表
	 * @param $table
	 * @return mixed
	 */
	public function createTable($table_name){
		$db_name = $this->curr_db_name;
		return $this->mongo->$db_name->createCollection($table_name);
	}

	/**
	 * 获得最后一个错误语句
	 */
	public function getLastError(){
		$this->curr_db_name->resetError();
		$this->error = $this->curr_db_name->lastError();
	}

	/**获取当前错误信息
	 * @return string
	 */
	public function getError(){
		return $this->error;
	}

	/**
	 * @return string
	 */
	public function findLastSql(){
		return $this->get_last_sql;
	}

	public function __destruct(){
		$this->mongo = null;
	}
}
