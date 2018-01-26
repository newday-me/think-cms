<?php

namespace app\manage\widget\search;


class KeywordSearch
{
    protected $default = [
        'title' => '',
        'name' => '',
        'value' => '',
        'holder' => '',
        'form' => '.nd-search-form',
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
        $html .= '<input type="text" class="form-control nd-search-field ' . $data['class'] . '" style="' . $data['style'] . '" ' . $data['attr'];
        $html .= ' name="' . $data['name'] . '" placeholder="' . $data['holder'] . '" value="' . $data['value'] . '" />';
        $html .= '<span class="input-group-btn">';
        $html .= '<a class="btn btn-primary nd-search" search-form="' . $data['form'] . '"><i class="fa fa-search"></i></a>';
        $html .= '</span></div>';
        return $html;
    }
}