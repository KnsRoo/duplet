<?php

namespace Back\Gallery;

use Back\Files\Picture;
use Model\FileMan\Folder;
use Model\FileMan\File;
use Model\Journal;

use Back\Gallery\Actions\PhotoActions;
use Back\Gallery\Actions\AlbumActions;

use Back\Widgets\GalleryMenu\MenuWidget as Menu;

use Websm\Framework\Db\Qb;
use Websm\Framework\Notify;
use Websm\Framework\Sort as NewSort;
use Websm\Framework\Chpu;
use Websm\Framework\Response;
use Websm\Framework\Router\Router;

//use Core\Misc\Path;
use Core\Misc\NewPath\NewPath;
use Core\Misc\Pager\Pager;
use Core\ModuleInterface;
use Core\Users;

class Gallery extends Response implements ModuleInterface {

    const TEMPLATES = __DIR__ . '/temp/';

    public $permitions = [
        'creating-album' => 'off',
        'creating-photo' => 'off',
        'deleting-album' => 'off',
        'deleting-photo' => 'off',
        'editing-album' => 'off',
        'editing-photo' => 'off',
        'chroot' => null,
    ];

    protected $model;
    protected $url = 'gallery';
    protected $title = 'Галерея';
    protected $data = [];
    protected $gallery = null;
    protected $album = null;
    protected $albumObject = null;
    protected $photo;
    protected $path;
    protected $pager = '';
    protected $page = 1;
    protected $parent = null;

    public function __construct(&$props = []) { }

    public function setSettings(Array &$props = []) {

        $this->permitions = array_merge($this->permitions, $props);
        $this->permitions['chroot'] = $this->permitions['chroot'] ?: null;

    }

    public function getSettings() { return $this->permitions; }

    public function getRoutes() {

        $group = Router::group();

        $albumActions = new AlbumActions($this->permitions);

        $group->mount('/', $albumActions->getRoutes());

        $photoActions = new PhotoActions($this->permitions);

        $group->mount('/', $photoActions->getRoutes());

        return $group;

    }

    public function init($req, $next) {

        $this->css = [
            'css/gallery.css',
            'css/filesMin.css',
        ];

        $this->js = array_merge($this->js, [
            'js/gallery.js',
            'js/filesMin.js',
            'plugins/ckeditor/ckeditor.js',
        ]);

        $route = Router::instance();

        $route->mount('/', $this->getRoutes());

        $next();

    }

    public function getContent() {

        $rAddPhoto = Router::byName('Gallery.addPhoto');
        $rAddAlbum = Router::byName('Gallery.addAlbum');
        $rRenderAlbums = Router::byName('Albums.render');
        $rDefault = Router::instance();

        $check = Menu::getHtml();

        $photos = '';
        $albums = '';
        $createPhotos = '';
        $createAlbums = '';

        switch ($check) {
            case 'photos':
                $photos = 'action';
                break;
            case 'albums':
                $albums = 'action';
                break;
            case 'createPhotos':
                $createPhotos = 'action';
                break;
            case 'createAlbums':
                $createAlbums = 'action';
                break;
        }

        $data = [
            'photos' => $photos,
            'albums' => $albums,
            'rDefault' => $rDefault,
            'rAddPhoto' => $rAddPhoto,
            'rAddAlbum' => $rAddAlbum,
            'createPhotos' => $createPhotos,
            'createAlbums' => $createAlbums,
            'rRenderAlbums' => $rRenderAlbums,
        ];

        return $this->render(self::TEMPLATES . 'default.tpl', $data);

    }

    public function getSettingsContent($name = '', Array $permitions) {

        return $this->render(self::TEMPLATES . 'settings.tpl', $permitions);

    }

}
