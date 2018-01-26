<?php

namespace app\manage\widget\form;


class TagForm
{
    protected $default = [
        'title' => '',
        'name' => '',
        'value' => '',
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
        $html = '<div class="form-group"><label>' . $data['title'] . '</label>';
        $html .= '<input type="text" name="' . $data['name'] . '" value="' . $data['value'] . '" class="form-control nd-tag ' . $data['class'] . '" style=" ' . $data['style'] . '" ' . $data['attr'] . ' />';
        $html .= '</div>';
        return $html;
    }
}