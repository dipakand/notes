<?php
class DB
{
    //    $host = 'localhost';
    //    $user = 'root';
    //    $password = 'root';
    //    $database = 'pvc';

    public $connection;

    public function __construct()
    {
        $this->db_connect();
    }

    public function db_connect()
    {
        include 'config.php';
        $this->connection = mysqli_connect("$host","$user","$password","$database");
        if(mysqli_connect_error())
        {
            die(" Connect Failed ");
        }
    }

    /* public function check($a)
    {
        $return = mysqli_real_escape_string($this->connection,$a);
        return $return;
    }*/

    function save($table, $fields, $condition = '')
    {
        $sql = "INSERT INTO $table SET ";
        if($condition != '')
            $sql = "UPDATE $table SET ";
        //print_r($fields);exit;

        $table_fields = $this->get_table_fields($table);

        foreach($fields as $field=>$value)
        {
            if(in_array($field,$table_fields))
                $sql .= "$field = '".addslashes($value)."', ";
        }

        $sql = substr($sql, 0 ,-2);

        if($condition != '')
            $sql .= " WHERE $condition";
        //print_r($sql);exit;
        $result = mysqli_query($this->connection, $sql);

        if(mysqli_affected_rows())
            return true;
        else
            return false;

    }

    function delete($table, $condition)
    {
        $sql = "DELETE FROM $table WHERE $condition";
        //print_r($sql);exit;
        $result = mysqli_query($this->connection, $sql);
        if(mysqli_affected_rows())
            return true;
        else
            return false;
    }

    // table, fields, condition, order, limit
    function select($table, $fields = array(), $condition = '',$order = '',$limit ='')
    {
        $data = array();
        $sql = "SELECT ";

        if(is_array($fields) && count($fields) > 0)
        {	
            $sql .= implode(", ",$fields);		
        }
        else
        {
            $sql .= "*";
        }

        $sql .= " FROM $table";

        if($condition != '')
            $sql .= " WHERE $condition";

        if($order != '')
            $sql .= " ORDER BY $order";

        if($limit != '')
            $sql .= " LIMIT $limit"; 	
        //print_r($sql);
        $result = mysqli_query($this->connection, $sql);

        while($row = mysqli_fetch_assoc($result))//,MYSQLI_ASSOC
        {
            $data[] = $row;
        }
        //echo $sql; echo nl2br("\n");
        //print_r($data);
        //return $data;
        return json_encode($data);
    }

    function get_table_fields($table)
    {
        $fields = array();
        $result = mysqli_query($this->connection, "SHOW COLUMNS FROM $table");
        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
        {
            $fields[] = $row['Field'];
        }

        return $fields;
    }
}
?>
<?php
error_reporting(0);
session_start();
ob_start();
include("../include/db.php");

$db = new DB;
$cond = 'reg_id = 6' ;

// table, fields, condition, order, limit
$allusers = $db->select('tbl_reg','','','','');

//$fiesd = $db->get_table_fields('tbl_reg');
print_r($allusers);
?>