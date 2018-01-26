<?php

namespace core\logic\support;

use core\base\Logic;

class TreeLogic extends Logic
{
    /**
     * 根节点key
     */
    const KEY_ROOT = '_tree_root_';

    /**
     * 构造树
     *
     * @param array $list
     * @param string $noKey
     * @param string $pnoKey
     * @param string $rootValue
     * @return array
     */
    public function buildTree($list, $noKey, $pnoKey, $rootValue = '')
    {
        $nest = $this->listToNest($list, $pnoKey, $rootValue);
        if (isset($nest[self::KEY_ROOT])) {
            return $this->nestToTree($nest, $nest[self::KEY_ROOT], $noKey);
        } else {
            return [];
        }
    }

    /**
     * 遍历树
     *
     * @param array $tree
     * @param \Closure $func
     * @param int $depth
     * @return mixed
     */
    public function travelTree(&$tree, $func, $depth = 1)
    {
        foreach ($tree as &$vo) {
            if (isset($vo['children'])) {
                $this->travelTree($vo['children'], $func, $depth + 1);
            }
            $func($vo, $depth);
        }
    }

    /**
     * 列表转嵌套
     *
     * @param array $list
     * @param string $pnoKey
     * @param string $rootValue
     * @return array
     */
    public function listToNest($list, $pnoKey, $rootValue = '')
    {
        $nest = [];
        foreach ($list as $vo) {
            if (!isset($vo[$pnoKey])) {
                continue;
            }

            $pno = ($vo[$pnoKey] === $rootValue) ? self::KEY_ROOT : $vo[$pnoKey];
            if (!isset($nest[$pno])) {
                $nest[$pno] = [];
            }

            $nest[$pno][] = $vo;
        }
        return $nest;
    }

    /**
     * 嵌套转树
     *
     * @param array $nest
     * @param array $subList
     * @param string $noKey
     * @return array
     */
    public function nestToTree($nest, $subList, $noKey)
    {
        $tree = [];
        foreach ($subList as $vo) {
            $no = $vo[$noKey];
            if (isset($nest[$no])) {
                $vo['_children_'] = true;
                $vo['children'] = $this->nestToTree($nest, $nest[$no], $noKey);
            } else {
                $vo['_children_'] = false;
            }
            $vo['folder'] = 'no activate';
            $tree[] = $vo;
        }
        return $tree;
    }

}