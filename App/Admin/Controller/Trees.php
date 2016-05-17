<?php
namespace Admin\Controller;

use DB;
use Lang;
use Request;
use Response;

class Trees extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->assign('title', lang('menu.trees'));
    }


    public function index()
    {

        if(Request::isMethod('POST')){
           if(Request::get('id')>0) {
               $Trees['nodes'] = \App\Model\Trees::getTreeById(Request::get('id'));//= \App\Model\Trees::where('pid', Request::get('id'))->get() ?: array();
           }else{
               $Trees['nodes'][0] = \App\Model\Trees::getNodeById(Request::get('pid'));
           }
            return Response::Json($Trees);
        }

        $Trees = \App\Model\Trees::where('pid',0)->get();

        return Response::view('trees', ['trees'=>$Trees]);
    }


    public function create()
    {
        if(Request::isMethod('POST')){

            if(!Request::get('pid')){
                if(!$this->checkForm(Request::all(),['id'])) return $this->alert();
                if(\App\Model\Trees::where('tag',Request::get('tag'))->exists()){
                    return $this->alert(0, lang('alert.exist', lang('tag')));
                }
            }
            $data = $this->setNode(Request::all());

            $create = \App\Model\Trees::create($data);
            if(!$create) return Response::make(lang('alert.create_failure', lang('menu.trees')), 500);

            if(!Request::get('pid')) return $this->alert(1, lang('alert.create_success', lang('menu.trees')));
            return Response::Json($create);
        }

    }


    public function update()
    {

        \App\Model\Trees::whereId(Request::get('id'))->update($this->setNode(Request::all()));

        if(Request::has('tag')) {
            if(!$this->checkForm(Request::all(),['id'])) return $this->alert();
            return $this->alert(1, lang('alert.update_success', lang('menu.trees')));
        }

        $Trees = \App\Model\Trees::getNodeById(Request::get('id'));

        return Response::Json($Trees);

        //$update = \App\Model\Trees::wherePid(Request::get('pid'))->update(Request::all());
        //if(!$update) return Response::make(lang('alert.update_failure', lang('module.trees')), 500);
        //return Response::Json(Request::all());
    }

    public function delete()
    {
        $delete = \App\Model\Trees::whereId(Request::get('id'))->OrWhere('pid',Request::get('id'))->delete();
        if(Request::isMethod('GET')) return $this->alert(1, lang('alert.delete_failure', lang('menu.trees')));
        if(!$delete) return Response::make(lang('alert.delete_failure', lang('menu.trees')), 500);
        return Response::make(1);
    }


    public function change()
    {

    }

    private function setNode($node)
    {
        $node['level'] = $node['order'] = 0;
        if(!isset($node['position']) || !isset($node['related'])) return $node;
        if(isset($node['pid'])){
            $targetId = $node['related'];
            $targetNode = \App\Model\Trees::getNodeById($targetId);

            //更新原排序列表
            if(isset($node['id']) && $node['position']=='lastChild'){
                    $sourceNode = \App\Model\Trees::getNodeById($node['id']);
                    \App\Model\Trees::where('pid', $sourceNode['pid'])->where('order','>', $sourceNode['order'])->decrement('order', 1);
            }

            //更新层级
            if($node['position']=='firstChild' || $node['position']=='lastChild'){
                $node['pid'] = $targetId;
                $node['level'] = $targetNode['level']+1;
            }else{
                $node['level'] = $targetNode['level'];
            }


            //调整目标节点顺序
            if($node['position']=='firstChild'){

                \App\Model\Trees::where('pid',$node['pid'])->where('order', '>=', 0)->increment('order',1);

            }else if($node['position']=='lastChild'){

                //判断有子节点则排序+1
                if(\App\Model\Trees::where('pid',$node['pid'])->exists()) {
                    $node['order'] = \App\Model\Trees::where('pid', $node['pid'])->max('order') + 1;
                }else {
                    $node['order'] = 0;
                }

            }else if($node['position']=='before'){

                \App\Model\Trees::where('pid',$node['pid'])->where('order', '>=', $targetNode['order'])->increment('order',1);
                $node['order'] = $targetNode['order'];

            }else if($node['position']=='after'){

                    \App\Model\Trees::where('pid',$node['pid'])->where('order', '>', $targetNode['order'])->increment('order',1);
                    $node['order'] = $targetNode['order']+1;
            }


        }

        unset($node['position']);
        unset($node['related']);
        return $node;
    }
}