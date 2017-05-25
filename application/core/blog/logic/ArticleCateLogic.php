<?php
namespace core\blog\logic;

use core\Logic;
use core\blog\model\ArticleCateModel;

class ArticleCateLogic extends Logic
{

    /**
     * 获取分类嵌套
     *
     * @return array
     */
    public function getCateNest()
    {
        $list = ArticleCateModel::getInstance()->order('cate_sort desc')->column('*', 'id');
        
        $tree = [];
        foreach ($list as $vo) {
            $catePid = $vo['cate_pid'];
            if (! isset($tree[$catePid])) {
                $tree[$catePid] = [];
            }
            
            $tree[$catePid][] = $vo;
        }
        
        return [
            'list' => $list,
            'tree' => $tree
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
                'name' => '启用',
                'value' => 1
            ],
            [
                'name' => '禁用',
                'value' => 0
            ]
        ];
    }

    /**
     * 获取 列表下拉
     *
     * @return array
     */
    public function getSelectList()
    {
        $nest = $this->getCateNest();
        $tree = $nest['tree'];
        
        $list = [
            [
                'name' => '无',
                'value' => 0
            ]
        ];
        if (isset($tree[0])) {
            foreach ($tree[0] as $vo) {
                $list[] = [
                    'name' => $vo['cate_title'],
                    'value' => $vo['id']
                ];
            }
        }
        
        return $list;
    }

    /**
     * 获取列表下拉-文章
     *
     * @return array
     */
    public function getSelectListForArticle()
    {
        $nest = $this->getCateNest();
        $tree = $nest['tree'];
        
        $list = [];
        foreach ($tree[0] as $vo) {
            $list[] = [
                'name' => $vo['cate_title'],
                'value' => $vo['id']
            ];
            if (isset($tree[$vo['id']])) {
                foreach ($tree[$vo['id']] as $ko) {
                    $list[] = [
                        'name' => '--' . $ko['cate_title'],
                        'value' => $ko['id']
                    ];
                }
            }
        }
        
        return $list;
    }
}