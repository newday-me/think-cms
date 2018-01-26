<?php

namespace app\manage\widget\form;


class ImageForm
{
    protected $default = [
        'title' => '',
        'name' => '',
        'value' => '',
        'class' => '',
        'style' => '',
        'attr' => '',
        'multi' => false,
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

        // 值
        $data['value'] = is_array($data['value']) ? implode(',', $data['value']) : $data['value'];

        $html = '<div class="form-group"><label>' . $data['title'] . '</label>';
        $html .= '<input type="hidden" name="' . $data['name'] . '" id="' . $uniqueId . '" value="' . $data['value'] . '"/>';
        $html .= '<div class="file-loading">';
        $html .= '<input type="file" ' . ($data['multi'] ? 'multiple' : '') . ' class="form-control nd-upload-image ' . $data['class'] . '" style=" ' . $data['style'] . '" ' . $data['attr'] . ' data-target="#' . $uniqueId . '" />';
        $html .= '</div></div>';
        return $html;
    }
}