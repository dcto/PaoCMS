<?php
namespace PAO\Captcha;



use Illuminate\Support\Str;
use Illuminate\Container\Container;
use PAO\Exception\SystemException;

/**
 * Class Captcha
 * @Copyright:PaoCMS
 * @Version:1.0
 */
class Captcha {

    /**
     * ��������
     * @var array
     */
    public $fonts;


    protected $token;

    /**
     * ���
     * @var int
     */
    protected $width;

    /**
     * �߶�
     * @var int
     */
    protected $height;


    public function __construct($width = 100, $height = 30, $fonts = null)
    {
        $this->width = $width;
        $this->height = $height;
        $this->token = 'pao_captcha_key';
        $this->fonts = $fonts ?: array('Exo-Bold.otf', 'MetalMania-Regular.ttf', 'Syncopate-Bold.ttf', 'VastShadow-Regular.ttf');
    }

    /**
     * verify Captcha and Str return true or false
     * @version 1.0
     * @param null $str
     * @return bool
     */
    public function is($input, $case = false)
    {
        $input = sprintf("%s", trim($input));
        $codes = Container::getInstance()->make('session')->get($this->token);

        if($case && strtolower($input) === strtolower($codes)){
            return true;
        }else if($input === $codes){
            return true;
        }else{
            return false;
        }
    }


    /**
     * [make]
     *
     * @param int $width [���]
     * @param int $height [�߶�]
     * @param int $obstruct [���Ŷ�]
     * @return mixed
     * @author 11.
     */
    public function make($width = 100, $height = 30, $obstruct = 5)
    {
        if (!extension_loaded("gd"))  throw new SystemException ("Captcha Unable Load GD Library Copyright PaoCMS System");

        /**
         * ���ÿ��
         */
        $this->width = $width;

        /**
         * ���ø߶�
         */
        $this->height = $height;


        /**
         * ��ȡ�����
         */
        $phrase = $this->getRandomCode(4);

        /**
         * ��������
         */
        $images = $this->CreateCanvas();

        /**
         * ���ñ���
         */
        $this->setBackground($images);

        /**
         * ���ø�����
         */
        $this->setInterference($images, $obstruct);

        /**
         * ���������
         */
        $this->setImageString($images, $phrase);

        $container = Container::getInstance();

        /**
         * Session��¼
         */
        $container->make('session')->set($this->token, $phrase);

        if($container->make('session')->get($this->token) != $phrase) throw new SystemException ('The captcha set session was error.');

        imagepng($images);

        imagedestroy($images);

        return $container->make('response')->make(uniqid(), 200,
            array(
                'Expires' => 'Mon,26 Jul 1997 05:00:00 GMT',
                'Last-Modified' => gmdate("D,d M Y H:i:s")."GMT",
                'Cache-Control' => "no-store,no-cache,must-revalidate",
                'Pragma' => 'no-cache',
                'Content-Type' => 'image/png',
                'Content-Disposition' => 'inline; filename="' . $images. '"'
            )
        );
    }


    /**
     * [CreateCanvas ��������]
     *
     * @return resource
     * @author 11.
     */
    private function CreateCanvas()
    {
        return imagecreatetruecolor($this->width, $this->height);
    }


    /**
     * [setBackground ����ͼƬ����]
     *
     * @param $images
     * @return mixed
     * @author 11.
     */
    private function setBackground($images)
    {
        if(!is_resource($images)) throw new SystemException('The set resource background is not a not available');

        $background = imagecolorallocate($images, rand(200, 255),rand(200, 255), rand(150, 255));
        imagefill($images,0,0,$background);
    }


    /**
     * [setInterference ����ͼƬ����]
     *
     * @param $images
     * @author 11.
     */
    private function setInterference($images, $level = 5)
    {
        for($i = 0; $i<$level; $i++)
        {
            $x = rand(0, $this->width);
            $y = rand(0, $this->height);
            $angle = rand(0, 360);
            $size = rand(8,12);

            $color = imagecolorallocate($images, rand(40,140),rand(40,140),rand(40,140));
            /**
             * ��������
             */
            imageline($images, rand(0,$this->width), rand(0,$this->height), rand(0,$this->width), rand(0, $this->height), $color);

            /**
             * �ַ�����
             */
            $codes = $this->getRandomCode(rand(1,5), '0123456789~!@#$%^&*()_+=|');
            imagestring($images,rand(5,10), $x, $y, $codes, $color);
        }
    }

    /**
     * [setImageString ������֤��]
     *
     * @param $images
     * @param $phrase
     * @return mixed
     * @author 11.
     */
    private function setImageString($images, $phrase)
    {
        $size = min($this->width, $this->height*3) / (strlen($phrase)) ;
        $span = (integer) ($this->width * 0.9 / strlen($phrase));
        for($i = 0, $strLen = strlen($phrase); $i < $strLen; $i++)
        {
            $color = imagecolorallocate($images, rand(0,50),rand(50,100),rand(100,180));
            $angle = rand(-20, 30);
            $box = imageftbbox($size, $angle, $this->getFont(), $phrase[$i]);
            $x = $span / 4 + $i * $span + 2;
            $y = $this->height / 2 + ($box[2] - $box[5]) / 4;
            imagefttext($images, $size, $angle, $x, $y, $color, $this->getFont(), $phrase[$i]);
            //fix the imagefttext bug before imagepng and imagedestroy
            header("content-type: image/png");
        }
        return $images;
    }

    /**
     * [getRandomCode ��ȡ����ַ���]
     *
     * @param int  $length
     * @param null $pool
     * @return string
     * @author 11.
     */
    private function getRandomCode($length = 4, $pool = null)
    {
        $pool = $pool?:'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    /**
     * [getFont ��ȡ����]
     *
     * @param null $font
     * @return string
     * @author 11.
     */
    private function getFont($font = null)
    {
        $FontDir = __DIR__.DIRECTORY_SEPARATOR;
        if($font && is_readable($FontDir.$font))
        {
            return $FontDir.$font;
        }else{

            if(is_readable($font = $FontDir.$this->fonts[array_rand($this->fonts)]))
            {
                return $font;
            }else
            {
                throw new SystemException('The font ['. $font.'] was can\'t not readable.');
            }

        }
    }

}
