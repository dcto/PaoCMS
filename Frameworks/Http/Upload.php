<?php

namespace PAO\Http;

use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Upload extends UploadedFile
{

    /**
     * Create a new file instance from a base instance.
     *
     * @param  \Symfony\Component\HttpFoundation\File\UploadedFile  $file
     * @return static
     */
    public static function initialize(UploadedFile $file)
    {
        return $file instanceof static ? $file : new static(
            $file->getPathname(),
            $file->getClientOriginalName(),
            $file->getClientMimeType(),
            $file->getClientSize(),
            $file->getError()
        );
    }

    /**
     * Get the fully qualified path to the file.
     *
     * @return string
     */
    public function path()
    {
        return $this->getRealPath();
    }


    public function size()
    {
        return $this->getSize();
    }


    /**
     * Get the file's extension.
     *
     * @return string
     */
    public function extension()
    {
        return $this->guessExtension();
    }

    /**
     * Get the file's extension supplied by the client.
     *
     * @return string
     */
    public function clientExtension()
    {
        return $this->guessClientExtension();
    }

    /**
     * Get a filename for the file that is the MD5 hash of the contents.
     *
     * @param  string  $path
     * @return string
     */
    public function hashName($path = null)
    {
        if ($path) {
            $path = rtrim($path, '/').'/';
        }

        return $path.$this->md5().'.'.$this->extension();
    }


    /**
     * Get file md5 hash
     *
     * @param null $path
     * @return string
     */
    public function md5()
    {
        return md5_file($this->path());
    }


    /**
     * Returns the extension based on the client mime type.
     *
     * @return null|string
     */
    public function mime()
    {
        return $this->getMimeType();
    }


    /**
     * upload files
     *
     * @param array $config
     * @return array
     */
    public static function save($config = array())
    {
        $default = array('path'=>PAO, 'size'=>1024, 'kind'=>['gif','png','jpg','jpeg'], 'name'=>false);
        $config = array_merge(array_merge(config('upload'), $default), $config);

        $uploads = (object) array('files' => array(), 'error'=>array());


        $files = array();
        foreach(Input::files() as $key => $file) {
                /* @var $file $this */
                if(!$file) continue;
                if($file->getError()){
                    $files[$key]->error = $file->getErrorMessage();
                    continue;
                }

                if($file->size() > $config['size']) {
                    $files[$key]->error = lang('upload.size', $file->getClientOriginalName());
                    continue;
                }

                $files[$key]['name'] = $config['name'] ?: $file->hashName();
                $files[$key]['path'] = rtrim($config['path'], '/') . '/' . $files[$key]['name'];
                $files[$key]['size'] = $file->size();
                $files[$key]['mime'] = $file->mime();
                $files[$key]['hash'] = $file->md5();
                $files[$key]['temp'] = $file->getPathname();
                $files[$key]['time'] = array('create'=>$file->getCTime(),'modify'=>$file->getMTime(),'access'=>$file->getATime());
                $files[$key]['client']['name'] = $file->getClientOriginalName();
                $files[$key]['client']['size'] = $file->getClientSize();
                $files[$key]['client']['extension'] = $file->getClientOriginalExtension();

        }

        return $files;
    }
}
