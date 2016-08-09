<?php

namespace App\Controller\Admin;

/**
*
*/
class Access extends Controller
{

    public function login()
    {

        return view('admin/login');
    }


    public function logout()
    {
        return redirect(url('@login'));
    }


    /**
     * 身份方验正
     */
    public function checkAccess()
    {
        //if(!$this->uid) return redirect(url('@login'));
    }


}
