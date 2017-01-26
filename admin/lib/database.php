<?php
    /*
     * The MIT License
     *
     * Copyright 2017 kyto.
     *
     * Permission is hereby granted, free of charge, to any person obtaining a copy
     * of this software and associated documentation files (the "Software"), to deal
     * in the Software without restriction, including without limitation the rights
     * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
     * copies of the Software, and to permit persons to whom the Software is
     * furnished to do so, subject to the following conditions:
     *
     * The above copyright notice and this permission notice shall be included in
     * all copies or substantial portions of the Software.
     *
     * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
     * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
     * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
     * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
     * THE SOFTWARE.
     */

    /**
     * Conection to Database Class
     */
    class Database
    {
        /**
         * @var object Objeto de acceso a la Base de Datos
         */
        public $db;
        private $host = DB_HOST;
        private $user = DB_USER;
        private $pass = DB_PASSWORD;
        private $dbname = DB_DATABASE;
        private $stmt;
        public $AuthConfig;
        public $AuthClass;

        public function __construct()
        {
            try {
                $dsn = 'mysql:host='.$this->host.';dbname='.$this->dbname;
                $options = array(
                    PDO::ATTR_PERSISTENT => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                );
                $this->db = new PDO($dsn, $this->user, $this->pass, $options);
                //
            } catch (Exception $ex) {
                $this->error = 'DataBase::'.$ex->getMessage();
            }
            self::deployAuth();
        }

        public function deployAuth()
        {
            $this->AuthConfig = new PHPAuth\Config($this->db);
            $this->AuthClass = new PHPAuth\Auth($this->db, $this->AuthConfig);
        }

        public function query($query)
        {
            $this->stmt = $this->db->prepare($query);
        }

        public function bind($param, $value, $type = null)
        {
            if (is_null($type)) {
                switch (true) {
                    case is_int($value):
                        $type = PDO::PARAM_INT;
                        break;
                    case is_bool($value):
                        $type = PDO::PARAM_BOOL;
                        break;
                    case is_null($value):
                        $type = PDO::PARAM_NULL;
                        break;
                    default:
                        $type = PDO::PARAM_STR;
                }
            }
            $this->stmt->bindValue($param, $value, $type);
        }

        public function execute()
        {
            return $this->stmt->execute();
        }

        public function resultset()
        {
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function single()
        {
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function rowCount()
        {
            return $this->stmt->rowCount();
        }

        public function lastInsertId()
        {
            return $this->dbh->lastInsertId();
        }

        public function beginTransaction()
        {
            return $this->dbh->beginTransaction();
        }

        public function endTransaction()
        {
            return $this->dbh->commit();
        }

        public function cancelTransaction()
        {
            return $this->dbh->rollBack();
        }

        public function debugDumpParams()
        {
            return $this->stmt->debugDumpParams();
        }
    }    