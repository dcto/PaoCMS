<?php
/**
 * RSA加密解密
 *
 */

class Rsa {
    private static $PRIVATE_KEY = '-----BEGIN PRIVATE KEY-----
xDSN79RE9lZBKHD7r6uX2ovyqFVcqhOawsZMVq9UjZEahRuZZSfGlzwqO84PdfRE
YzCMX71R5bevJP37R5fBX4fZ22hMloFNNdokiYZ4l4ZJ4ooBWeGdGqRsHW3z3fE3
... ...
VnlUPzRTggamditCVYaoNNqMuBtV7PsRZtZWV2UV7WSSioExcuKixBPYCmJpMXKV
138YMtyf9DCZYgX7wuNmCqQMfJGvnfRKfLp5ks0iZhqdi4gP0hdINAyCZXzMwswc
Xa5ULb6LHyYk
-----END PRIVATE KEY-----';


    private static $PUBLIC_KEY = '-----BEGIN PUBLIC KEY-----
pqYokHJS6TELqPPFPWzrV3F0WuLfWymrv9DWldmy9V0j3ITnGUidRACzIiCggvBX
... ...
pDoaIVXCPIUFZ7dFi0cjho5FZoUGf6jgQG0BeFTHbY3HAcfDGiOAWD4e4DCYt3Eb
wdpFDxpbN5opKjOGNL45CLGU
-----END PUBLIC KEY-----';

    /**
     *返回对应的私钥
     */
    private static function getPrivateKey(){
        $privKey = self::$PRIVATE_KEY;
        return openssl_pkey_get_private($privKey);
    }

    /**
     *返回对应的私钥
     */
    private static function getPublicKey(){
        $publicKey = self::$PUBLIC_KEY;
        return openssl_pkey_get_public($publicKey);
    }

    /**
     * 私钥加密
     */
    public static function privEncrypt($data)
    {
        if(!is_string($data)){
            return null;
        }
        return openssl_private_encrypt($data,$encrypted,self::getPrivateKey())? base64_encode($encrypted) : null;
    }


    /**
     * 私钥解密
     */
    public static function privDecrypt($encrypted)
    {
        if(!is_string($encrypted)){
            return null;
        }
        return (openssl_private_decrypt(base64_decode($encrypted), $decrypted, self::getPrivateKey()))? $decrypted : null;
    }

    /**
     * 公钥解密
     */
    public static function publicDecrypt($encrypted)
    {
        if(!is_string($encrypted)){
            return null;
        }
        return (openssl_public_decrypt(base64_decode($encrypted), $decrypted, self::getPublicKey()))? $decrypted : null;
    }

    /**
     * 私钥解密,不做Base64解码
     * @param unknown $encrypted
     * @return NULL|Ambigous <unknown, NULL>
     */
    public static function privDecryptNB64($encrypted)
    {
        if(!is_string($encrypted)){
            return null;
        }
        return (openssl_private_decrypt($encrypted, $decrypted, self::getPrivateKey()))? $decrypted : null;
    }

    /**
     * 分段私钥解密
     * @param string $encrypted
     * @return Ambigous <string, NULL, Ambigous>
     */
    public static function partPrivDecrypt($encrypted){
        $encrypted = base64_decode($encrypted);
        $fiveMBs = 50 * 1024 * 1024;
//      $file = FCPATH. 'jktest_'.time().'.txt';
        $fp = fopen("php://memory",'w+b');
        fwrite($fp, $encrypted);
        fseek($fp, 0);
        $bContent = '';
        while (!feof($fp)) {
            $bContent .= self::privDecryptNB64(fread($fp, 128));
        }
        fclose($fp);
//      unlink($file);
        return $bContent;
    }

}