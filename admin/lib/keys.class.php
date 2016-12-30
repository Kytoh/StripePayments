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
/**
 * 
 * @row KeyType 1 = Private, 2 = Public
 * @row KeyMode 1 = Test, 2 = Live
 */
class keys {
    
    
    /**
     * Return all the active keys in an array with all the key_data table info
     * @return array
     */
    public static function getKeys()
    {
        try {
            $query = 'SELECT * FROM '.DB_DATABASE.'.key_data k';
            Foreach ($dbp->query($query) as $row){
                $result[] = $row;
            }
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
    
    public static function setEnabledKeys($id, $active = 0)
    {
        try{
            $query = 'UPDATE '.DB_DATABASE.'.key_data k SET k.status = :status WHERE k.id = :id ;';
            $dbp->prepare($query)->execute(array(':status' => $active, ':id' => $id));
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public static function setDisableKey($id)
    {
        self::setEnabledKeys($id, 0);
    }

    public static function setEnableKey($id)
    {
        self::setEnabledKeys($id, 1);
    }

    public static function createKey($dbp, $key_name, $key_type, $key_mode, $key_data, $key_status)
    {
        try {
            $query = 'INSERT INTO '.DB_DATABASE.'.key_data (key_name, key_type, key_mode, key_data, status) VALUES(:name, :type, :mode, :data, :status)';
            $dbp->prepare($query)->execute(array(
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

    public static function deleteKey($dbp, $key_id)
    {
        try {
            $query = 'DELETE FROM '.DB_DATABASE.'.key_data WHERE id = :id';
            $dbp->prepare($query)->execute(array(':id' => $key_id));
            return true;
        } catch (Exception $ex) {
            return $e->getMessage();
        }
    }
}