<?php

namespace app\manage\widget\form;


class JsonForm
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

        // 值
        $data['value'] = is_array($data['value']) ? json_encode($data['value']) : $data['value'];

        $html = '<div class="form-group"><label>' . $data['title'] . '</label>';
        $html .= '<textarea type="text" name="' . $data['name'] . '" class="form-control nd-code-json ' . $data['class'] . '" style=" ' . $data['style'] . '" ' . $data['attr'] . '>';
        $html .= $data['value'] . '</textarea>';
        $html .= '</div>';
        return $html;
    }
}