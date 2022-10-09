<?php
/**
 * Description :
 * Article.php
 */

namespace php\models;

// 引入模型基础类
require_once 'Base.php';

// 使用类必须声明使用，并使用正确的命名空间
use php\models\Base;

/**
 * Class Article
 * 文章类，处理文章的相关操作
 *
 * @package php\controllers
 */
class Article extends Base
{
      // 这里我们用类属性的方式来接收数据
      protected $title = '';
      protected $abstract = '';
      protected $content = '';
      protected $author = '';
      protected $offset = '';
      protected $size = '';
  
      /**
       * 保存文章，新增或编辑更新
       *
       * @param $post_data
       *
       * @return bool
       * @throws \Exception
       * @author Shuixiang
       * @Last   Modified content :
       * @Last   Modified at 2019/12/2 20:52
       */
      public function save($post_data)
      {
          // 获取参数，文章标题、摘要、内容、作者
          isset($post_data['id'])         && $this->article_id = intval($post_data['id']);
          isset($post_data['title'])      && $this->title = $post_data['title'];
          isset($post_data['abstract'])   && $this->abstract = $post_data['abstract'];
          isset($post_data['content'])    && $this->content = $post_data['content'];
          isset($post_data['author'])     && $this->author = $post_data['author'];
  
          // 判断是否为空，我们要求每一个参数都不能为空，这是基本校验
          if ( !($this->title && $this->abstract && $this->content && $this->author) ) {
              throw new \Exception('表单数据不正确，请确认后重试');
          }
  
          try {
              // 获取当前时间
              $created_at = time();

              if ($this->article_id) {
                $sql = "
                        UPDATE articles SET 
                        title='{$this->title}',
                        abstract='{$this->abstract}',
                        content='{$this->content}',
                        author='{$this->author}',
                        update_time={$created_at}
                        WHERE id={$this->article_id}
                        ";
            } else { 
                // 开始写入数据，使用sql语句
                $sql = "
                INSERT INTO articles (title, abstract, content, author, create_time) 
                VALUES ('{$this->title}', '{$this->abstract}', '{$this->content}', '{$this->author}', {$created_at})
                ";
            }


     
  
  
              // 执行sql语句
              if($this->db->exec($sql) === false) {
                  throw new \Exception($this->db->errorInfo()[2]);
              }
  
              return $this->article_id ?: $this->db->lastInsertId();
  
          } catch(\Exception $e) {
              throw new \Exception($e->getMessage());
          }
  
      }


    /**
     * 获取文章详详情
     *
     * @param $get_data
     *
     * @return mixed
     * @throws \Exception
     */
      public function getDetail($get_data)
      {
          isset($get_data['id']) && $this->article_id = intval($get_data['id']);
  
          // 判断是否为空，我们要求id参数都不能为空或0，这是基本校验
          if (!$this->article_id) {
              throw new \Exception('参数错误，请确认后重试');
          }
  
          try {
              // 开始获取数据
              $sql = "
                      SELECT id,title,abstract,content,author,create_time FROM articles
                      WHERE id={$this->article_id}
                      ";
              $data = $this->db->query($sql)->fetch(\PDO::FETCH_ASSOC);
  
              if (empty($data)) {
                  throw new \Exception('文章不存在或已删除');
              }
  
              $data['time'] = date('Y-m-d H:i', $data['created_at']);
              return $data;
          } catch(\Exception $e) {
              throw new \Exception($e->getMessage());
          }
  
      }

          /**
     * 获取文章详详情
     *
     * @param $get_data
     *
     * @return mixed
     * @throws \Exception
     */
    public function getList($post_data)
    {
        isset($post_data['offset'])   && $this->offset = $post_data['offset'];
        isset($post_data['size'])     && $this->size = $post_data['size'];

        try {
            // 开始获取数据
            $sql = "
                    SELECT id, content, author, create_time FROM articles
                    ORDER BY create_time DESC LIMIT {$this->offset},{$this->size}
                    ";
                    // WHERE article_id={$article_id} 

            // 判断是否为空，我们要求每一个参数都不能为空，这是基本校验
            if ( !($this->offset && $this->size) ) {
             throw new \Exception('分页数据不正确，请确认后重试');
            }
            
            $list=$this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

            if (empty($list)) {
                throw new \Exception('无数据');
            }

            $data['time'] = date('Y-m-d H:i', $list['create_time']);
            return $list;
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }

}