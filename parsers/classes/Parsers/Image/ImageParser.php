<?php

namespace Parsers\Image;

use finfo;

use Parsers\ProgressBar;

use Websm\Framework\ImgResize;

use Back\Files\Config as FilesConfig;

use Model\FileMan\Folder;
use Model\FileMan\File;

use Exceptions\FileNotFoundException;
use Exceptions\InvalidFileException;
use Exceptions\MainException;
use Exception;

class ImageParser
{
    private $dir;
    private $rootFolderId;
    private $errors = [];

    public function __construct(string $dir)
    {
        $this->dir = $dir;
        $this->rootFolderId = $this->getRootFolderId();
    }

    private function rglob($pattern)
    {
        $files = [];
        $files = glob($pattern);
        foreach (glob(
            dirname($pattern).'/*',
            GLOB_ONLYDIR|GLOB_NOSORT
        ) as $dir) {
            $files = array_merge(
                $files,
                $this->rglob($dir.'/'.basename($pattern))
            );
        }
        return $files;
    }

    private function findAllImages()
    {
        $pattern = $this->dir.'/*.jpg';
        $images = $this->rglob($pattern);
        return $images;
    }

    private function createRootFolder(Folder &$rootFolder)
    {
        $rootFolder->id = md5(uniqid());
        $rootFolder->cid = null;
        $rootFolder->date = time();
        $rootFolder->title = 'import_files';
        if (!$rootFolder->save()) {
            print_r("Не удалось создать корневую директорию\n");
            die();
        }
    }

    private function getRootFolderId()
    {
        $rootFolder = Folder::find([ 'title' => 'import_files' ])
            ->andWhere([ 'cid' => null ])
            ->get();
        if ($rootFolder->isNew()) {
            $this->createRootFolder($rootFolder);
        }
        return $rootFolder->id;
    }

    private function getImgRelPath(string $filePath)
    {
        $imgRelPath = str_replace($this->dir.'/', '', $filePath);
        return $imgRelPath;
    }

    private function getParentDirId(string $filePath)
    {
        $imgRelPath = $this->getImgRelPath($filePath);
        $fileName = basename($filePath);

        $name = str_replace('/'.$fileName, '', $imgRelPath);
        $dirId = md5($name);
        $dir = Folder::find([ 'id' => $dirId ])
            ->get();

        if ($dir->isNew()) {
            $dir->id = $dirId;
            $dir->cid = $this->rootFolderId;
            $dir->title = $name;
            $dir->date = time();
            if (!$dir->save()) {
                $this->errors['dir'][$name] = "Не удалось создать папку";
                return false;
            }
        }

        return $dir->id;
    }

    private function resizeImage(File $img, string $filePath)
    {
        $dst = FilesConfig::DATA_PATH.'/'.$img->getPictureName();
        ImgResize::resize($filePath, $dst);
        $img->size = filesize($dst);
        if (!$img->save()) {
            $this->errors['files'][$img->title] = 'Не удалось выполнить'
                .' resize для изображения';
        }
    }

    private function parseOne(string $filePath)
    {
        $rawName = basename($filePath);
        $imgRelPath = $this->getImgRelPath($filePath);
        $imgId = md5($imgRelPath);
        $parentDirId = $this->getParentDirId($filePath);
        if (!$parentDirId) {
            return false;
        }
        $img = File::find([ 'id' => $imgId ])
            ->andWhere([ 'cid' => $parentDirId ])
            ->get();
        if ($img->isNew()) {
            $img->id = $imgId;
            $img->cid = $parentDirId;
            $img->no_del = $parentDirId;
            $img->date = date('U');
            $img->text_date = date('Y-m-d H:i:s');

            preg_match(
                '/^(?<title>.*)(\.(?<ext>\w+))?$()/Uu',
                $rawName,
                $matches
            );
            $img->title = $matches['title'];
            $img->ext = mb_strtolower($matches['ext'], 'UTF-8');
            $img->size = filesize($filePath);
            $img->user = 'parser';

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $img->type = $finfo->file($filePath);

            if (!$img->save()) {
                $this->errors['files'][$img->title] = 'Не удалось создать'
                    .' файл в папке '.$parentDirId;
                return null;
            }

            $this->resizeImage($img, $filePath);
        }
    }

    public function parse()
    {
        $imagesFiles = $this->findAllImages();

        $progressTotal = count($imagesFiles);
        $progressBar = new ProgressBar($progressTotal, 'Images parser');

        foreach ($imagesFiles as $file) {
            $this->parseOne($file);
            $progressBar->makeStep();
        }

        $progressBar->close($this->errors);
    }
}
