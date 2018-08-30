<?php

/**
 * Gemeinsam genutzte Datenbankfunktionen
 *
 * @author: Marlon Böhland
 * @access: public
 * @date: 01.12.2016
 */
class Database extends PDO
{
    public function __construct()
    {
        //TODO: Globals verwenden
        parent::__construct('mysql:host=192.168.200.2;port=3307;dbname=usrdb_stokcgbl5;charset=utf8_general_ci', 'stokcgbl5', 'X$9?2IMalDUU');
    }
    /**
     * MySQL SELECT
     * @param $sql - SQL Statement
     * @param array $array - Parameter
     * @param int $fetchMode - Return Typ
     * @return array
     */
    public function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC)
    {
        $pdoStatement = $this->prepare($sql);
        foreach ($array as $key => $value) {
            $pdoStatement->bindValue("$key", $value);
        }

        $pdoStatement->execute();
        return $pdoStatement->fetchAll($fetchMode);
    }

    /**
     * MySQL INSERT
     * @param string $table Der Tabellenname in welche die Daten eingefügt werden sollen.
     * @param array $data Ein assoziatives Array.
     */
    public function insert($table, $data)
    {
        ksort($data);

        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $pdoStatement = $this->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");

        foreach ($data as $key => $value) {
            $pdoStatement->bindValue(":$key", $value);
        }
        $pdoStatement->execute();
    }

    /**
     * MySQL UPDATE
     * @param string $table Der Tabellenname in welche die Daten eingefügt werden sollen.
     * @param array $data Ein assoziatives Array.
     * @param string $where WHERE Bedingung
     */
    public function update($table, $data, $where)
    {
        ksort($data);

        $fieldDetails = NULL;
        foreach ($data as $key => $value) {
            $fieldDetails .= "`$key`=:$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        $sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");

        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $sth->execute();
    }

    /**
     * MySQL DELETE
     *
     * @param string $table
     * @param string $where
     * @param integer $limit
     * @return integer affected rows
     */
    public function delete($table, $where, $limit = 1)
    {
        return $this->exec("DELETE FROM $table WHERE $where LIMIT $limit");
    }
}