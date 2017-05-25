<?php
namespace core\blog\logic;

use core\Logic;
use core\blog\model\ArticleTagModel;

class ArticleTagLogic extends Logic
{

    /**
     * 批量保存标签
     *
     * @param unknown $tagNames            
     */
    public function saveTagBatch($tagNames)
    {
        $ids = [];
        foreach ($tagNames as $tagName) {
            $ids[] = $this->saveTag($tagName);
        }
        return $ids;
    }

    /**
     * 保存标签
     *
     * @param string $tagName            
     *
     * @return mixed
     */
    public function saveTag($tagName)
    {
        $map = [
            'tag_name' => $tagName
        ];
        $model = ArticleTagModel::getInstance();
        $record = $model->where($map)->find();
        if (empty($record)) {
            $data = [
                'tag_name' => $tagName
            ];
            $model->save($data);
            return $model['id'];
        } else {
            return $record['id'];
        }
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
                'name' => '启用',
                'value' => 1
            ],
            [
                'name' => '停用',
                'value' => 0
            ]
        ];
    }
}