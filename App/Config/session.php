<?php
defined('PAO') || die('The PaoCMS Load Error');

/**
 * session 存储方式
 */
$session['handler'] = 'files';

/**
 * 以文件形试保存
 */
$session['storage']['files']['save_path'] = RUNTIME.'/Session';

/**
 * 保存到redis配置
 * 参见 cache redis中的配置
 */
$session['storage']['redis'] = config('cache.redis.default');

/**
 * 保存到memcache
 */
$session['storage']['memcache'] = array();

/**
 * 保存到memcached
 */
$session['storage']['memcached'] = array();

/**
 * 保存到mongodb
 */
$session['storage']['mongodb'] = array();

/**
 * 保存到据据库名称参见database配置文件
 */
$session['storage']['database']['db_host'] = 'localhost';
$session['storage']['database']['db_port'] = '3306';
$session['storage']['database']['db_name'] = 'pao';
$session['storage']['database']['db_username'] = 'root';
$session['storage']['database']['db_password'] = 'root';
$session['storage']['database']['db_table'] = 'pao_session';
$session['storage']['database']['db_id_col'] = 'id';
$session['storage']['database']['db_data_col'] = 'session';
$session['storage']['database']['db_time_col'] = 'time';
$session['storage']['database']['db_lifetime_col'] = 'life_time';
$session['storage']['database']['db_connection_options'] = array();
$session['storage']['database']['lock_mode'] = 2;


/**
 * session 选项
 */
$session['options'] = array(
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
);