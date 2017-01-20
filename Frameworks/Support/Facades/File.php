<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class File
 *
 * @method static PAO\FileSystem\FileSystem has(string $path)
 * @method static PAO\FileSystem\FileSystem get(string $path, bool $lock = false)
 * @method static PAO\FileSystem\FileSystem del(string $path)
 * @method static PAO\FileSystem\FileSystem set(string $path, string $contents, bool $lock = false)
 * @method static PAO\FileSystem\FileSystem put(string $path, string $contents, bool $lock = false)
 * @method static PAO\FileSystem\FileSystem copy(string $path, string $target)
 * @method static PAO\FileSystem\FileSystem move(string $path, string $target)
 * @method static PAO\FileSystem\FileSystem size(string $path)
 * @method static PAO\FileSystem\FileSystem type(string $path)
 * @method static PAO\FileSystem\FileSystem mimeType(string $path)
 * @method static PAO\FileSystem\FileSystem name(string $path)
 * @method static PAO\FileSystem\FileSystem dirname(string $path)
 * @method static PAO\FileSystem\FileSystem basename(string $path)
 * @method static PAO\FileSystem\FileSystem extension(string $path)
 * @method static PAO\FileSystem\FileSystem glob(string $pattern, int $flags = 0)
 * @method static PAO\FileSystem\FileSystem files(string $directory)
 * @method static PAO\FileSystem\FileSystem allFiles(string $directory, bool $hidden = false)
 * @method static PAO\FileSystem\FileSystem isFile(string $file)
 * @method static PAO\FileSystem\FileSystem isDir(string $path)
 * @method static PAO\FileSystem\FileSystem mkDir(string $path, int $mode = 0755, bool $recursive = true, bool $force = false)
 * @method static PAO\FileSystem\FileSystem copyDir(string $dir, string $target, mixed $options = null)
 * @method static PAO\FileSystem\FileSystem exists(string $path)
 * @method static PAO\FileSystem\FileSystem append(string $path, string $data)
 * @method static PAO\FileSystem\FileSystem prepend(string $path, string $data)
 * @method static PAO\FileSystem\FileSystem directories(string $directory)
 * @method static PAO\FileSystem\FileSystem isDirectory(string $directory)
 * @method static PAO\FileSystem\FileSystem makeDirectory(string $path, int $mode = 0755, bool $recursive = false, bool $force = false)
 * @method static PAO\FileSystem\FileSystem moveDirectory(string $from, string $to, bool $overwrite = false)
 * @method static PAO\FileSystem\FileSystem copyDirectory(string $directory, string $destination, int $options = null)
 * @method static PAO\FileSystem\FileSystem deleteDirectory(string $directory, bool $preserve = false)
 * @method static PAO\FileSystem\FileSystem cleanDirectory(string $directory, bool $preserve = false)
 * @method static PAO\FileSystem\FileSystem sharedGet(string $path)
 * @method static PAO\FileSystem\FileSystem getRequire(string $path)
 * @method static PAO\FileSystem\FileSystem requireOnce(string $path)
 * @method static PAO\FileSystem\FileSystem lastModified(string $path)
 * @method static PAO\FileSystem\FileSystem isWritable(string $path)
 */
class File extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'file';
    }
}
