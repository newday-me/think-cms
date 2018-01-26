<?php

namespace app\manage\widget\form;


class SummerNoteForm
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
        $html .= '<textarea type="text" name="' . $data['name'] . '" class="form-control nd-summernote ' . $data['class'] . '" style=" ' . $data['style'] . '" ' . $data['attr'] . '>';
        $html .= $data['value'] . '</textarea>';
        $html .= '</div>';
        return $html;
    }
}