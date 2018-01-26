<?php

namespace app\manage\widget\form;


class UmEditorForm
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

        // 唯一ID
        $uniqueId = $data['name'] . '_' . time() . '_' . rand(0, 10000);

        $html = '<div class="form-group"><label>' . $data['title'] . '</label>';
        $html .= '<textarea type="text" name="' . $data['name'] . '" id="' . $uniqueId . '" class="form-control nd-umeditor ' . $data['class'] . '" style=" ' . $data['style'] . '" ' . $data['attr'] . '>';
        $html .= $data['value'] . '</textarea>';
        $html .= '</div>';
        return $html;
    }
}