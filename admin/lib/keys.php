<?php
    /*
     * The MIT License
     *
     * Copyright 2016 Kyto.
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

    class keys
    {
        /**
         * Manage the Key Type
         * 1 = Private, 2 = Public
         * @var integer
         */
        public $key_type = 0;

        /**
         * Manage the Key Mode
         * 1 = Test, 2 = Live
         * @var integer
         */
        public $key_mode = 0;

        /**
         * Key name on the DB
         * @var string
         */
        public $key_name = '';

        /**
         * Key Data
         * @var string
         */
        public $key_data = '';

        /**
         * Status of the Current Key
         * @var integer
         */
        public $key_status = 0;

        /**
         * All keys will be inserted here
         * @var array
         */
        public $keys = array();

        /**
         * DataBase Object
         * @var object
         */
        public $db;
        public $error;

        public function __construct($database, $keyType = null, $keyMode = null,
                                    $keyName = null, $keyData = null,
                                    $keyData = null, $keyStatus = null)
        {
            $this->db = $database;
            $this->key_type = $keyType;
            $this->key_mode = $keyMode;
            $this->key_name = $keyName;
            $this->key_data = $keyData;
            $this->key_status = $keyStatus;
        }

        /**
         * Return all the active keys in an array with all the key_data table info
         * @return array
         */
        public function getKeys()
        {
            try {
                $this->keys = array();

                $query = 'SELECT * FROM '.DB_DATABASE.'.key_data k WHERE k.status = 1';
                Foreach ($this->db->query($query) as $row) {
                    $this->keys[] = $row;
                }
            } catch (Exception $ex) {
                $this->keys = array();
                $this->error = $ex->getMessage();
            }
        }

        /**
         * Return a number of the active keys
         * @return number
         */
        public function countEnabledKeys()
        {
            try {
                if (count($this->keys) && $count = count($this->keys) == 0) {
                    return $count;
                }
            } catch (Exception $e) {
                return 0;
            }
        }

        public function setEnabledKeys($id, $active = 0)
        {
            try {
                $query = 'UPDATE '.DB_DATABASE.'.key_data k SET k.status = :status WHERE k.id = :id ;';
                $dbo->db->prepare($query)->execute(array(':status' => $active, ':id' => $id));
                return true;
            } catch (Exception $ex) {
                return false;
            }
        }

        public static function createKey($key_name, $key_type, $key_mode,
                                         $key_data, $key_status)
        {
            try {
                $query = 'INSERT INTO '.DB_DATABASE.'.key_data (key_name, key_type, key_mode, key_data, status) VALUES(:name, :type, :mode, :data, :status)';
                $dbo->db->prepare($query)->execute(array(
                    ':name' => $key_name,
                    ':type' => $key_type,
                    ':mode' => $key_mode,
                    ':data' => $key_data,
                    ':status' => $key_status));
                return true;
            } catch (Exception $ex) {
                return $e->getMessage();
            }
        }

        public static function deleteKey($key_id)
        {
            try {
                $query = 'DELETE FROM '.DB_DATABASE.'.key_data WHERE id = :id';
                $dbo->db->prepare($query)->execute(array(':id' => $key_id));
                return true;
            } catch (Exception $ex) {
                return $e->getMessage();
            }
        }
    }    