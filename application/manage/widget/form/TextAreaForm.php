<?php

namespace app\manage\widget\form;


class TextAreaForm
{
    protected $default = [
        'title' => '',
        'name' => '',
        'value' => '',
        'class' => '',
        'style' => '',
        'attr' => '',
        'row' => 3
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
        $html .= '<textarea name="' . $data['name'] . '" value="' . $data['value'] . '" rows="' . $data['row'] . '" class="form-control ' . $data['class'] . '" style=" ' . $data['style'] . '" ' . $data['attr'] . ' ></textarea>';
        $html .= '</div>';
        return $html;
    }
}