<?php

    namespace Back\Files;

    class Config {

        const DATA_PATH = _SHARED_DATA;

        const PREFIX_PATH = '/data';

        const SMALL_RESOLUTION = '50x50';
        const BIG_RESOLUTION = '1000x1000';

        const ARCHIVE = [
            'application/zip',
            'application/x-rar',
        ];

        const VIDEO = [
            'video/mp4',
            'video/mpeg',
            'video/ogg',
            'video/quicktime',
            'video/webm',
            'video/x-ms-wmv',
            'video/x-flv',
        ];

        const IMAGE = [
            'image/png',
            'image/jpeg',
            'image/pjpeg',
            'image/bmp',
            'image/gif',
        ];

        const VECTOR_IMAGE = [
            'image/svg+xml',
        ];

        const DOC = [
            'text/plain',
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/msword',
            'application/vnd.ms-excel',
            'application/vnd.ms-office',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.oasis.opendocument.spreadsheet',
            'application/vnd.oasis.opendocument.text',
            'application/vnd.oasis.opendocument.presentation',
            'application/rtf',
            'application/x-rtf',
            'text/richtext',
            'text/rtf',
            'image/vnd.djvu',
        ];

        const EXTENSIONS = [
            'txt',
            'pdf',
            'djvu',
            'djv',
            'zip',
            'rar',
            'docx',
            'xlsx',
            'doc',
            'xls',
            'ppt',
            'pptx',
            'ods',
            'odt',
            'rtf',
            'odp',
            'png',
            'jpg',
            'jpeg',
            'bmp',
            'gif',
            'mp4',
            'mpeg',
            'ogg',
            'webm',
            'wmv',
            'flv',
            'svg',
        ];

        const PICTURE_NAME = ':id-:resolution.:ext';

        public static function getMimeTypes(){

            return array_merge(
                self::ARCHIVE,
                self::IMAGE,
                self::VECTOR_IMAGE,
                self::DOC,
                self::VIDEO
            );

        }

    }
