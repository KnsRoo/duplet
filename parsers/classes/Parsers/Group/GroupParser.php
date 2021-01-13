<?php

namespace Parsers\Group;

use SimpleXmlElement;

use Parsers\ProgressBar;

use Websm\Framework\Chpu;
use Websm\Framework\Sort;

use Model\Catalog\Group;
use Model\Catalog\Structure;

use Exceptions\FileNotFoundException;
use Exceptions\InvalidFileException;
use Exceptions\MainException;

use Parsers\Connection;

class GroupParser
{
    private $xml;
    private $errors = [];

    private const ALLOWED_MIME_TYPE = 'text/xml';

    public function __construct($src)
    {
        try {
            $this->checkFile($src);

            $file = file_get_contents($src);
            $this->xml = new SimpleXMLElement($file);
        } catch (FileNotFoundException $e) {
            $e->printError();
        } catch (InvalidFileException $e) {
            $e->printError();
        } catch (\Exception $e) {
            print_r("Error:\n");
            print_r($e->getMessage()."\n");
            print_r("Trace:\n");
            print_r($e->getTraceAsString());
        }
        $connection = new Connection();
        $this->pdo = $connection->getPdo();
    }

    private function checkFile(string $path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException(
                'File on path "'.$path.'" not found'
            );
        }

        $fileMimeType = mime_content_type($path);
        if ($fileMimeType !== self::ALLOWED_MIME_TYPE) {
            throw new InvalidFileException(
                'Invalid file mime type. Found "'
                .$fileMimeType
                .'" when "text/xml" expected'
            );
        }
    }

    private function getXmlGroups()
    {
        return $this->xml
            ->Товары
            ->Товар;
    }

    private function getData(SimpleXMLElement $xmlGroup)
    {
        $rawId = $xmlGroup->Ид->__toString();
        $groupId = md5($rawId);
        $groupCid = $xmlGroup->Группы->Ид->__toString();
        $groupCid = ($groupCid == '00000000-0000-0000-0000-000000000000') ? NULL : md5($groupCid);
        $name = $xmlGroup->Наименование->__toString();
        $rawImage = $xmlGroup->Картинка->__toString();
        $image = $rawImage ? md5(
            str_replace('import_files/', '', $rawImage)
        ) : null;
        $xmlSubGroups = $xmlGroup->Группы->Группа;

        return [
            'groupId' => $groupId,
            'name' => $name,
            'image' => $image,
            'groupCid' => $groupCid,
        ];
    }

    private function parseOne(SimpleXMLElement $xmlGroup, $cid = null)
    {
        $data = $this->getData($xmlGroup);

        $group = Group::find([ 'id' => $data['groupId'] ])
            ->get();
        $group->scenario('update');

        if ($group->isNew()) {
            $group->scenario('create');
            $group->id = $data['groupId'];
        }

        $group->cid = $data['groupCid'];
        $group->title = $data['name'];
        $group->hash = md5($group->id);
        $group->picture = $data['image'];

        Chpu::inject($group);
        Sort::init($group)
            ->normalise();

        if (!$group->save()) {
            $this->errors[$group->id] = [
                'title' => $group->title,
                'error' => [
                    'message' => 'Unable to save group',
                    'ModelLog' => $group->getErrors(),
                ],
            ];
            return false;
        }

        // foreach ($data['xmlSubGroups'] as $xmlSubGroup) {
        //     $this->parseOne($xmlSubGroup, $group->id);
        // }
    }

    private function parseOneS(SimpleXMLElement $xmlGroup, $cid = null)
    {
        $data = $this->getData($xmlGroup);

        $group = Structure::find([ 'id' => $data['groupId'] ])
            ->get();
        $group->scenario('update');

        if ($group->isNew()) {
            $group->scenario('create');
            $group->id = $data['groupId'];
        }

        $group->cid = $data['groupCid'];

        if (!$group->save()) {
            $this->errors[$group->id] = [
                'title' => $group->id,
                'error' => [
                    'message' => 'Unable to save group',
                    'ModelLog' => $group->getErrors(),
                ],
            ];
            return false;
        }

        // foreach ($data['xmlSubGroups'] as $xmlSubGroup) {
        //     $this->parseOne($xmlSubGroup, $group->id);
        // }
    }



    public function parseStructure(){
        $xmlGroups = $this->getXmlGroups();
        var_dump($xmlGroups);
        $progressTotal = count($xmlGroups);
        $progressBar = new ProgressBar($progressTotal, 'Groups parser');
        foreach ($xmlGroups as $xmlGroup) {
            $this->parseOneS($xmlGroup);
            $progressBar->makeStep();
        }
        $progressBar->close($this->errors);
    }

    public function parse(){
        $xmlGroups = $this->getXmlGroups();
        $progressTotal = count($xmlGroups);
        $progressBar = new ProgressBar($progressTotal, 'Groups parser');
        foreach ($xmlGroups as $xmlGroup) {
            $this->parseOne($xmlGroup);
            $progressBar->makeStep();
        }
        $progressBar->close($this->errors);
    }
}
