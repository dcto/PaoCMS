<?php

namespace PAO\Crypt;

/**
* DES加密解密类库
* @author  11
* @version  1.2
*/
class Crypt
{
	/**
	 * [$key 密钥]
	 * @var [type]
	 */
	private $key;

	/**
	 * [$bit 进制]
	 * @var [type]
	 */
	private $bit; //128|256

	/**
	 * [$iv 偏移量]
	 * @var [type]
	 */
	private $iv;

	/**
	 * [__construct description]
	 * @param [type]  $key [密钥]
	 * @param integer $bit [进制]
	 * @param [type]  $iv  [偏移量]
	 */
	public function __construct($key = null, $bit = 128, $iv = "")
	{
	    $key = $key?:config('app.token');
		$this->key = $bit == 256 ? hash('sha256', $key, true) : hash('crc32b', $key, true);
        $this->bit = $bit;
		$this->iv = $iv != "" ? hash('MD5', $iv, true) : str_repeat(chr(0), 16);
	}

    /**
     * set get key
     *
     * @param $key
     */
	public function key($key = null)
    {
        return $key ? $this->key = $key : $this->key;
    }

    /**
     * [en 加密字符串]
     *
     * @param $str      [明文]
     * @param bool $key [密钥]
     * @return string
     */
	public function en($str, $key = false)
    {
		//Open
		$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
		mcrypt_generic_init($module, $this->key(), $this->iv);

		//Padding
		$block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC); //Get Block Size
		$pad = $block - (strlen($str) % $block); //Compute how many characters need to pad
		$str .= str_repeat(chr($pad), $pad); // After pad, the str length must be equal to block or its integer multiples

		//Encrypt
		$encrypted = mcrypt_generic($module, $str);

		//Close
		mcrypt_generic_deinit($module);
		mcrypt_module_close($module);

		//Return
		return bin2hex($encrypted);
	}

    /**
     * [de 解密字符串]
     *
     * @param $str          [密文]
     * @param bool $key     [密钥]
     * @return string
     */
	public function de($str, $key = false)
	{
		//Open
		$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
		mcrypt_generic_init($module, $this->key(), $this->iv);
//var_dump(ctype_xdigit($str));die;
		//Decrypt
		$str = mdecrypt_generic($module, hex2bin($str)); //Get original str

		//Close
		mcrypt_generic_deinit($module);
		mcrypt_module_close($module);

		//Depadding
		$last = ord(substr($str, -1)); //pad value and pad count
		$str = substr($str, 0, strlen($str) - $last);

		//Return
		return $str;
	}
}
