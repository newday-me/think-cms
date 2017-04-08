<?php
use think\Db;
use think\Cache;
use think\migration\Migrator;
use core\blog\model\ArticleModel;
use core\blog\model\ArticleCateModel;

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
        $model = ArticleCateModel::getSingleton();
        
        // 分类-读书
        $data = [
            'cate_name' => '读书',
            'cate_flag' => 'read',
            'cate_info' => '读书...',
            'cate_sort' => 0,
            'cate_status' => 1
        ];
        $cate = $model->create($data);
        $urls = [
            'https://mp.weixin.qq.com/s/sD4UCh7_no-jxWenxrolsA',
            'http://mp.weixin.qq.com/s/JYn-HlEh6rDF10_L8uDkYA',
            'http://mp.weixin.qq.com/s/EJCPmZwguwB7BDNye_D5rg',
            'http://mp.weixin.qq.com/s/80JK1Bue0fLB-qeX17YHwg',
            'http://mp.weixin.qq.com/s/_GYen06AJlfjCnTXwEu9zg',
            'http://mp.weixin.qq.com/s/qOjolrVrJb3xlfI4TKoT7g',
            'http://mp.weixin.qq.com/s/NqYi8X-PiZzoZ7elnt19PQ'
        ];
        foreach ($urls as $url) {
            $this->addArticle($url, $cate['id']);
        }
        
        // 分类-活动
        $data = [
            'cate_name' => '活动',
            'cate_flag' => 'activity',
            'cate_info' => '读书...',
            'cate_sort' => 0,
            'cate_status' => 1
        ];
        $cate = $model->create($data);
        $urls = [
            'http://mp.weixin.qq.com/s/MGCLwOnH5OycemkD4QlRvg',
            'http://mp.weixin.qq.com/s/SfZVkF4uKcmBb1iHXMrNvg',
            'http://mp.weixin.qq.com/s/9EubuUvdnji7noZ_SPEH0Q',
            'http://mp.weixin.qq.com/s/NFk12fPkN2_hh3e11PJn4g',
            'http://mp.weixin.qq.com/s/eTVRE0uUcBN2xMCRm4MJXA',
            'http://mp.weixin.qq.com/s/_PQePDmopJUwZz6sTqrkGA',
            'http://mp.weixin.qq.com/s/TgeSR8MWlkWmDem5XWa2Lg'
        ];
        foreach ($urls as $url) {
            $this->addArticle($url, $cate['id']);
        }
        
        // 分类-旅行
        $data = [
            'cate_name' => '旅行',
            'cate_flag' => 'travel',
            'cate_info' => '读书...',
            'cate_sort' => 0,
            'cate_status' => 1
        ];
        $cate = $model->create($data);
        $urls = [
            'https://mp.weixin.qq.com/s/T2snmfck-Yjto4Lix_2K5Q',
            'http://mp.weixin.qq.com/s/WlX5KXPSKgBwRWEmiUvr1A',
            'http://mp.weixin.qq.com/s/B53IjIwJ2AhtKAw9a2vPQg',
            'http://mp.weixin.qq.com/s/OQgjtkf1OvYZP4S7QXHNjQ',
            'http://mp.weixin.qq.com/s/jSIngzFpe4RICWvg6HjnTg',
            'https://mp.weixin.qq.com/s/kXZrvWfRCg_tuW2jnfBqCg',
            'http://mp.weixin.qq.com/s/oTlqWaiCspFs4gEezh8UVw'
        ];
        foreach ($urls as $url) {
            $this->addArticle($url, $cate['id']);
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Phinx\Migration\AbstractMigration::down()
     */
    public function down()
    {
        Db::connect()->query('truncate table nd_blog_article');
        Db::connect()->query('truncate table nd_blog_article_cate');
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
        // 非二级域名或.com域名 return 'http://read.html5.qq.com/image?src=forum&q=5&r=0&imgflag=7&imageUrl=' . $url;
        $url = str_replace('mmbiz.qpic.cn', 'newday-qpic.b0.upaiyun.com', $url);
        list ($url) = explode('?', $url, 2);
        return $url;
    }

    /**
     * 添加文章
     *
     * @param string $url            
     * @param integer $cateId            
     *
     * @return Model
     */
    protected function addArticle($url, $cateId)
    {
        if (strpos($url, '?')) {
            $urlJson = $url . '&f=json';
        } else {
            $urlJson = $url . '?f=json';
        }
        $content = Cache::get($urlJson);
        if (empty($content)) {
            $content = file_get_contents($urlJson);
            Cache::set($urlJson, $content);
        }
        $result = json_decode($content, true);
        $data = [
            'article_key' => substr(md5($url), 8, 16),
            'article_title' => $result['title'],
            'article_author' => $result['author'] ?: $result['nick_name'],
            'article_info' => $result['desc'],
            'article_cover' => $this->transImageUrl($result['cdn_url']),
            'article_cate' => $cateId,
            'article_tags' => $result['nick_name'],
            'article_origin' => $result['source_url'] ?: $url,
            'article_sort' => 0,
            'article_content' => $this->transContent($result['content_noencode']),
            'article_status' => 1
        ];
        return ArticleModel::getSingleton()->create($data);
    }
}
