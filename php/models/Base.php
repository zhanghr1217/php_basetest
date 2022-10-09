<?php
/**
 * Description :
 * Base.php
 */

namespace php\models;

require_once dirname(__FILE__).'/../common/Db.php';

use php\common\Db;

/**
 * Class Base
 * 基类，主要处理了数据库连接
 *
 * 请注意，我们本次实例所有内容讲解为主，并未考虑相关数据安全
 * 比如包括但不仅限于xss注入、sql注入等
 * 请勿用作正式用途
 * 一般用户传递过来的数据，我们需要进行相对应的过滤后才放入数据库
 *
 * @package php\controllers
 */
class Base
{
    // 默认的数据库连接参数，我们在mysql命令行连接的时候也需要这些参数
    protected $config = [
        'host'  => '127.0.0.1', // 数据库地址，本地一般就是这个了
        'user'  => 'root', // 数据库用户名
        'pass'  => '123456', // 数据库密码
        'dbname'=> 'example' // 数据库名
    ];

    // 数据库连接实例
    public $db = null;

    // 类的构造方法，每个类进来就会先执行这个方法
    public function __construct()
    {

        try {
            $this->db = Db::getInstance($this->config['host'], $this->config['user'], $this->config['pass'], $this->config['dbname']);
        } catch (\Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

}