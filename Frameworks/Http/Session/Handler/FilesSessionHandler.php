<?php

namespace PAO\Http\Session\Handler;


class FilesSessionHandler extends \SessionHandler implements \SessionHandlerInterface
{


    /**
     * Constructor.
     *
     * @param string $savePath Path of directory to save session files
     *                         Default null will leave setting as defined by PHP.
     *                         '/path', 'N;/path', or 'N;octal-mode;/path
     *
     * @see http://php.net/session.configuration.php#ini.session.save-path for further details.
     *
     * @throws \InvalidArgumentException On invalid $savePath
     */
    public function __construct($path = null)
    {
        $savePath = path($path?:config('dir.session'));

        if (null === $savePath) {
            $savePath = ini_get('session.save_path');
        }

        $baseDir = $savePath;

        if ($count = substr_count($savePath, ';')) {
            if ($count > 2) {
                throw new \InvalidArgumentException(sprintf('Invalid argument $savePath \'%s\'', $savePath));
            }

            // characters after last ';' are the path
            $baseDir = ltrim(strrchr($savePath, ';'), ';');
        }

        if ($baseDir && !is_dir($baseDir) && !@mkdir($baseDir, 0777, true) && !is_dir($baseDir)) {
            throw new \RuntimeException(sprintf('Session Storage was not able to create directory "%s"', $baseDir));
        }

        ini_set('session.save_path', $savePath);
        ini_set('session.save_handler', 'files');
    }


}
