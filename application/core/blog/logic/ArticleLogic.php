<?php
namespace core\blog\logic;

use core\Logic;
use core\blog\model\ArticleModel;

class ArticleLogic extends Logic
{

    /**
     * 获取记录
     *
     * @param integer $articleId            
     *
     * @return array
     */
    public function getRecord($articleId)
    {
        $model = ArticleModel::getInstance();
        $map = [
            'id' => $articleId
        ];
        $record = $model->where($map)->find();
        
        // 分类
        $articleCates = [];
        foreach ($record->cates as $vo) {
            $articleCates[] = $vo['id'];
        }
        $record['article_cates'] = $articleCates;
        
        // 标签
        $articleTags = [];
        foreach ($record->tags as $vo) {
            $articleTags[] = $vo['tag_name'];
        }
        $record['article_tags'] = implode(',', $articleTags);
        
        return $record;
    }

    /**
     * 新增文章
     *
     * @param array $data            
     * @param array $articleCates            
     * @param string $articleTags            
     *
     * @return integer
     */
    public function addArticle($data, $articleCates = [], $articleTags = '')
    {
        $model = ArticleModel::getInstance();
        $status = $model->save($data);
        
        if ($status) {
            // 文章ID
            $articleId = $model['id'];
            
            // 关联分类
            $this->attachArticleCates($articleId, $articleCates);
            
            // 关联标签
            $this->attachArticleTags($articleId, $articleTags);
        }
        
        return $status;
    }

    /**
     * 修改文章
     *
     * @param array $data            
     * @param array $map            
     * @param array $articleCates            
     * @param string $articleTags            
     *
     * @return integer
     */
    public function saveArticle($data, $articleId, $articleCates = [], $articleTags = '')
    {
        $model = ArticleModel::getInstance();
        $map = [
            'id' => $articleId
        ];
        $status = $model->save($data, $map);
        
        // 关联分类
        $this->attachArticleCates($articleId, $articleCates);
        
        // 关联标签
        $this->attachArticleTags($articleId, $articleTags);
        
        return $status;
    }

    /**
     * 关联分类
     *
     * @param integer $articleId            
     * @param array $articleCates            
     *
     * @return void
     */
    public function attachArticleCates($articleId, $articleCates = [])
    {
        is_array($articleCates) || $articleCates = array_filter(explode(',', $articleCates));
        
        // 保存关联
        $article = ArticleModel::get($articleId);
        $article->cates()->detach();
        return $article->cates()->attach($articleCates);
    }

    /**
     * 关联标签
     *
     * @param integer $articleId            
     * @param mixed $articleTags            
     *
     * @return void
     */
    public function attachArticleTags($articleId, $articleTags)
    {
        is_array($articleTags) || $articleTags = array_filter(explode(',', $articleTags));
        
        // 保存标签
        $tagLogic = ArticleTagLogic::getSingleton();
        $tagIds = $tagLogic->saveTagBatch($articleTags);
        
        // 保存关联
        $article = ArticleModel::get($articleId);
        $article->tags()->detach();
        return $article->tags()->attach($tagIds);
    }

}