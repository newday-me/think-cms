<?php

namespace app\manage\widget\table;

class SwitchColumn
{
    protected $default = [
        'title' => '',
        'on' => 1,
        'off' => 0,
        'disable' => false,
        'class' => 'p-primary',
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
        $html = '<div class="pretty p-switch">';
        $html .= '<input type="checkbox" class="nd-switch"';

        // 选中
        if ($data['on'] === $data['value']) {
            $html .= ' checked';
        }

        // 禁用
        if ($data['disable']) {
            $html .= ' disabled';
        }

        // 操作
        $option = [
            'on' => $data['on'],
            'off' => $data['off']
        ];
        if (isset($data['field']) && isset($data['url']) && isset($data['data_no'])) {
            $option['field'] = $data['field'];
            $option['url'] = $data['url'];
            $option['data_no'] = $data['data_no'];
        }
        $html .= ' data-option=\'' . json_encode($option) . '\'/>';

        $html .= '<div class="state ' . $data['class'] . '" style=" ' . $data['style'] . '" ' . $data['attr'] . '><label>' . $data['title'] . '</label></div>';
        $html .= '</div>';

        return $html;
    }
}