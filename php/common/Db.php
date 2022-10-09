<?php
/**
 * Description :
 * Db.php
 */

namespace php\common;

use PDO;

/**
 * Class Db
 * 数据库管理类
 *
 * @package php\common
 */
class Db
{
    // 链接数据库必须的参数
    protected $host = '127.0.0.1'; # 数据库地址
    protected $user = 'root'; # 用户名
    protected $pass = '123456'; # 密码
    protected $select_db = 'example'; # 数据库名

    // 数据库连接实例
    public static $pdo_con = null;

    /**
     * Db constructor.
     * 构造方法，类实例化首先会执行的方法，进来就执行的
     * 我们定义成private私有，是为了实现单例模式，拒绝类外部的代码直接实例化本类
     * @param $host  数据库地址
     * @param $user  用户名
     * @param $pass  密码
     * @param $select_db  数据库名
     */
    private function __construct($host, $user, $pass, $select_db)
    {
        $host && $this->host = $host;
        $user && $this->user = $user;
        $pass && $this->pass = $pass;
        $select_db && $this->select_db = $select_db;
    }

    private function __clone()

    {

        return self::getInstance($this->host, 
                          $this->user, 

                          $this->pass, 

                          $this->select_db);

    }

    /**
     * 获取pdo连接db实例
     * @param $host
     * @param $user
     * @param $pass
     * @param $select_db
     *
     * @return PDO
     * @throws \Exception
     * @author Shuixiang
     * @Last   Modified content :
     * @Last   Modified at 2019/12/2 1:05
     */
    public static function getInstance($host, $user, $pass, $select_db)
    {

        // 如果连接实例已经存在直接返回
        if (static::$pdo_con) {
            return self::$pdo_con;
        }

        // 如果连接实例不存在，我们就去实例化一下
        // 参数的基础校验
        if ( !($host && $user && $pass && $select_db) ) {
            throw new \Exception('数据库链接参数有误');
        }

        // 实例化，调用构造方法，设置相关参数
        $db = new self($host, $user, $pass, $select_db);
        // 连接数据库
        $db->_connect();

        // 返回数据库连接
        return self::$pdo_con;
    }

    /**
     * pdo连接mysql数据库并返回pdo实例，长连接
     *
     * @return void
     * @throws \Exception
     * @author Shuixiang
     * @Last   Modified content :
     * @Last   Modified at 2019/12/2 1:06
     */
    protected function _connect()
    {
        try {

            self::$pdo_con = new PDO(
                "mysql:host={$this->host};dbname={$this->select_db}",
                $this->user,
                $this->pass,
                [PDO::ATTR_PERSISTENT => true]
            );

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}