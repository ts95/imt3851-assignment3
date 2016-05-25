<?php

namespace Tools;

/**
 * Subclass of PDO that generates the database
 * automatically if it doesn't already exist.
 */
class CustomPDO extends \PDO {

    public function __construct() {
        $settings = json_decode(file_get_contents(__DIR__ . '/../database.json'));

        $dbname = $settings->name;
        $dbport = $settings->port;

        parent::__construct("mysql:port=$dbport", $settings->username, $settings->password);
        parent::setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        parent::setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);

        $query = "SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'";
        $dbExists = (bool) parent::query($query)->fetchColumn();

        if (!$dbExists) {
            parent::query('CREATE DATABASE ' . $dbname);
            parent::query('USE ' . $dbname);

            $createSQL = file_get_contents(__DIR__ . '/../sql/create.sql');
            parent::exec($createSQL);

            $seedSQL = file_get_contents(__DIR__ . '/../sql/seed.sql');
            parent::exec($seedSQL);
        } else {
            parent::query('USE ' . $dbname);
        }
    }
}
