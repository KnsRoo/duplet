<?php

    namespace Core;

    class Users{

        public static $_USERS = [];
        private static $_USER;

        public static function init() {

            self::$_USERS = self::initUsers();

        }

        public static function initUsers() {

            $result = [];
            $files = glob(_PL . '/*.php');

            foreach ($files as &$file) {

                $user = include $file;
                $result[$user->login] = $user;

            }

            return $result;

        }

        public static function getUsers() {

            return self::$_USERS;

        }

        public static function exists($login = '') {

            return isset(self::$_USERS[$login]);

        }

        public static function deleteUser($login) {

            $file = _PL.'/'.$login.'.php';

            if (file_exists($file)) return unlink($file);

            else throw new \Exception('user not found.');

        }

        public static function set(\Core\User $user) {

            self::$_USER = $user;

        }

        public static function get() {

            return self::$_USER;

        }

    }
