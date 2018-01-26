<?php

namespace app\module\widget\controller;

use app\manage\controller\Module;
use app\manage\logic\WidgetLogic;

class Index extends Module
{

    /**
     * 表单组件
     *
     * @return string
     */
    public function form()
    {
        $this->assign('site_title', '表单组件');
        $widget = WidgetLogic::getSingleton()->getWidget();
        $motto = '<p>开阔视野，冲破险境<br>To see the world, things dangerous to come to</p><p>洞悉所有，贴近生活<br>to see behind walls, to draw closer</p><p>寻找真爱，感受彼此<br>to find each other and to feel</p><p>这便是生活的目的<br>That is the Purpose of LIFE</p>';

        // 多选
        $selectHtml = $widget->form('select', [
            'title' => '多选',
            'value' => '苹果',
            'list' => [
                [
                    'name' => '香蕉',
                    'value' => '香蕉'
                ],
                [
                    'name' => '苹果',
                    'value' => '苹果'
                ]
            ]
        ]);
        $this->assign('select_html', $selectHtml);

        // 标签
        $tagHtml = $widget->form('tag', [
            'title' => '标签',
            'value' => 'music,movie,writing'
        ]);
        $this->assign('tag_html', $tagHtml);

        // 颜色
        $colorHtml = $widget->form('textarea', [
            'title' => '颜色',
            'value' => '#000'
        ]);
        $this->assign('color_html', $colorHtml);

        // 遮罩
        $maskHtml = $widget->form('mask', [
            'title' => '遮罩',
            'value' => '127.0.0.',
            'option' => [
                'alias' => 'ip'
            ]
        ]);
        $this->assign('mask_html', $maskHtml);

        // 日期
        $dateHtml = $widget->form('date', [
            'title' => '日期',
            'value' => '2018-01-01'
        ]);
        $this->assign('date_html', $dateHtml);

        // 日期区间
        $dateRangeHtml = $widget->form('date_range', [
            'title' => '日期区间',
            'value' => '2018-01-01 - 2019-01-01'
        ]);
        $this->assign('date_range_html', $dateRangeHtml);

        // SummerNote
        $summerNoteHtml = $widget->form('summer_note', [
            'title' => 'SummerNote',
            'value' => $motto
        ]);
        $this->assign('summer_note_html', $summerNoteHtml);

        // UmEditor
        $umEditorHtml = $widget->form('um_editor', [
            'title' => 'UmEditor',
            'value' => $motto
        ]);
        $this->assign('um_editor_html', $umEditorHtml);

        // 单图片
        $singleImageHtml = $widget->form('image', [
            'title' => '单图片',
            'value' => 'https://www.baidu.com/img/bd_logo.png'
        ]);
        $this->assign('single_image_html', $singleImageHtml);

        // 单文件
        $singleFileHtml = $widget->form('file', [
            'title' => '单文件',
            'value' => 'https://www.baidu.com/img/bd_logo.png'
        ]);
        $this->assign('single_file_html', $singleFileHtml);

        // 多图片
        $multiImageHtml = $widget->form('image', [
            'multi' => true,
            'title' => '多图片',
            'value' => [
                'https://www.baidu.com/img/bd_logo.png',
                'https://www.baidu.com/img/bd_logo.png',
                'https://www.baidu.com/img/bd_logo.png'
            ]
        ]);
        $this->assign('multi_image_html', $multiImageHtml);

        // 多文件
        $multiFileHtml = $widget->form('file', [
            'multi' => true,
            'title' => '多文件',
            'value' => [
                'https://www.baidu.com/img/bd_logo.png',
                'https://www.baidu.com/img/bd_logo.png',
                'https://www.baidu.com/img/bd_logo.png'
            ]
        ]);
        $this->assign('multi_file_html', $multiFileHtml);

        // 网页
        $htmlHtml = $widget->form('html', [
            'title' => 'Html',
            'value' => $motto
        ]);
        $this->assign('html_html', $htmlHtml);

        // 对象
        $jsonHtml = $widget->form('json', [
            'title' => 'Json',
            'value' => [
                'key' => 'value'
            ]
        ]);
        $this->assign('json_html', $jsonHtml);

        return $this->fetch();
    }

}