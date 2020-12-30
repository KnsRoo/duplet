<?php

namespace Back\Gallery\Actions;

use Model\FileMan\File;
use Model\FileMan\Folder;
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

class PhotoActions extends Response {

    const TEMPLATES = __DIR__ . '/../temp/';
    const ITEMS_ON_PAGE = 16;

    public $permitions = [];
    protected $updatePhoto;

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

        $group->addGet('/', [$this, 'getContent'])
            ->setName('Gallery.getContent');

        $group->addGet('/render-photo', [$this, 'addPhoto'])
            ->setName('Gallery.addPhoto');

        $group->addPost('/create-photo', [$this, 'createPhoto'])
            ->setName('Gallery.createPhoto');

        $group->addPost('/create-photos', [$this, 'createPhotos'])
            ->setName('Gallery.createPhotos');

        $group->addGet('/update-photo-:id', [$this, 'updatePhoto'])
            ->setName('Photo.update');

        $group->addPut('/update-photo-:id', [$this, 'putUpPhoto']);

        $group->addPut('/update-visibility-photo-:id', [$this, 'upViPhoto'])
            ->setName('Gallery.updateVisible');

        $group->addDelete('/delete-photo-:id', [$this, 'deletePhoto'])
            ->setName('Photo.delete');

        $group->addPut('/update-sort-photo-:id', [$this, 'upSortPhoto'])
            ->setName('Photo.updateSort');

        return $group;

    }

    public function parseRequest($req, $next) {

        $page = &$_REQUEST['page'];
        $this->page = $page ?: 1;
        $next();

    }

    public function getContent($req) {

        $photos = Photo::getAllPhotos();

        $route = Router::byName('Gallery.getContent');
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
        $sort = Router::byName('Photo.updateSort');
        $delete = Router::byName('Photo.delete');
        $visible = Router::byName('Gallery.updateVisible');
        $albumOP = Router::byName('Album.albumPhotos');

        $data = [
            'edit' => $edit,
            'sort' => $sort,
            'pager' => $pager,
            'photos' => $photos,
            'delete' => $delete,
            'visible' => $visible,
            'albumOP' => $albumOP,
        ];

        Menu::setContent('photos');

        $this->content = $this->render(self::TEMPLATES . 'photo.tpl', $data);

    }

    public function addPhoto() {

        if ($this->permitions['creating-photo'] != 'on') {

            Notify::push('Нет полномочий для изменеия.', 'err');
            $this->back();

        }

        $albums = Album::getAllAlbums();

        $rCreatePhoto = Router::byName('Gallery.createPhoto');
        $rCreatePhotos = Router::byName('Gallery.createPhotos');
        $rDefault = Router::instance();

        $data = [
            'id' => uniqid(),
            'albums' => $albums,
            'rDefault' => $rDefault,
            'rCreatePhoto' => $rCreatePhoto,
            'rCreatePhotos' => $rCreatePhotos,
        ];

        Menu::setContent('createPhotos');

        $this->content = $this->render(self::TEMPLATES . 'photoNew.tpl', $data);

    }

    public function createPhoto() {

        if ($this->permitions['creating-photo'] != 'on') {

            Notify::push('Нет полномочий для изменеия.', 'err');
            $this->back();
        }

        $ar = new Photo('create');
        $ar->bind($_POST['create']);

        $ar->id = md5(uniqid());
        $ar->title = $ar->title ?: 'Новое фото';
        $ar->hash = md5($ar->title);
        Chpu::inject($ar);

        if ($ar->save()){

            Notify::push('Фотография успешно добавленна.', 'ok'); 
            NewSort::init($ar)->normalise();

            Journal::add(Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> создал фото "/'.$ar->chpu.'".',
                'Фото');

        } else {

            Notify::push('Ошибка добавления фотографии.', 'err');
            foreach($ar->getErrors() as $errors)
                Notify::pushArray($errors);

        }

        $this->back();

    }

    public function createPhotos() {

        if ($this->permitions['creating-photo'] != 'on') {

            Notify::push('Нет полномочий для изменеия.', 'err');
            $this->back();

        }

        $post = $_POST;
        $folder = new Folder;

        foreach ($_FILES['files']['name'] as $key => $name) {

            $file = $folder->createFile($_FILES['files']['tmp_name'][$key], $name);

            if ($file) {

                $this->photoCreate($file->id, $post);

                Journal::add(Journal::STATUS_NOTICE,
                    '<b>'.Users::get()->login.'</b> загрузил файл "'.$file->title.'".',
                    'Файлы');

            } else {

                foreach($folder->getErrors() as $error)
                    Notify::pushArray($error);

            }

        }

        $this->back();
    }

    public function photoCreate($id, $post) {

        $ar = new Photo('create');
        $ar->id = md5(uniqid());
        $ar->cid = $post['cid'];
        $ar->picture = $id;
        $ar->hash = md5($ar->title);
        Chpu::inject($ar, ['cid' => 'category']);

        if ($ar->save()) {

            NewSort::init($ar)->normalise();
            Journal::add(Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> создал фото "/'.$ar->chpu.'".',
                'Фото');

        } else {

            Notify::push('Ошибка добавления фотографии.', 'err');
            foreach($ar->getErrors() as $errors)
                Notify::pushArray($errors);

        }

    }

    public function updatePhoto($req) {

        if ($this->permitions['editing-photo'] != 'on') {

            Notify::push('Нет полномочий для изменеия.', 'err');
            $this->back();

        }

        $photo = Photo::getPhotoId($req['id']);
        $album = Album::getAlbumId($photo->cid);
        $albums = Album::getAllAlbums();

        if (!$photo->isNewRecord()) {

            $rDefault = Router::instance();
            $rUpdatePhoto = Router::byName('Photo.update');

            $picture = $photo->getPicture('1030x706');
            $style = $picture ?  'background-image: url('.$picture.');' : '';

            $data = [
                'picture' => '',
                'photo' => $photo,
                'album' => $album,
                'style' => $style,
                'albums' => $albums,
                'rDefault' => $rDefault,
                'rUpdatePhoto' => $rUpdatePhoto,
            ];

            $this->content = $this->render(self::TEMPLATES . 'photoEdit.tpl', $data);

        }

    }

    public function putUpPhoto($req) {

        if ($this->permitions['editing-photo'] != 'on') {

            Notify::push('Нет полномочий для изменеия.', 'err');
            $this->back();

        }

        $ar = Photo::getPhotoId($req['id']);
        $ar->scenario('update');

        if ($ar->isNewRecord()) $this->back();

        $ar->bind($_POST['update']);

        if ($ar->save()) {

            Notify::push('Фотография успешно изменена.', 'ok');

            Journal::add(Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> изменил фото "/'.$ar->chpu.'".',
                'Фото');

        } else {

            Notify::push('Ошибка изменения фотографии.', 'err');
            foreach($ar->getErrors() as $errors)
                Notify::pushArray($errors);

        }

        $this->back();

    }

    public function upViPhoto($req) {

        if ($this->permitions['editing-photo'] != 'on') {

            Notify::push('Нет полномочий для изменеия.', 'err');
            $this->back();

        }

        $ar = Photo::getPhotoId($req['id']);
        $ar->scenario('visibility');

        if ($ar->isNewRecord()) $this->back();

        $ar->visible = $ar->visible ? 0 : 1;

        if ($ar->save()) {

            Notify::push('Видимость фотографии успешно изменена.', 'ok');

            Journal::add(Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> изменил видимость фото "/'.$ar->chpu.'".',
                'Фото');
        } else {

            Notify::push('Ошибка изменения видимости фотографии.', 'err');
            foreach($ar->getErrors() as $errors)
                Notify::pushArray($errors);

        }

        $this->back();

    }

    public function deletePhoto($req)
    {
        if ($this->permitions['deleting-photo'] != 'on') {

            Notify::push('Нет полномочий для изменеия.', 'err');
            $this->back();

        }

        $check = isset($_POST['checkbox-test']) ? $_POST['checkbox-test'] : false;

        $photo = Photo::find(['id' => $req['id']])->get();

        if ($photo->delete()) {

            if ($check === 'check-photo') {

                $this->fileDelete($photo->picture);
            }

            Notify::push('Фотография успешно удалена.', 'ok');

            Journal::add(Journal::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> удалил фото "/'.$photo->chpu.'".',
                'Фото');

        } else {
            Notify::push('Ошибка удаления фотографии.', 'err');
        }

        NewSort::init($photo)->normalise();

        $this->back();
    }

    public function fileDelete($id) {

        $file = File::find(['id' => $id])->get();

        if (!$file->isNew()) {

            $result = $file->delete();

            if ($result) {

                Notify::push('Файл успешно удален.', 'ok');
                Journal::add(Journal::STATUS_NOTICE,
                    '<b>'.Users::get()->login.'</b> удалил файл "'.$file->title.'".',
                    'Файлы');

            } else Notify::push('Ошибка удаления файла.', 'err');

        }

    }

    public function upSortPhoto($req) {

        if ($this->permitions['editing-photo'] != 'on') {

            Notify::push('Нет полномочий для изменеия.', 'err');
            $this->back();

        }

        $ar = Photo::getPhotoId($req['id']);

        NewSort::init($ar)
            ->move($_POST['sort'])
            ->normalise();

        Journal::add(Journal::STATUS_NOTICE,
            '<b>'.Users::get()->login.'</b> изменил порядковый номера фото "/'.$ar->chpu.'".',
            'Фото');

        $this->back();

    }

}
