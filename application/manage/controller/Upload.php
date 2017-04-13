<?php
namespace app\manage\controller;

use think\Config;
use think\Request;
use cms\Response;
use cms\upload\validates\ExtensionVaildate;
use cms\upload\processes\CropProcess;
use cms\upload\processes\OrientationProcess;
use app\common\App;
use app\common\factories\FileFactory;
use app\manage\service\EditorService;

class Upload extends Base
{

    /**
     * 上传文件
     *
     * @param Request $request            
     *
     * @return void
     */
    public function upload(Request $request)
    {
        $option = $request->param('upload_option');
        $option && $option = json_decode($option, true);
        $option || $option = [];
        
        // 文件是否存在
        $file = isset($_FILES['upload_file']) ? $_FILES['upload_file'] : null;
        if (empty($file)) {
            $this->error('上传文件不存在');
        }
        
        $result = $this->uploadFile($file, $option);
        $this->success('上传成功', '', $result);
    }

    /**
     * 编辑器上传
     *
     * @param Request $request            
     *
     * @return void
     */
    public function editor(Request $request)
    {
        // 图片大小
        $option = [
            'width' => 1920,
            'height' => 1080
        ];
        
        $reqponse = Response::getSingleton();
        $action = $request->param('action', '');
        switch ($action) {
            case 'wang':
                // 文件是否存在
                $file = isset($_FILES['upload_file']) ? $_FILES['upload_file'] : null;
                if (empty($file)) {
                    return 'error|上传文件不存在';
                }
                
                // 上传文件
                try {
                    $result = $this->uploadFile($file, $option);
                    return $result['url'];
                } catch (\Exception $e) {
                    return 'error|' . $e->getMessage();
                }
                break;
            case 'config':
                $editor = EditorService::getSingleton();
                $reqponse->json($editor->getUeditorConfig());
                break;
            case 'uploadscrawl':
                // 内容是否存在
                $content = base64_decode($request->param('upfile', ''));
                if (empty($content)) {
                    $reqponse->json([
                        'state' => '上传文件不存在'
                    ]);
                }
                
                // 上传文件
                try {
                    $result = $this->uploadFile($content, $option);
                    $data = [
                        'state' => 'SUCCESS',
                        'url' => $result['url'],
                        'title' => $result['name'],
                        'original' => $result['name']
                    ];
                } catch (\Exception $e) {
                    $data = [
                        'state' => $e->getMessage()
                    ];
                }
                $reqponse->json($data);
                break;
            case 'uploadimage':
            case 'uploadvideo':
            case 'uploadfile':
                
                // 文件是否存在
                $file = isset($_FILES['upfile']) ? $_FILES['upfile'] : null;
                if (empty($file)) {
                    $reqponse->json([
                        'state' => '上传文件不存在'
                    ]);
                }
                
                // 上传文件
                try {
                    $result = $this->uploadFile($file, $option);
                    $data = [
                        'state' => 'SUCCESS',
                        'url' => $result['url'],
                        'title' => $file['name'],
                        'original' => $file['name']
                    ];
                } catch (\Exception $e) {
                    $data = [
                        'state' => $e->getMessage()
                    ];
                }
                $reqponse->json($data);
                break;
            case 'listfile':
            case 'listimage':
                $start = $request->param('start', 0);
                $size = $request->param('size', 20);
                $type = $action == 'listfile' ? EditorService::TYPE_FILE : EditorService::TYPE_IMAGE;
                
                $editor = EditorService::getSingleton();
                $reqponse->json($editor->listFile($type, $start, $size));
                break;
        }
    
    }

    /**
     * 上传文件
     *
     * @param array $file            
     * @param array $option            
     *
     * @return array
     */
    protected function uploadFile($file, $option)
    {
        // 上传文件
        $type = is_array($file) ? FileFactory::TYPE_UPLOAD : FileFactory::TYPE_STREAM;
        $upfile = FileFactory::make($type);
        $upfile->load($file);
        
        // 上传对象
        $upload = App::getSingleton()->upload;
        
        // 文件后缀
        $extensions = Config::get('upload_extensions');
        if (! empty($extensions)) {
            $option = [
                'extensions' => $extensions
            ];
            $upload->addValidate(new ExtensionVaildate($option));
        }
        
        // 图片重力
        $upload->addProcesser(new OrientationProcess());
        
        // 图片大小
        if (isset($option['width']) || isset($option['height'])) {
            $upload->addProcesser(new CropProcess($option));
        }
        
        // 上传文件
        return $upload->upload($upfile);
    }
}