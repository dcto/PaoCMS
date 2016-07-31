<?php

namespace App\Service;


class Upload extends Service
{

    public function files(){

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