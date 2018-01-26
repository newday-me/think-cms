<?php

namespace app\manage\widget\form;

class DateForm
{
    protected $default = [
        'title' => '',
        'name' => '',
        'value' => '',
        'class' => '',
        'style' => '',
        'attr' => '',
        'format' => 'yyyy-mm-dd'
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
        $html .= '<input type="text" name="' . $data['name'] . '" value="' . $data['value'] . '" class="form-control nd-date ' . $data['class'] . '" style=" ' . $data['style'] . '" ' . $data['attr'] . ' data-date-format="' . $data['format'] . '" />';
        $html .= '</div>';
        return $html;
    }
}