<?php

namespace app\manage\widget\table;


class SelectColumn
{
    protected $default = [
        'name' => '',
        'value' => '',
        'list' => '',
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
        $html = '<select class="form-control nd-select nd-select2 ' . $data['class'] . '" style="' . $data['style'] . '" ' . $data['attr'];

        // 操作
        $option = [];
        if (isset($data['field']) && isset($data['url']) && isset($data['data_no'])) {
            $option['field'] = $data['field'];
            $option['url'] = $data['url'];
            $option['data_no'] = $data['data_no'];
            $html .= ' name="' . $data['field'] . '-' . $data['data_no'] . '"';
        } else {
            $html .= ' name="' . $data['name'] . '"';
        }
        $html .= ' data-option=\'' . json_encode($option) . '\'>';

        foreach ($data['list'] as $vo) {
            if ($vo['value'] === $data['value']) {
                $html .= '<option value="' . $vo['value'] . '" selected>' . $vo['name'] . '</option >';
            } else {
                $html .= '<option value="' . $vo['value'] . '">' . $vo['name'] . '</option >';
            }
        }

        $html .= '</select >';
        return $html;
    }
}