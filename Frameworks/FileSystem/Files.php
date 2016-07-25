<?php

namespace PAO\FileSystem;

use Illuminate\Filesystem\Filesystem;

class Files extends Filesystem
{


    /**
     * 判断文件是否存在
     * @param $path
     * @return bool
     */
    public function has($path)
    {
        return $this->exists($path);
    }


    /**
     * 删除
     * @param $path
     * @return bool
     */
    public function del($path)
    {
        return $this->delete($path);
    }

    /**
     * 写入文件内容
     * @param $path
     * @param $content
     * @param bool $lock
     * @return int
     */
    public function set($path, $content, $lock = false)
    {
        return $this->put($path, $content, $lock);
    }

    /**
     * 追加文件内容
     * @param $path
     * @param $content
     * @return int
     */
    public function put($path, $content)
    {
        return $this->append($path, $content);
    }


    /**
     * 递归创建文件目录
     * @param $path
     * @return bool
     */
    public function mkDir($path)
    {
        return $this->makeDirectory($path, 0755, true, false);
    }


    /**
     * 判断是否是目录
     * @param $path
     * @return bool
     */
    public function isDir($path)
    {
        return $this->isDirectory($path);
    }

    /**
     * 拷贝目录
     * @param $dir
     * @param $target
     * @param null $options
     * @return bool
     */
    public function copyDir($dir, $target, $options = null)
    {
        return $this->copyDirectory($dir, $target, $options);
    }
}