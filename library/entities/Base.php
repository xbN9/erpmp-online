<?php
namespace Entities;
//use Illuminate\Database\Capsule\Manager as IlluminateCapsule;
use Entities\MDWQueryBuilder;
use Illuminate\Database\Eloquent\Model;

//use Yaf\Registry as YRegistry;

class Base extends Model {
    /*
    protected $config  = null;
    protected $capsule = null;

    public function __construct(array $attributes = array()) {
    parent::__construct($attributes);
    $dbConfigKey  = DATABASE_CONFIG_KEY;
    $this->config = YRegistry::get('config');
    if (!$this->config->$dbConfigKey) {
    throw new Exception("Must configure database in .ini first");
    }
    $this->config  = $this->config->$dbConfigKey->toArray();
    $this->capsule = new IlluminateCapsule();
    $this->capsule->addConnection($this->config);
    $this->capsule->bootEloquent();
    //TODO 放到bootstrap 中做连接
    Illuminate\Support\Facades\DB::setFacadeApplication([
    'db' => $this->capsule->getDatabaseManager(),
    ]);
    }*/

    protected function newBaseQueryBuilder() {
        $conn    = $this->getConnection();
        $grammar = $conn->getQueryGrammar();
        return new MDWQueryBuilder($conn, $grammar, $conn->getPostProcessor());
    }
}
