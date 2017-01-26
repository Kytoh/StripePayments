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
         * @var integer ID of the Selected Key
         */
        public $key_id = null;

        /**
         * Manage the Key Type
         * 1 = Private, 2 = Public
         * @var integer
         */
        public $key_type = null;

        /**
         * Manage the Key Mode
         * 1 = Test, 2 = Live
         * @var integer
         */
        public $key_mode = null;

        /**
         * Key name on the DB
         * @var string
         */
        public $key_name = null;

        /**
         * Key Data
         * @var string
         */
        public $key_data = null;

        /**
         * Status of the Current Key
         * @var integer
         */
        public $key_status = null;

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

        /**
         *
         * @param object $database
         * @param type $keyType
         * @param type $keyMode
         * @param type $keyName
         * @param type $keyData
         * @param type $keyStatus
         */
        public function __construct($database, $keyType = null, $keyMode = null,
                                    $keyName = null, $keyData = null,
                                    $keyStatus = null)
        {
            $this->db = $database;
            $this->key_type = $keyType;
            $this->key_mode = $keyMode;
            $this->key_name = $keyName;
            $this->key_data = $keyData;
            $this->key_status = $keyStatus;
        }

        /**
         * Declare a Key ID
         * @param object $database
         * @param integer $key_id
         * @overload
         */
        public static function __constructID($database, $key_id = null)
        {
            $obj = new Keys($database);
            $obj->key_id = $key_id;
            return $obj;
        }

        /**
         * Return all the active keys in an array with all the key_data table info
         * @return array
         */
        public function getKeys()
        {
            try {
                $this->keys = array();

                $query = 'SELECT * FROM '.DB_DATABASE.'.key_data k';
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

        /**
         *
         * @return boolean
         */
        public function resetKeys()
        {
            try {
                $query = 'UPDATE '.DB_DATABASE.'.key_data k SET k.status = 0 '
                    .'WHERE k.key_type = :type;';
                $this->db->prepare($query)
                    ->execute(array(':type' => $this->key_type));
            } catch (Exception $ex) {
                $this->error .= 'resetKeys::'.$ex->getMessage();
            }
        }

        public function setEnabledKeys()
        {
            try {
                /* TODO: #11
                 * if ($this->key_status == 1) {
                  $query = 'SELECT k.key_type FROM key_data k WHERE k.id = :t_id';
                  $result = $this->db->prepare($query)->execute(array(':t_id' => $this->key_id));
                  //$row = $result->fetch();
                  //$this->key_type = $row[0];
                  //self::resetKeys();
                  } */
                $query = 'UPDATE '.DB_DATABASE.'.key_data k SET k.status = :status '
                    .'WHERE k.id = :id;';
                $this->db->prepare($query)
                    ->execute(array(':status' => $this->key_status, ':id' => $this->key_id));
                return true;
            } catch (Exception $ex) {
                $this->error .= 'setEnabledKeys::'.$ex->getMessage();
            }
        }

        public function createKey()
        {
            try {
                if ($this->key_status == 1) {
                    self::setEnabledKeys();
                }
                $query = 'INSERT INTO '.DB_DATABASE.'.key_data (key_name, key_type, key_mode, key_data, status) VALUES(:name, :type, :mode, :data, :status)';
                $this->db->prepare($query)->execute(array(
                    ':name' => $this->key_name,
                    ':type' => $this->key_type,
                    ':mode' => $this->key_mode,
                    ':data' => $this->key_data,
                    ':status' => $this->key_status));
                return true;
            } catch (Exception $ex) {
                $this->error .= 'createKey::'.$ex->getMessage();
                return false;
            }
        }

        public function deleteKey()
        {
            try {
                $query = 'DELETE FROM '.DB_DATABASE.'.key_data WHERE id = :id';
                $this->db->prepare($query)->execute(array(':id' => $this->key_id));
                return true;
            } catch (Exception $ex) {
                $this->error .= 'deleteKey::'.$ex->getMessage();
                return false;
            }
        }
    }    