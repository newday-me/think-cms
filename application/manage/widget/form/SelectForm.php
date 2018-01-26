<?php

namespace app\manage\widget\form;

class SelectForm
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
        $html = '<div class="form-group"><label>' . $data['title'] . '</label>';
        $html .= '<select name="' . $data['name'] . '" class="form-control nd-select2 ' . $data['class'] . '" style=" ' . $data['style'] . '" ' . $data['attr'] . '>';
        foreach ($data['list'] as $vo) {
            if ($vo['value'] == $data['value']) {
                $html .= '<option selected value="' . $vo['value'] . '">' . $vo['name'] . '</option>';
            } else {
                $html .= '<option value="' . $vo['value'] . '">' . $vo['name'] . '</option>';
            }
        }
        $html .= '</select></div>';
        return $html;
    }
}