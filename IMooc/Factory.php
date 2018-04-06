<?php
namespace IMooc;

class Factory
{
    static $proxy = null;
    /**
     * @param $id
     * @return User
     */
    static function getUser($id)
    {
        $key = 'user_'.$id;
        $user = Register::get($key);
        if (!$user) {
            $user = new User($id);
            Register::set($key, $user);
        }
        return $user;
    }

    /**
     * @param $name
     * @return bool
     */
    static function getModel($name)
    {
        $key = 'app_model_'.$name;
        $model = Register::get($key);
        if (!$model) {
            $class = '\\App\\Model\\'.ucwords($name);
            $model = new $class;
            Register::set($key, $model);
        }
        return $model;
    }

    static function getDatabase($id = 'proxy')
    {
        if ($id == 'proxy')
        {
            if (!self::$proxy)
            {
                self::$proxy = new \IMooc\Database\Proxy;
            }
            return self::$proxy;
        }

        $key = 'database_'.$id;
        if ($id == 'slave')
        {
            $slaves = Application::getInstance()->config['database']['slave'];
            $db_conf = $slaves[array_rand($slaves)];
        }
        else
        {
            $db_conf = Application::getInstance()->config['database'][$id];
        }
        $db = Register::get($key);
        if (!$db) {
            $db = new Database\MySQLi();
            $db->connect($db_conf['host'], $db_conf['user'], $db_conf['password'], $db_conf['dbname']);
            Register::set($key, $db);
        }
        return $db;
    }
}