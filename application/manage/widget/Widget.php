<?php

namespace app\manage\widget;

use app\manage\widget\form\ColorForm;
use app\manage\widget\form\DateForm;
use app\manage\widget\form\DateRangeForm;
use app\manage\widget\form\FileForm;
use app\manage\widget\form\HtmlForm;
use app\manage\widget\form\ImageForm;
use app\manage\widget\form\JsonForm;
use app\manage\widget\form\MaskForm;
use app\manage\widget\form\SelectForm;
use app\manage\widget\form\SummerNoteForm;
use app\manage\widget\form\TagForm;
use app\manage\widget\form\TextAreaForm;
use app\manage\widget\form\TextForm;
use app\manage\widget\form\UmEditorForm;
use app\manage\widget\search\MaskSearch;
use cms\core\traits\InstanceTrait;
use app\manage\widget\table\SwitchColumn;
use app\manage\widget\table\RadioColumn;
use app\manage\widget\table\SelectColumn;
use app\manage\widget\search\SelectSearch;
use app\manage\widget\search\DateRangeSearch;
use app\manage\widget\search\DateSearch;
use app\manage\widget\search\KeywordSearch;

class Widget
{

    /**
     * 实例trait
     */
    use InstanceTrait;

    /**
     * 表单
     */
    const TYPE_FORM = 'form';

    /**
     * 表格
     */
    const TYPE_TABLE = 'table';

    /**
     * 搜索
     */
    const TYPE_SEARCH = 'search';

    /**
     * 映射
     *
     * @var array
     */
    protected $mapping = [
        self::TYPE_FORM => [
            'text' => TextForm::class,
            'textarea' => TextAreaForm::class,
            'select' => SelectForm::class,
            'tag' => TagForm::class,
            'color' => ColorForm::class,
            'mask' => MaskForm::class,
            'date' => DateForm::class,
            'date_range' => DateRangeForm::class,
            'file' => FileForm::class,
            'image' => ImageForm::class,
            'json' => JsonForm::class,
            'html' => HtmlForm::class,
            'summer_note' => SummerNoteForm::class,
            'um_editor' => UmEditorForm::class
        ],
        self::TYPE_TABLE => [
            'radio' => RadioColumn::class,
            'select' => SelectColumn::class,
            'switch' => SwitchColumn::class
        ],
        self::TYPE_SEARCH => [
            'date' => DateSearch::class,
            'date_range' => DateRangeSearch::class,
            'select' => SelectSearch::class,
            'mask' => MaskSearch::class,
            'keyword' => KeywordSearch::class
        ]
    ];

    /**
     * 注册表单
     *
     * @param mixed $name
     * @param string $class
     */
    public function registerForm($name, $class = null)
    {
        $this->register(self::TYPE_FORM, $name, $class);
    }

    /**
     * 注册表格
     *
     * @param mixed $name
     * @param string $class
     */
    public function registerTable($name, $class = null)
    {
        $this->register(self::TYPE_TABLE, $name, $class);
    }

    /**
     * 注册搜索
     *
     * @param mixed $name
     * @param string $class
     */
    public function registerSearch($name, $class = null)
    {
        $this->register(self::TYPE_SEARCH, $name, $class);
    }

    /**
     * 渲染表单
     *
     * @param string $name
     * @param array $data
     * @return string
     */
    public function form($name, $data = [])
    {
        return $this->fetch(self::TYPE_FORM, $name, $data);
    }

    /**
     * 渲染表格
     *
     * @param string $name
     * @param array $data
     * @return string
     */
    public function table($name, $data = [])
    {
        return $this->fetch(self::TYPE_TABLE, $name, $data);
    }

    /**
     * 渲染搜索
     *
     * @param string $name
     * @param array $data
     * @return string
     */
    public function search($name, $data = [])
    {
        return $this->fetch(self::TYPE_SEARCH, $name, $data);
    }

    /**
     * 渲染
     *
     * @param string $type
     * @param string $name
     * @param array $data
     * @return string
     */
    protected function fetch($type, $name, $data)
    {
        $method = 'fetch';
        $class = isset($this->mapping[$type][$name]) ? $this->mapping[$type][$name] : '';
        if ($class && class_exists($class) && method_exists($class, $method)) {
            return (new $class())->$method($data);
        }
        return '';
    }

    /**
     * 注册
     *
     * @param string $type
     * @param mixed $name
     * @param string $class
     */
    protected function register($type, $name, $class)
    {
        if (is_array($name)) {
            foreach ($name as $co => $vo) {
                $this->register($type, $co, $vo);
            }
        } else {
            $this->mapping[$type][$name] = $class;
        }
    }

}