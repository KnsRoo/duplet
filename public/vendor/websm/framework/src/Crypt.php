<?php

namespace Websm\Framework;

class Crypt {

    private $key;
    private $cipher = 'aes256';
    private $ciphers = [];

    /**
     * getAviableCiphers 
     * 
     * @static
     * @access public
     * @return Array
     */
    public static function getAviableCiphers() {

        return openssl_get_cipher_methods(true);

    }

    /**
     * __callStatic 
     * 
     * @param mixed $name Имя вызываемого метода.
     * @param mixed $arguments Массив аргументов.
     * @static
     * @access public
     * @return Mixed
     */
    public static function __callStatic($name, $arguments = []) {

        $method = $name.'Static';

        if(method_exists(__CLASS__, $method))
            return self::$method(...$arguments);

        else throw new Exceptions\BaseException('Method "'.$name.'" not found.');

    }

    /**
     * encryptStatic
     *
     * Статичекий метод для шифрования данных.
     * 
     * @param mixed $data Данные которые нужно зашифровать.
     * @param mixed $key Ключ шифрования.
     * @param mixed $cipher Алгоритм шифрования.
     * @static
     * @access public
     * @return String
     * @throws BaseException если $cipher указан не верно.
     *
     * @code
     *
     * $data = Crypt::encrypt('test string', 'test key');
     *
     * @endcode
     */
    public static function encryptStatic($data, $key, $cipher = 'aes256') {

        $ciphers = self::getAviableCiphers();

        if(!in_array($cipher, $ciphers))
            throw new Exceptions\BaseException('Cipher "'.$cipher.'" not found.');

        $len = openssl_cipher_iv_length($cipher);
        $iv = mb_substr(md5($key), 0, $len, 'UTF-8');
        return openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);

    }

    /**
     * decryptStatic 
     *
     * Статичекий метод для расшифровки данных.
     * 
     * @param mixed $data Данные которые нужно расшифровать.
     * @param mixed $key Ключ шифрования.
     * @param mixed $cipher Алгоритм шифрования.
     * @static
     * @access public
     * @return String
     * @throws BaseException если $cipher указан не верно.
     *
     * @code
     *
     * $data = Crypt::encrypt('test string', 'test key');
     * $data = Crypt::decrypt($data, 'test key');
     *
     * @endcode
     */
    public static function decryptStatic($data, $key, $cipher = 'aes256') {

        $ciphers = self::getAviableCiphers();

        if(!in_array($cipher, $ciphers))
            throw new Exceptions\BaseException('Cipher "'.$cipher.'" not found.');

        $len = openssl_cipher_iv_length($cipher);
        $iv = mb_substr(md5($key), 0, $len, 'UTF-8');
        return openssl_decrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);

    }

    /**
     * __construct 
     * 
     * @param mixed $key Ключ шифрования.
     * @param string $cipher Алгоритм шифрования.
     * @access public
     * @return void
     *
     * @code
     *
     * $crypt = new Crypt('test key');
     *
     * @endcode
     */
    public function __construct($key = null, $cipher = 'aes256') {

        $this->ciphers = self::getAviableCiphers();
        if ($key) $this->setKey($key);
        if ($cipher) $this->setCiper($cipher);

    }

    /**
     * setKey 
     *
     * Устанавливает ключ шифрования.
     * 
     * @param mixed $key Ключ шифрования.
     * @access public
     * @return Crypt
     *
     * @code
     *
     * $crypt = new Crypt;
     * $crypt->setKey('test key');
     * 
     * @endcode
     */
    public function setKey($key) {

        $this->key = $key;
        return $this;

    }

    /**
     * setCiper 
     *
     * Устанавливает метод шифрования.
     * 
     * @param mixed $cipher 
     * @access public
     * @return Crypt
     *
     * @code
     *
     * $crypt = new Crypt;
     * $crypt->setCiper('aes256');
     * 
     * @endcode
     */
    public function setCiper($cipher) {

        if(!in_array($cipher, $this->ciphers))
            throw new Exceptions\BaseException('Cipher "'.$cipher.'" not found.');

        $this->cipher = $cipher;
        return $this;

    }

    /**
     * encrypt 
     *
     * Производит шифрование даных.
     * 
     * @param mixed $data Данные которые требуется зашифровать.
     * @access public
     * @return String
     * @throws BaseException если ключ шифрования пуст.
     *
     * @code
     *
     * $crypt = new Crypt('test key');
     * $data = $crypt->encrypt('test string');
     *
     * @endcode
     */
    public function encrypt($data) {

        if (!$this->key) throw new Exceptions\BaseException('Key is empty.');

        $len = openssl_cipher_iv_length($this->cipher);
        $iv = mb_substr(md5($this->key), 0, $len, 'UTF-8');
        return openssl_encrypt($data, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);

    }

    /**
     * decrypt 
     *
     * Выполняет расшифровку данных.
     * 
     * @param mixed $data 
     * @access public
     * @return String
     * @throws BaseException если ключ шифрования пуст.
     *
     * @code
     *
     * $crypt = new Crypt('test key');
     * $data = $crypt->encrypt('test string');
     *
     * $data = $crypt->decrypt($data); // test string
     *
     * @endcode
     */
    public function decrypt($data) {

        if (!$this->key) throw new Exceptions\BaseException('Key is empty.');

        $len = openssl_cipher_iv_length($this->cipher);
        $iv = mb_substr(md5($this->key), 0, $len, 'UTF-8');
        return openssl_decrypt($data, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);

    }

}
