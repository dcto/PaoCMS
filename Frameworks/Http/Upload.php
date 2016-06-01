<?php

namespace PAO\Http;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Upload extends UploadedFile
{


    /**
     * Create a new file instance from a base instance.
     *
     * @param  \Symfony\Component\HttpFoundation\File\UploadedFile  $file
     * @return static
     */
    public static function initialize(UploadedFile $file)
    {
        return $file instanceof static ? $file : new static(
            $file->getPathname(),
            $file->getClientOriginalName(),
            $file->getClientMimeType(),
            $file->getClientSize(),
            $file->getError()
        );
    }

    /**
     * Get the fully qualified path to the file.
     *
     * @return string
     */
    public function path()
    {
        return $this->getRealPath();
    }


    public function size()
    {
        return $this->getSize();
    }


    /**
     * Get the file's extension.
     *
     * @return string
     */
    public function extension()
    {
        return $this->guessExtension();
    }

    /**
     * Get the file's extension supplied by the client.
     *
     * @return string
     */
    public function clientExtension()
    {
        return $this->guessClientExtension();
    }

    /**
     * Get a filename for the file that is the MD5 hash of the contents.
     *
     * @param  string  $path
     * @return string
     */
    public function hash($path = null)
    {
        if ($path) {
            $path = rtrim($path, '/').'/';
        }

        return $path.md5_file($this->path()).'.'.$this->extension();
    }


    /**
     * Get file md5 this for hash alias name
     *
     * @param null $path
     * @return string
     */
    public function md5($path = null)
    {
        return $this->hash($path);
    }


    /**
     * Returns the extension based on the client mime type.
     *
     * @return null|string
     */
    public function mime()
    {
        return $this->getMimeType();
    }

}