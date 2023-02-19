<?php

namespace app\Tools\Admin;

use app\Tools\Models\ToolsFile;
use app\Tools\Models\ToolsFileDir;
use Dux\App;
use Dux\Handlers\ExceptionBusiness;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

class Upload {
    public function handler(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        /**
         * @var $uploads UploadedFileInterface[]
         */
        $body = $request->getParsedBody();
        $uploads = $request->getUploadedFiles();
        $app = $request->getAttribute('app');
        $list = [];
        $mimes = new \Mimey\MimeTypes;
        $type = App::config("storage")->get("type");
        $ext = App::config("storage")->get("ext", []);
        foreach ($uploads as $key => $vo) {
            $content = $vo->getStream()->getContents();
            $extension = pathinfo($vo->getClientFilename(), PATHINFO_EXTENSION);
            if (!$extension) {
                $mime = $vo->getClientMediaType();
                $extension = $mimes->getExtension($mime);
            } else {
                $mime = $mimes->getMimeType($extension);
            }
            if ($ext && !in_array($extension, $ext)) {
                throw new ExceptionBusiness('文件格式不支持');
            }
            $basename = bin2hex(random_bytes(10));
            $filename = sprintf('%s.%0.8s', $basename, $extension);
            $path = date('Y-m-d') . '/' . $filename;
            App::storage()->write($path, $content);
            $item = [
                'dir_id' => $body['dir_id'],
                'has_type' => $app,
                'driver' => $type,
                'url' => App::storage()->publicUrl($path),
                'path' => $path,
                'name' => $vo->getClientFilename(),
                'ext' => $extension,
                'size' => $vo->getSize(),
                'mime' => $mime,
            ];
            ToolsFile::query()->create($item);
            $list[] = [
                'url' => $item['url'],
                'name' => $item['name'],
                'ext' => $item['ext'],
                'size' => $item['size'],
                'mime' => $item['mime'],
            ];
        }
        return send($response, "ok", [
            'list' => $list
        ]);
    }

    private string $hasType = '';

    public function manage(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $this->hasType = $request->getAttribute('app');
        $query = $request->getQueryParams();
        $type = $query['type'];
        $id = $query['id'];
        $name = $query['name'];
        $query = $query['query'];
        $filter = $query['filter'];

        $data = [];
        if ($type == 'folder') {
            $data = $this->getFolder();
        }
        if ($type == 'files') {
            $data = $this->getFile($id, $query, $filter);
        }
        if ($type == 'files-delete') {
            $data = $this->deleteFile($id);
        }
        if ($type == 'folder-create') {
            $data = $this->createFolder($name);
        }
        if ($type == 'folder-delete') {
            $data = $this->deleteFolder($id);
        }

        return send($response, 'ok', $data);
    }

    private function getFile($dirId, $query = '', $filter = 'all'): array
    {
        $totalPage = 1;
        $page = 1;
        $format = [
            'image' => 'jpg,png,bmp,jpeg,gif',
            'audio' => 'wav,mp3,acc,ogg',
            'video' => 'mp4,ogv,webm,ogm',
            'document' => 'doc,docx,xls,xlsx,pptx,ppt,csv,pdf',
        ];

        if ($dirId) {
            $data = ToolsFile::where('has_type', $this->hasType)->where('dir_id', $dirId);
            if ($query) {
                $data = $data->where('name', 'like', '%' . $query . '%');
            }
            if ($filter <> 'all') {
                if ($filter === 'other') {

                    $data->whereNotIn('ext', explode(',', implode(',', $format)));
                } else {

                    $filterData = explode(',', $filter);
                    $exts = [];
                    foreach ($filterData as $vo) {
                        $exts[] = $format[$vo];
                    }
                    $exts = array_filter($exts);
                    $data->whereIn('ext', explode(',', implode(',', $exts)));
                }
            }
            $data = $data->orderBy('id', 'desc')->paginate(16, [
                'id', 'dir_id', 'url', 'name', 'ext', 'size', 'created_at'
            ]);
            $total = $data->total();
            $page = $data->currentPage();
            $data = $data->map(function ($item) use ($format) {
                $item->size = $item['size'];
                $item->time = $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '';
                if (in_array($item->ext, explode(',', $format['image']))) {
                    $item->cover = $item->url;
                } else {
                    $type = 'other';
                    foreach ($format as $key => $vo) {
                        if (in_array($item->ext, explode(',', $vo))) {
                            $type = $key;
                            break;
                        }
                    }
                    switch ($type) {
                        case 'audio':
                            $item->cover = '/static/system/img/icon/audio.svg';
                            break;
                        case 'video':
                            $item->cover = '/static/system/img/icon/video.svg';
                            break;
                        case 'document':
                            $item->cover = '/static/system/img/icon/doc.svg';
                            break;
                        default:
                            $item->cover = '/static/system/img/icon/other.svg';
                            break;
                    }
                }
                return $item;
            })->toArray();
        } else {
            $data = [];
        }
        return [
            'data' => $data,
            'total' => $total ?: 0,
            'page' => $page,
            'pageSize' => 16
        ];
    }

    /**
     * @return mixed
     */
    private function getFolder()
    {
        return ToolsFileDir::query()->where('has_type', $this->hasType)->get()->toArray();
    }

    /**
     * @param $name
     * @return array
     */
    private function createFolder($name): array
    {
        if (empty($name)) {
            trigger_error('请输入目录名称');
        }
        $file = new ToolsFileDir();
        $file->name = $name;
        $file->has_type = $this->hasType;
        $file->save();
        return [
            'id' => $file->id,
            'name' => $name,
        ];
    }

    /**
     * @param int $id
     * @return array
     */
    private function deleteFolder(int $id): array
    {
        if (empty($id)) {
            trigger_error('请选择目录');
        }
        $files = ToolsFile::query()->where('has_type', $this->hasType)->where('dir_id', $id)->get([
            'driver', 'path'
        ]);
        $files->map(function ($vo) {
            App::storage($vo->driver)->delete($vo->path);
        });
        ToolsFile::query()->where('dir_id', $id)->delete();
        ToolsFileDir::query()->where('id', $id)->delete();
        return [];
    }

    /**
     * @param $ids
     * @return array
     */
    private function deleteFile($ids): array
    {
        $ids = array_filter(explode(',', $ids));
        if (empty($ids)) {
            trigger_error('请选择删除文件');
        }
        $files = ToolsFile::query()->where('has_type', $this->hasType)->whereIn('id', $ids)->get([
            'driver', 'path'
        ]);
        $files->map(function ($vo) {
            App::storage($vo->driver)->delete($vo->path);
        });
        ToolsFile::query()->whereIn('id', $ids)->delete();
        return [];
    }
}