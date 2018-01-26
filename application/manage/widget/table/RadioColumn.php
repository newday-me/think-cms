<?php

namespace app\manage\widget\table;


class RadioColumn
{
    protected $default = [
        'value' => '',
        'list' => [],
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
        $html = '';
        foreach ($data['list'] as $vo) {
            $html .= '<div class="pretty p-default p-round"><input type="radio" class="nd-radio" value="' . $vo['value'] . '"';

            // 选中
            if ($data['value'] === $vo['value']) {
                $html .= ' checked';
            }

            // 禁用
            if (isset($vo['disable']) && $vo['disable']) {
                $html .= ' disabled';
            }

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
            $html .= ' data-option=\'' . json_encode($option) . '\'/>';

            $html .= '<div class="state ' . $data['class'] . '" style=" ' . $data['style'] . '" ' . $data['attr'] . '><label>' . $vo['name'] . '</label></div>';
            $html .= '</div>';
        }
        return $html;
    }
}