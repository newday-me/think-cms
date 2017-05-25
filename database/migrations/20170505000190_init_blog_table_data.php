<?php
use think\Db;
use think\Cache;
use think\migration\Migrator;
use core\blog\model\PageModel;
use core\blog\model\ArticleModel;
use core\blog\model\ArticleCateModel;
use core\blog\logic\ArticleLogic;
use core\blog\model\ArticleCateLinkModel;
use core\blog\model\ArticleTagModel;
use core\blog\model\ArticleTagLinkModel;

class InitBlogTableData extends Migrator
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \Phinx\Migration\AbstractMigration::up()
     */
    public function up()
    {
        $this->initArticle();
        
        $this->initPage();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Phinx\Migration\AbstractMigration::down()
     */
    public function down()
    {
        $tables = [
            ArticleModel::getInstance()->getTableName(),
            ArticleCateModel::getInstance()->getTableName(),
            ArticleCateLinkModel::getInstance()->getTableName(),
            ArticleTagModel::getInstance()->getTableName(),
            ArticleTagLinkModel::getInstance()->getTableName(),
            PageModel::getInstance()->getTableName()
        ];
        foreach ($tables as $table) {
            Db::connect()->query('truncate table ' . $table);
        }
    }

    /**
     * 初始化文章数据
     *
     * @return void
     */
    protected function initArticle()
    {
        $data = [
            [
                'cate' => [
                    'cate_title' => '读书',
                    'cate_name' => 'read',
                    'cate_info' => '读书...',
                    'cate_status' => 1
                ],
                'urls' => [
                    'https://mp.weixin.qq.com/s/sD4UCh7_no-jxWenxrolsA',
                    'http://mp.weixin.qq.com/s/JYn-HlEh6rDF10_L8uDkYA',
                    'http://mp.weixin.qq.com/s/EJCPmZwguwB7BDNye_D5rg',
                    'http://mp.weixin.qq.com/s/80JK1Bue0fLB-qeX17YHwg',
                    'http://mp.weixin.qq.com/s/_GYen06AJlfjCnTXwEu9zg',
                    'http://mp.weixin.qq.com/s/qOjolrVrJb3xlfI4TKoT7g',
                    'http://mp.weixin.qq.com/s/NqYi8X-PiZzoZ7elnt19PQ'
                ]
            ],
            [
                'cate' => [
                    'cate_title' => '活动',
                    'cate_name' => 'activity',
                    'cate_info' => '读书...',
                    'cate_status' => 1
                ],
                'urls' => [
                    'http://mp.weixin.qq.com/s/MGCLwOnH5OycemkD4QlRvg',
                    'http://mp.weixin.qq.com/s/SfZVkF4uKcmBb1iHXMrNvg',
                    'http://mp.weixin.qq.com/s/9EubuUvdnji7noZ_SPEH0Q',
                    'http://mp.weixin.qq.com/s/NFk12fPkN2_hh3e11PJn4g',
                    'http://mp.weixin.qq.com/s/eTVRE0uUcBN2xMCRm4MJXA',
                    'http://mp.weixin.qq.com/s/_PQePDmopJUwZz6sTqrkGA',
                    'http://mp.weixin.qq.com/s/TgeSR8MWlkWmDem5XWa2Lg'
                ]
            ],
            [
                'cate' => [
                    'cate_title' => '旅行',
                    'cate_name' => 'travel',
                    'cate_info' => '读书...',
                    'cate_status' => 1
                ],
                'urls' => [
                    'https://mp.weixin.qq.com/s/T2snmfck-Yjto4Lix_2K5Q',
                    'http://mp.weixin.qq.com/s/WlX5KXPSKgBwRWEmiUvr1A',
                    'http://mp.weixin.qq.com/s/B53IjIwJ2AhtKAw9a2vPQg',
                    'http://mp.weixin.qq.com/s/OQgjtkf1OvYZP4S7QXHNjQ',
                    'http://mp.weixin.qq.com/s/jSIngzFpe4RICWvg6HjnTg',
                    'https://mp.weixin.qq.com/s/kXZrvWfRCg_tuW2jnfBqCg',
                    'http://mp.weixin.qq.com/s/oTlqWaiCspFs4gEezh8UVw'
                ]
            ]
        ];
        
        foreach ($data as $vo) {
            $cate = ArticleCateModel::getInstance()->create($vo['cate']);
            
            foreach ($vo['urls'] as $url) {
                $this->addArticle($url, $cate['id']);
            }
        }
    }

    /**
     * 添加文章
     *
     * @param string $url            
     * @param integer $cateId            
     *
     * @return void
     */
    protected function addArticle($url, $cateId)
    {
        $result = $this->fetchUrl($url);
        $data = [
            'article_key' => substr(md5($url), 8, 16),
            'article_title' => $result['title'],
            'article_author' => $result['author'] ?: $result['nick_name'],
            'article_info' => $result['desc'],
            'article_cover' => $this->transImageUrl($result['cdn_url']),
            'article_origin' => $result['source_url'] ?: $url,
            'article_sort' => 0,
            'article_content' => $this->transContent($result['content_noencode']),
            'article_status' => 1
        ];
        $article = ArticleModel::getInstance()->create($data);
        
        $logic = ArticleLogic::getSingleton();
        $logic->attachArticleCates($article['id'], $cateId);
        $logic->attachArticleTags($article['id'], $result['nick_name'] . ',' . $result['author']);
    }

    /**
     * 初始化页面
     *
     * @return void
     */
    protected function initPage()
    {
        $data = [
            [
                'page_title' => '关于',
                'page_name' => 'farmer',
                'page_url' => 'http://mp.weixin.qq.com/s/hOu7JkyTpIAnzy10tek04g',
                'page_status' => 2
            ],
            [
                'page_title' => '养只喵',
                'page_name' => 'feedcat',
                'page_url' => 'http://mp.weixin.qq.com/s/QYyZgbji0LGhgpVpgq0lew',
                'page_status' => 1
            ]
        ];
        
        foreach ($data as $vo) {
            $result = $this->fetchUrl($vo['page_url']);
            
            $data = [
                'page_name' => $vo['page_name'],
                'page_title' => $vo['page_title'],
                'page_content' => $this->transContent($result['content_noencode']),
                'page_status' => $vo['page_status']
            ];
            PageModel::getInstance()->create($data);
        }
    }

    /**
     * 抓取链接
     *
     * @param string $url            
     *
     * @return array
     */
    protected function fetchUrl($url)
    {
        $url = $this->transUrl($url);
        $content = Cache::get($url);
        if (empty($content)) {
            $content = file_get_contents($url);
            Cache::set($url, $content);
        }
        return json_decode($content, true);
    }

    /**
     * 转换链接
     *
     * @param string $url            
     *
     * @return string
     */
    protected function transUrl($url)
    {
        if (strpos($url, '?')) {
            $url = $url . '&f=json';
        } else {
            $url = $url . '?f=json';
        }
        return $url;
    }

    /**
     * 转换内容里的图片
     *
     * @param string $content            
     *
     * @return string
     */
    protected function transContent($content)
    {
        $pattern = '#data-src="(.*?)"#';
        if (preg_match_all($pattern, $content, $match)) {
            $vars = [];
            foreach ($match[0] as $co => $vo) {
                $vars[$vo] = 'src="' . $this->transImageUrl($match[1][$co]) . '"';
            }
            $content = str_replace(array_keys($vars), array_values($vars), $content);
        }
        return $content;
    }

    /**
     * 转换图片链接
     *
     * @param string $url            
     *
     * @return string
     */
    protected function transImageUrl($url)
    {
        $url = str_replace('mmbiz.qpic.cn', 'newday-qpic.b0.upaiyun.com', $url);
        list ($url) = explode('?', $url, 2);
        return $url;
    }
}
