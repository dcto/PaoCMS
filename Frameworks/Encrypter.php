<?php

namespace PAO;

/**
* DES加密解密类库
* @author  11
* @version  1.1
*/
class Encrypter
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
		$this->key = $bit == 256 ? hash('SHA256', $key, true) : hash('MD5', $key, true);
		$this->iv = $iv != "" ? hash('MD5', $iv, true) : str_repeat(chr(0), 16);
	}


	/**
	 * [encrypt 加密字符串]
	 * @param  [string] $string [明文]
	 * @return [string]         [密文]
	 */
	public function encrypt($str)
	{
		//Open
		$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
		mcrypt_generic_init($module, $this->key, $this->iv);

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
	 * [decrypt 解密字符串]
	 * @param  [string] $str [密文]
	 * @return [string]      [明文]
	 */
	public function decrypt($str)
	{
		//Open
		$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
		mcrypt_generic_init($module, $this->key, $this->iv);

		//Decrypt
		$str = mdecrypt_generic($module, hex2bin($str)); //Get original str

		//Close
		mcrypt_generic_deinit($module);
		mcrypt_module_close($module);

		//Depadding
		$slast = ord(substr($str, -1)); //pad value and pad count
		$str = substr($str, 0, strlen($str) - $slast);

		//Return
		return $str;
	}
}
