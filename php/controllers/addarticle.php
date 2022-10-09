<?php
/**
 * Description :
 * addarticle.php
 */

require_once '../models/Article.php';
use php\models\Article;

// 获取前端提交过来的post参数，并实例化一个文章对象，把数据传进去
$article = new Article();

try {
    $id = $article->save($_POST);
    echo json_encode([
        'status'=> 'success',
        'msg'   => '文章发布成功',
        'data'  => ['article' => ['id' => $id]]
    ]);
    die;
} catch (\Exception $e) {
    echo json_encode([
        'status'=> 'fail',
        'msg'   => $e->getMessage()
    ]);
    die;
}