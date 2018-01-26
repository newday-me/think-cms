<?php

namespace app\manage\widget\search;

class SelectSearch
{
    protected $default = [
        'title' => '',
        'name' => '',
        'value' => '',
        'list' => [],
        'class' => '',
        'style' => '',
        'attr' => ''
    ];

    /**
     * 渲染
     *
     * @param array $data
     * @return string
     */
    public function fetch($data)
    {
        $data = array_merge($this->default, $data);
        $html = '<div class="input-group">';
        $html .= '<span class="input-group-btn"><a class="btn btn-default">' . $data['title'] . '</a></span>';
        $html .= '<select name="' . $data['name'] . '" class="form-control nd-search-field nd-select2 ' . $data['class'] . '" style="' . $data['style'] . '" ' . $data['attr'] . '>';
        foreach ($data['list'] as $vo) {
            if ($vo['value'] === $data['value']) {
                $html .= '<option value="' . $vo['value'] . '" selected>' . $vo['name'] . '</option >';
            } else {
                $html .= '<option value="' . $vo['value'] . '">' . $vo['name'] . '</option >';
            }
        }
        $html .= '</select>';
        $html .= '</div>';
        return $html;
    }
}