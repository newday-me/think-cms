<?php
namespace core\blog\logic;

use core\Logic;
use core\blog\model\ArticleModel;

class ArticleLogic extends Logic
{

    /**
     * 增加访问次数
     *
     * @param integer $articleId            
     * @return integer
     */
    public function addVisit($articleId)
    {
        $map = [
            'id' => $articleId
        ];
        $data = [
            'article_visit' => [
                'exp',
                'article_visit + 1'
            ]
        ];
        return ArticleModel::getInstance()->where($map)->update($data);
    }

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

    /**
     * 获取类型下拉
     *
     * @return array
     */
    public function getSelectType()
    {
        return [
            [
                'name' => '文章',
                'value' => 'article'
            ],
            [
                'name' => '外链',
                'value' => 'outer'
            ],
            [
                'name' => '相册',
                'value' => 'gallery'
            ],
            [
                'name' => '专辑',
                'value' => 'album'
            ]
        ];
    }

    /**
     * 获取状态下拉
     *
     * @return array
     */
    public function getSelectStatus()
    {
        return [
            [
                'name' => '发布',
                'value' => 1
            ],
            [
                'name' => '待发布',
                'value' => 0
            ]
        ];
    }
}