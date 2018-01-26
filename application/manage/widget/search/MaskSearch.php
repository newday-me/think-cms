<?php

namespace app\manage\widget\search;

class MaskSearch
{
    protected $default = [
        'title' => '',
        'name' => '',
        'value' => '',
        'holder' => '',
        'class' => '',
        'style' => '',
        'attr' => '',
        'option' => '{}'
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
        $html .= '<div class="input-group-addon">' . $data['title'] . '</div>';
        $html .= '<input type="text" class="form-control nd-mask nd-search-field ' . $data['class'] . '" style="' . $data['style'] . '" ' . $data['attr'];
        $html .= ' name="' . $data['name'] . '" placeholder="' . $data['holder'] . '" value="' . $data['value'] . '" data-option="' . $data['option'] . '" />';
        $html .= '</div>';
        return $html;
    }
}