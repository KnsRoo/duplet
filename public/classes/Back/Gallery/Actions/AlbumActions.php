<?php

namespace Back\Gallery\Actions;

use Model\Journal;
use Model\Gallery\Album;
use Model\Gallery\Photo;

use Back\Widgets\GalleryMenu\MenuWidget as Menu;

use Core\Users;
use Websm\Framework\Response;
use Websm\Framework\Notify;
use Websm\Framework\Sort as NewSort;
use Websm\Framework\Chpu;
use Websm\Framework\Router\Router;
use Websm\Framework\Pager\Pager;

class AlbumActions extends Response {

    const TEMPLATES = __DIR__ . '/../temp/';
    const ITEMS_ON_PAGE = 16;

    public $permitions = [];

    public function __construct($permitions = []) {

        $this->permitions = $permitions;

    }

    public function setSettings(Array &$props = []) {

        $this->permitions = array_merge($this->permitions, $props);
        $this->permitions['chroot'] = $this->permitions['chroot'] ?: null;

    }

    public function getSettings() { return $this->permitions; }

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', [$this, 'parseRequest'], ['end' => false]);

        $group->addGet('/render', [$this, 'renderAlbums'])
            ->setName('Albums.render');

        $group->addGet('/render-album', [$this, 'addAlbum'])
            ->setName('Gallery.addAlbum');

        $group->addGet('/album-photos-:id', [$this, 'albumPhotos'])
            ->setName('Album.albumPhotos');

        $group->addPost('/create-album', [$this, 'createAlbum'])
            ->setName('Gallery.createAlbum');

        $group->addGet('/update-album-:id', [$this, 'updateAlbum'])
            ->setName('Album.update');

        $group->addPut('/update-album-:id', [$this, 'putUpdateAlbum'])
            ->setName('Album.putUpdate');

        $group->addPut('/update-visibility-album-:id', [$this, 'upViAlbum'])
            ->setName('Album.updateVisible');

        $group->addDelete('/delete-album-:id', [$this, 'deleteAlbum'])
            ->setName('Album.delete');

        $group->addPut('/update-sort-album-:id', [$this, 'upSortAlbum'])
            ->setName('Album.updateSort');

        $group->addGet('/render-albums-:id', [$this, 'renderAlbumsID'])
            ->setName('Album.renderAlbumsID');

        return $group;

    }

    public function parseRequest($req, $next) {

        $page = &$_REQUEST['page'];
        $this->page = $page ?: 1;
        $next();

    }

    public function renderAlbums() {

        Menu::setContent('albums');
        $albums = Album::getAllAlbums();
        $edit = Router::byName('Album.update');
        $sort = Router::byName('Album.updateSort');
        $delete = Router::byName('Album.delete');
        $visible = Router::byName('Album.updateVisible');
        $albumOP = Router::byName('Album.albumPhotos');
        $renderAID = Router::byName('Album.renderAlbumsID');

        $data = [
            'edit' => $edit,
            'sort' => $sort,
            'albums' => $albums,
            'delete' => $delete,
            'visible' => $visible,
            'albumOP' => $albumOP,
            'renderAID' => $renderAID
        ];

        $this->content = $this->render(self::TEMPLATES . 'album.tpl', $data);

    }

    public function addAlbum() {

        if ($this->permitions['creating-album'] != 'on') {

            Notify::push('Нет полномочий для изменеия.', 'err');
            $this->back();

        }

        $albums = Album::query()
            ->select(['id', 'title'])
            ->getAll();

        $rCreate = Router::byName('Gallery.createAlbum');
        $rDefault = Router::instance();

        $data = [
            'albums' => $albums,
            'rCreate' => $rCreate,
            'rDefault' => $rDefault
        ];

        Menu::setContent('createAlbums');

        $this->content = $this->render(self::TEMPLATES . 'albumNew.tpl', $data);

    }

    public function albumPhotos($req) {

        $album = Album::getAlbumId($req['id']);
        $photos = Photo::getPhotosCid($req['id']);

        if (!$photos) {

            Notify::push('В альбоме нет фотографий.', 'err');

            $this->back();

        }

        $route = Router::byName('Album.albumPhotos');
        $action = $route->getAbsolutePath() . '/';

        $pager = new Pager(count($photos), $this->page, self::ITEMS_ON_PAGE);
        $photos = $pager->chunkItems($photos);

        $query = clone $req->query;

        $data = [
            'pager' => $pager,
            'action' => $action,
            'query' => clone $query,
        ];

        $pager = $this->render(self::TEMPLATES . 'pager.tpl', $data);

        $edit = Router::byName('Photo.update');
        $visible = Router::byName('Gallery.updateVisible');
        $sort = Router::byName('Photo.updateSort');
        $delete = Router::byName('Photo.delete');
        $albumOP = Router::byName('Album.albumPhotos');

        $data = [
            'edit' => $edit,
            'sort' => $sort,
            'pager' => $pager,
            'album' => $album,
            'photos' => $photos,
            'delete' => $delete,
            'visible' => $visible,
            'albumOP' => $albumOP,
        ];

        $this->content = $this->render(self::TEMPLATES . 'photoInfo.tpl', $data);

    }

    public function createAlbum() {

        if ($this->permitions['creating-album'] != 'on') {

            Notify::push('Нет полномочий для изменеия.', 'err');
            $this->back();

        }

        $ar = new Album('create');
        $ar->bind($_POST['create']);
        $ar->id = md5(uniqid());
        $ar->hash = md5($ar->title);
        Chpu::inject($ar);

        if ($ar->save()) {

            Notify::push('Альбом успешно создан.', 'ok');
            NewSort::init($ar)->normalise();

            Journal::add(Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> создал альбом "/'.$ar->chpu.'".',
                'Альбома');

        } else {

            Notify::push('Ошибка создания альбома.', 'err');
            foreach($ar->getErrors() as $errors)
                Notify::pushArray($errors);

        }

        $this->back();

    }

    public function updateAlbum($req) {

        if ($this->permitions['editing-album'] != 'on') {

            Notify::push('Нет полномочий для изменеия.', 'err');
            $this->back();

        }

        $album = Album::getAlbumId($req['id']);

        if (!$album->isNewRecord()) {

            $rDefault = Router::instance();
            $rUpdatePhoto = Router::byName('Album.putUpdate');

            $picture = $album->getPicture('1000x1000');
            $style = $picture ? 'background-image: url('.$picture.');' : '';

            $data = [
                'picture' => '',
                'album' => $album,
                'style' => $style,
                'rDefault' => $rDefault,
                'rUpdatePhoto' => $rUpdatePhoto,
            ];

            $this->content = $this->render(self::TEMPLATES . 'albumEdit.tpl', $data);

        }

    }

    public function putUpdateAlbum($req) {

        if ($this->permitions['editing-album'] != 'on') {

            Notify::push('Нет полномочий для изменеия.', 'err');
            $this->back();

        }

        $ar = Album::getAlbumId($req['id']);
        $ar->scenario('update');

        if ($ar->isNewRecord()) $this->back();

        $ar->bind($_POST['update']);

        if ($ar->save()) {

            Notify::push('Альбом успешно изменен.', 'ok');

            Journal::add(Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> изменил альбом "/'.$ar->chpu.'".',
                'Альбома');

        } else {

            Notify::push('Ошибка изменения альбома.', 'err');
            foreach($ar->getErrors() as $errors)
                Notify::pushArray($errors);

        }

        $this->back();

    }

    public function upViAlbum($req) {

        if ($this->permitions['editing-album'] != 'on') {

            Notify::push('Нет полномочий для изменеия.', 'err');
            $this->back();

        }

        $ar = Album::getAlbumId($req['id']);
        $ar->scenario('visibility');

        if ($ar->isNewRecord()) $this->back();

        $ar->visible = $ar->visible ? 0 : 1;

        if ($ar->save()) {

            Notify::push('Видимость альбома успешно изменена.', 'ok');

            Journal::add(Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> изменил видимость альбома "/'.$ar->chpu.'".',
                'Альбома');

        } else {

            Notify::push('Ошибка изменения видимости альбома.', 'err');
            foreach($ar->getErrors() as $errors)
                Notify::pushArray($errors);

        }

        $this->back();

    }

    public function deleteAlbum($req) {

        if ($this->permitions['deleting-album'] != 'on') {

            Notify::push('Нет полномочий для изменеия.', 'err');
            $this->back();

        }

        $ar = Album::getAlbumId($req['id']);

        if ($ar->delete()) {

            Notify::push('Альбом успешно удален.', 'ok');

            Journal::add(Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> удалил альбом "/'.$ar->chpu.'".',
                'Альбома');

        } else {

            Notify::push('Ошибка удаления альбома.', 'err');

        }

        NewSort::init($ar)->normalise();

        $this->back();

    }

    public function upSortAlbum($req) {

        if ($this->permitions['editing-album'] != 'on') {

            Notify::push('Нет полномочий для изменеия.', 'err');
            $this->back();

        }

        $ar = Album::getAlbumId($req['id']);

        NewSort::init($ar)
            ->move($_POST['sort'])
            ->normalise();

        Journal::add(Journal::STATUS_NOTICE,
            '<b>'.Users::get()->login.'</b> изменил порядковый номер альбома "/'.$ar->chpu.'".',
            'Альбома');

        $this->back();

    }

    public function renderAlbumsID($req) {

        $albums = Album::getAlbumsCid($req['id']);

        if (!$albums) {

            Notify::push('В альбоме нет альбомов.', 'err');

            $this->back();

        }

        $album = Album::getAlbumId($req['id']);
        $edit = Router::byName('Album.update');
        $sort = Router::byName('Album.updateSort');
        $delete = Router::byName('Album.delete');
        $visible = Router::byName('Album.updateVisible');
        $albumOP = Router::byName('Album.albumPhotos');
        $renderAID = Router::byName('Album.renderAlbumsID');

        $data = [
            'edit' => $edit,
            'sort' => $sort,
            'albums' => $albums,
            'album' => $album,
            'delete' => $delete,
            'visible' => $visible,
            'albumOP' => $albumOP,
            'renderAID' => $renderAID,
        ];

        $this->content = $this->render(self::TEMPLATES . 'albumInfo.tpl', $data);

    }

    public function getSettingsContent($name = '', Array $permitions) {

        return $this->render(self::TEMPLATES . 'settings.tpl', $permitions);

    }

}
