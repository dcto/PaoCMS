<?php
defined('PAO') || die('The PaoCMS Load Error');


return array(
    'storage'=>array(

            'files'=>[
                'save_path'=>PAO.'/RunTime/Session',

            ],

            'pdo'=>[
                'db_host'=>'192.168.11.11',
                'db_port'=>'3306',
                'db_name'=>'paocms',
                'db_connection_options',
                'db_username'=>'root',
                'db_password'=>'root',
                'db_table'=>'pao_session',
                'db_id_col',
                'db_data_col',
                'db_lifetime_col',
                'db_time_col',
                'lock_mode'
            ],

            'memcache'=>[],
            'memcached'=>[],
            'mongodb'=>[],
        ),

        /**
         * the options configure @see http://php.net/session.configuration
         * cache_limiter, "" (use "0" to prevent headers from being sent entirely).
         * cookie_domain, ""
         * cookie_httponly, ""
         * cookie_lifetime, "0"
         * cookie_path, "/"
         * cookie_secure, ""
         * entropy_file, ""
         * entropy_length, "0"
         * gc_divisor, "100"
         * gc_maxlifetime, "1440"
         * gc_probability, "1"
         * hash_bits_per_character, "4"
         * hash_function, "0"
         * name, "PHPSESSID"
         * referer_check, ""
         * serialize_handler, "php"
         * use_cookies, "1"
         * use_only_cookies, "1"
         * use_trans_sid, "0"
         * upload_progress.enabled, "1"
         * upload_progress.cleanup, "1"
         * upload_progress.prefix, "upload_progress_"
         * upload_progress.name, "PHP_SESSION_UPLOAD_PROGRESS"
         * upload_progress.freq, "1%"
         * upload_progress.min-freq, "1"
         * url_rewriter.tags, "a=href,area=href,frame=src,form=,fieldset="
         */
        'options' => array()

);