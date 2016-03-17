<?php

namespace PAO;

use PAO\Exception\SystemException;


/**
 * 字符串验证器
 * Class Validator
 * @package PAO
 * @author 11
 * @version 201603167
 */
class Validator
{
    private $string;

    private $regular = [
        'username'=>'/^[a-z0-9_]{4,16}$/',
        'password'=>'/((?=.*\d)(?=.*[a-z]).{8,})/',
        'chinese'=>'/\p{Han}+/u',
        'english'=>'/^\w+$/',
        'price'=>'/(\d+\.\d{1,2})/',
        'qq'=>'/^[1-9][0-9]{4,9}$/',
        'tel'=>'/^\d{3}-\d{8}|\d{4}-\d{7,8}$/',
        'mobile'=>'/^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8}$/',
        'zip'=>'/^[1-9]\d{5}(?!\d)$/',
        'phone'=>'/((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)/',
        'idCard'=>'/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/',
        'letter'=>'/^[A-Za-z]+$/',
        'credit'=>'/4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|6(?:011|5[0-9]{2})[0-9]{12}|(?:2131|1800|35\d{3})\d{11}/g'
    ];

    public static $Error = false;

    /**
     * 验证结果
     */
    public function is($is)
    {
        return self::$Error==$is ? true : false;
    }

    /**
     * 错误信息
     */
    private function setError($message)
    {
        $message = $message ?: true;
        self::$Error = self::$Error ?: $message;
    }


    /**
     * 返回验证错误结果
     * @return bool
     */
    public function error()
    {
        return self::$Error;
    }

    /**
     * 验证字符串
     * @param $string
     * @return $this
     */
    public function make($string)
    {
        $this->string = $string;
        return $this;
    }

    /**
     * 空字符串检测
     * @param string $message
     * @return $this
     */
    public function null($message = 'This field is required.')
    {
        if(strlen(trim($this->string))<1){
            $this->setError($message);
        }
        return $this;
    }

    /**
     * 判断两者相等
     * @param $string
     * @param string $message
     */
    public function eq($val, $message = 'The both string %s and %s must be equal')
    {
        if($this->string != $val){
            $this->setError(sprintf($message, $this->string, $val));
        }
        return $this;
    }

    /**
     * 判断大于
     * @param $val
     * @param string $message
     */
    public function gt($val, $message = 'The [%s] must be greater than %s .')
    {
        if($this->string<$val){
            $this->setError(sprintf($message, $this->string, $val));
        }
        return $this;
    }

    /**
     * 判断大于
     * @param $val
     * @param string $message
     */
    public function lt($val, $message = 'The [%s] must be less than %s .')
    {
        if($this->string>$val){
            $this->setError(sprintf($message, $this->string, $val));
        }
        return $this;
    }

    /**
     * 指定长度限制
     * @param $limit
     * @param string $message
     * @return $this
     */
    public function length($limit, $message='The [%s] length must be equal to %d character.')
    {
        if(strlen($this->string)!=$limit){
            $this->setError(sprintf($message, $this->string, $limit));
        }
        return $this;
    }

    /**
     * 最小长度限制
     * @param $limit
     * @param string $message
     * @return $this
     */
    public function min($limit, $message='Input %s must be greater than %d length.')
    {
        if(strlen($this->string)<$limit){
            $this->setError(sprintf($message, $this->string, $limit));
        }
        return $this;
    }

    /**
     * 最大长度限制
     * @param $limit
     * @param string $message
     * @return $this
     */
    public function max($limit, $message='Input %s must be less than %d length.')
    {
        if(strlen($this->string)>$limit){
            $this->setError(sprintf($message, $this->string, $limit));
        }
        return $this;
    }

    public function datetime($format, $message = 'The input datetime %s does not match of format %s.')
    {
        $d = \DateTime::createFromFormat($format, $this->string);
        $result = $d && $d->format($format) == $this->string;
        if(!$result){
            $this->setError(sprintf($message, $this->string, $format));
        }
        return $this;
    }

    /**
     * 验证整数
     * @param $message
     * @return $this
     */
    public function integer($message = 'The input %s is not a integer.')
    {
        if(!filter_var($this->string, FILTER_VALIDATE_INT)){
            $this->setError(sprintf($message, $this->string));
        }
        return $this;
    }

    /**
     * 验证浮点数
     * @param string $message
     * @return $this
     */
    public function float($message = 'The input %s is not a float.')
    {
        if(!filter_var($this->string, FILTER_VALIDATE_FLOAT)){
            $this->setError(sprintf($message, $this->string));
        }
        return $this;
    }

    /**
     * 验证布尔值类型
     * @param string $message
     * @return $this
     */
    public function boolean($message = 'The input %s is not a boolean.')
    {
        if(!filter_var($this->string, FILTER_VALIDATE_BOOLEAN)){
            $this->setError(sprintf($message, $this->string));
        }
        return $this;
    }

    /**
     * 验证url格式
     * @param string $message
     * @return $this
     */
    public function url($message = 'The input url %s is not available.')
    {
        if(!filter_var($this->string, FILTER_VALIDATE_URL)){
            $this->setError(sprintf($message, $this->string));
        }
        return $this;
    }


    /**
     * 验证电子邮箱
     * @param string $message
     * @return $this
     */
    public function email($message = 'The email %s format was incorrect.')
    {
         if(!filter_var($this->string, FILTER_VALIDATE_EMAIL)){
             $this->setError(sprintf($message, $this->string));
         }
        return $this;
    }

    /**
     * 验证IP地址
     * @param string $message
     * @return $this
     */
    public function ip($message = 'The input ip %s format was incorrect.')
    {
        if(!filter_var($this->string, FILTER_VALIDATE_IP)){
            $this->setError(sprintf($message, $this->string));
        }
        return $this;
    }

    /**
     * 验证mac地址
     * @param string $message
     * @return $this
     */
    public function mac($message = 'The input mac address %s format was incorrect.')
    {
        if(!filter_var($this->string, FILTER_VALIDATE_MAC)){
            $this->setError(sprintf($message, $this->string));
        }
        return $this;
    }

    /**
     * 自定义验证规则
     * @param $regex
     * @param string $message
     * @return $this
     */
    public function regExp($regexp, $message = 'The input %s does not match the rule %s ')
    {
        if(!filter_var($this->string, FILTER_VALIDATE_REGEXP, array('options'=>array('regexp'=>$regexp)))){
            $this->setError(sprintf($message, $this->string, $regexp));
        }
        return $this;
    }

    /**
     * 自定义验证调用
     * @param $name
     * @param $args
     * @return Validator
     */
    public function __call($name, $args)
    {
        if(isset($this->regular[$name])){
            if(isset($args[0])) {
                return $this->regExp($this->regular[$name], $args[0]);
            }else{
                return $this->regExp($this->regular[$name]);
            }
        }else{
            throw new SystemException(sprintf("The validator regular [$name] was not found."));
        }
    }
}