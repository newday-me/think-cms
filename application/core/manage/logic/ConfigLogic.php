<?php
namespace core\manage\logic;

use core\Logic;
use think\Cache;
use core\manage\model\ConfigModel;

class ConfigLogic extends Logic
{

    /**
     * 缓存key
     *
     * @var unknown
     */
    const CACHE_KEY = 'manage_config';

    /**
     * 获取类型下拉
     *
     * @return array
     */
    public function getSelectType()
    {
        return [
            [
                'name' => '文本',
                'value' => 'text'
            ],
            [
                'name' => '文本域',
                'value' => 'textarea'
            ],
            [
                'name' => '标签',
                'value' => 'tag'
            ],
            [
                'name' => '日期',
                'value' => 'date'
            ],
            [
                'name' => '颜色',
                'value' => 'color'
            ],
            [
                'name' => '图片',
                'value' => 'image'
            ],
            [
                'name' => '文件',
                'value' => 'file'
            ],
            [
                'name' => '多选',
                'value' => 'checkbox'
            ],
            [
                'name' => '单选',
                'value' => 'radio'
            ],
            [
                'name' => '下拉',
                'value' => 'select'
            ],
            [
                'name' => '数组',
                'value' => 'array'
            ],
            [
                'name' => '富文本',
                'value' => 'editor'
            ]
        ];
    }

    /**
     * 获取配置
     *
     * @return array
     */
    public function getConfig()
    {
        $config = Cache::get(self::CACHE_KEY);
        if (empty($config)) {
            $list = ConfigModel::getInstance()->select();
            $config = [];
            foreach ($list as $vo) {
                switch ($vo['config_type']) {
                    case 'checkbox':
                    case 'array':
                        $vo['config_value'] = empty($vo['config_value']) ? [] : @json_decode($vo['config_value'], true);
                        break;
                }
                $config[$vo['config_name']] = $vo['config_value'];
            }
            
            // 变量
            $config = $this->parseConfigVariable($config);
            
            // 缓存
            Cache::set(self::CACHE_KEY, $config, 600);
        }
        return $config;
    }

    /**
     * 刷新缓存
     *
     * @return boolean
     */
    public function refreshConfig()
    {
        return Cache::rm(self::CACHE_KEY);
    }

    /**
     * 获取网站配置
     *
     * @return array
     */
    public function getSetting()
    {
        $list = ConfigModel::getInstance()->withGroups()
            ->order('group_sort desc, config_sort desc')
            ->select();
        $res = [];
        foreach ($list as $vo) {
            $group = $vo['group_name'];
            if (! isset($res[$group])) {
                $res[$group] = [
                    'name' => $group,
                    'key' => md5($group),
                    'list' => []
                ];
            }
            $res[$group]['list'][] = $this->processItem($vo);
        }
        return $res;
    }

    /**
     * 处理配置项
     *
     * @param array $item            
     * @return array
     */
    public function processItem($item)
    {
        switch ($item['config_type']) {
            case 'array':
                $arr = empty($item['config_extra']) ? [] : explode(',', $item['config_extra']);
                $value = empty($item['config_value']) ? [] : json_decode($item['config_value'], true);
                foreach ($arr as $vo) {
                    if (! isset($value[$vo])) {
                        $value[$vo] = '';
                    }
                }
                $item['config_value'] = json_encode($value, JSON_UNESCAPED_UNICODE);
                break;
            case 'radio':
            case 'checkbox':
            case 'select':
                $arr = empty($item['config_extra']) ? [] : explode('|', $item['config_extra']);
                $extra = [];
                foreach ($arr as $vo) {
                    list ($value, $name) = explode(':', $vo);
                    $extra[] = [
                        'name' => $name,
                        'value' => $value
                    ];
                }
                $item['config_extra'] = $extra;
                break;
        }
        return $item;
    }

    /**
     * 解析配置变量
     *
     * @param array $config            
     * @return array
     */
    protected function parseConfigVariable($config)
    {
        $var_list = $this->getVariableList();
        foreach ($config as $co => $vo) {
            if (is_array($vo)) {
                $config[$co] = $this->parseConfigVariable($vo);
            } else {
                $config[$co] = str_replace($var_list[0], $var_list[1], $config[$co]);
            }
        }
        return $config;
    }

    /**
     * 变量列表
     *
     * @return array
     */
    protected function getVariableList()
    {
        static $variableList;
        if (empty($variableList)) {
            $variableList = [
                [],
                []
            ];
            $list = [
                '{EXT}' => EXT,
                '{DS}' => DS,
                '{THINK_PATH}' => THINK_PATH,
                '{WEB_PATH}' => WEB_PATH,
                '{ROOT_PATH}' => ROOT_PATH,
                '{APP_PATH}' => APP_PATH,
                '{CONF_PATH}' => CONF_PATH,
                '{LIB_PATH}' => LIB_PATH,
                '{CORE_PATH}' => CORE_PATH,
                '{TRAIT_PATH}' => TRAIT_PATH,
                '{EXTEND_PATH}' => EXTEND_PATH,
                '{VENDOR_PATH}' => VENDOR_PATH,
                '{RUNTIME_PATH}' => RUNTIME_PATH,
                '{LOG_PATH}' => LOG_PATH,
                '{CACHE_PATH}' => CACHE_PATH,
                '{TEMP_PATH}' => TEMP_PATH
            ];
            foreach ($list as $co => $vo) {
                $variableList[0][] = $co;
                $variableList[1][] = $vo;
            }
        }
        return $variableList;
    }
}