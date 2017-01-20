<?php

namespace Illuminate\Http;

use PAO\Http\FileTrait;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

class File extends SymfonyFile
{
    use FileTrait;
}
