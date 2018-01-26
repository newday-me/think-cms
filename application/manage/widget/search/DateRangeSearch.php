<?php

namespace app\manage\widget\search;

class DateRangeSearch
{
    protected $default = [
        'title' => '<i class="fa fa-calendar"></i>',
        'name' => '',
        'value' => '',
        'holder' => '',
        'class' => '',
        'style' => '',
        'attr' => '',
        'format' => 'YYYY-MM-DD'
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
        $html .= '<input type="text" class="form-control nd-date-range nd-search-field ' . $data['class'] . '" style="' . $data['style'] . '" ' . $data['attr'];
        $html .= ' name="' . $data['name'] . '" placeholder="' . $data['holder'] . '" value="' . $data['value'] . '" data-format="' . $data['format'] . '" />';
        $html .= '</div>';
        return $html;
    }
}