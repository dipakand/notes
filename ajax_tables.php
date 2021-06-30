<?php
error_reporting(0);
session_start();

$conn = mysqli_connect('localhost', 'root', '','testing');

if(isset($_POST['update']) && $_POST['show'] == 1)
{   
    $table_name=$_POST['table_name'];
    $field=$_POST['field'];
    mysqli_query($conn,"update `$table_name` set `".$_POST["column"]."`='".$_POST["editval"]."' WHERE  `$field`='".$_POST["id"]."'");
}

if((isset($_POST['table_name']) && $_POST['show'] == 1))
{
    $table_name=$_POST['table_name'];
    $db_name=$_POST['db_name'];

    $show=$_POST['show'];
    $sql = "SHOW COLUMNS FROM ".$db_name.".`$table_name`";
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)>0)
    {
        $fields = array();
        while($row = mysqli_fetch_array($result)){
            $fields[]=$row['Field'];
        }
?>
<div class="alert alert-success text-center"><?php echo strtoupper($table_name);?></div>
<div class="table-responsive">
    <table class="table table-bordered" id="example" width="100%">
        <thead>
            <tr>
                <?php
        foreach($fields as $field)
        {
            echo "<th>".$field."</th>";
        }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php 
        $table_result = mysqli_query($conn,"SELECT * FROM ".$db_name.".`$table_name`");
        while($row_table = mysqli_fetch_array($table_result))
        {
            ?>
            <tr>
                <?php
            foreach($fields as $key => $field)
            {
                ?>
                <td onBlur="saveToDatabase(this,'<?php echo $field; ?>','<?php echo $row_table[$fields[0]]; ?>','<?php echo $table_name; ?>','<?php echo $show; ?>','<?php echo $fields[0]; ?>')" ondblClick='showEdit(this);'><?php echo $row_table[$field];?></td>
                <?php
            }
                ?>
            </tr>
            <?php 
        }
            ?>
        </tbody>
    </table>
</div>
<script> 
    $(document).ready(function() {
        var table = $('#example').DataTable( {
            lengthChange: true,
            "lengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
            pageLength : 5,
            buttons: [
                {
                    extend: 'excel',className: 'btn-primary',
                    exportOptions: {
                    },
                },
                //                {
                //                    extend: 'pdf',className: 'btn-primary',orientation:'landscape',
                //                    exportOptions: {
                //                    },
                //                },
                //                {
                //                    extend: 'print',className: 'btn-primary',orientation:'landscape',
                //                    exportOptions: {
                //                    }
                //                },
            ],
        } );
        table.buttons().container()
            .appendTo( '#example_wrapper .col-md-6:eq(0)' );
    } );
</script>
<?php
    }
    else
    {
?>
<div class="alert alert-success text-center"><?php echo strtoupper($table_name);?> Not Found.!!!</div>
<?php
    }
}

if(isset($_POST['select']) && $_POST['show'] == 7)
{
    $table_name=$_POST['stablename'];
    $where=$_POST['swhere'];
    if(!empty($where))
    {
        $select_query="select * from `$table_name` where ".$where;
    }
    else
    {
        $select_query="select * from `$table_name`";
    }
    $excut=mysqli_query($conn,$select_query);
    if($excut == true)
    {
        $s=1;
        while($result_fetch=mysqli_fetch_assoc($excut))
        {
            echo '<span style="color:green;text-decoration: underline;">Result '.$s.' => </span>';print_r($result_fetch); echo nl2br("\n");
            $s++;
        }
    }
    else
    {
        echo("Error description: " . mysqli_error($conn));
    }
}

if(isset($_POST['update']) && $_POST['show'] == 8)
{
    $table_name=$_POST['utablename'];
    $values=$_POST['uvalues'];
    $where=$_POST['uwhere'];
    $select_query="update `$table_name` set ".$values." where ".$where;
    $excut=mysqli_query($conn,$select_query);
    if($excut == true)
    {
        echo("Successfully Update");
    }
    else
    {
        echo("Error description: " . mysqli_error($conn));
    }
}

if(isset($_POST['update_manual']) && $_POST['show'] == 6)
{
    $exp=explode(' ',$_POST['manul_query']);
    if(in_array('delete',$exp))
    {
        echo "<script>alert('this Query Note Comfortable.!!');</script>";
    }
    else
    {
        $select_query=$_POST['manul_query'];
        $mysqli=mysqli_query($conn,$select_query);
        $s=1;
        if($mysqli == true)
        {
            while($result=mysqli_fetch_assoc($mysqli))
            {
                echo '<div class="row"><span style="color:green;text-decoration: underline;">Result '.$s.' => </span>';print_r($result); echo '</div>';echo nl2br("\n");
                $s++;
            }
        }
        else
        {
            echo("Error description: " . mysqli_error($conn));
        }
    }
}

if(isset($_POST['show_folder']) && $_POST['show'] == 5)
{
    $log_directory = $_POST['folder_name'];
    $results_array = array();
    if (is_dir($log_directory))
    {
        if ($handle = opendir($log_directory))
        {
            //Notice the parentheses I added:
            while(($file = readdir($handle)) !== FALSE)
            {
                $results_array[] = $file;
            }
            closedir($handle);
        }
    }
    $sr=1;
    foreach($results_array as $value)
    {
        echo '<div class="col-sm-3">'.$sr.'. '.$value . '</div>';
        $sr++;
    }
}

if(isset($_POST['allTable']) && $_POST['show'] == 13)
{

    $cur_db=mysqli_fetch_assoc(mysqli_query($conn,"SELECT DATABASE()")); 
    $all_table=mysqli_query($conn,"SELECT table_name FROM information_schema.tables WHERE table_schema ='".$cur_db['DATABASE()']."' ");
    $sr=1;
    while($all_table1=mysqli_fetch_array($all_table))
    {
        echo '<div class="col-sm-3">'.$sr.'. '.$all_table1['table_name'] . '</div>';
        $sr++;
    }
}

if(isset($_POST['show_date_tp']) && $_POST['show'] == 3)
{
    $table_name=$_POST['table_name_dat_typ'];
    $field_name=$_POST['field_name_dat_typ'];
?>
<div class="table-responsive">
    <table class="table table-bordered taxt-center" id="example" width="100%">
        <thead>
            <tr>
                <th>Fields</th>
                <th>Type</th>
                <th>Key</th>
            </tr>
        </thead>
        <tbody>
            <?php
    if($table_name !='' && $field_name == '')
    {
        $q = mysqli_query($conn,'DESCRIBE '.$table_name);
        while($row = mysqli_fetch_array($q)) {
            //                            echo "{$row['Field']} - {$row['Type']}";echo nl2br("\n");
            echo "<tr><td>".$row['Field']."</td>";
            echo "<td>".$row['Type']."</td>";
            echo "<td>".$row['Key']."</td></tr>";
        }
    } 
    else if($field_name !='' && $table_name !='')
    {
        $q = mysqli_query($conn,'DESCRIBE '.$table_name);
        while($row = mysqli_fetch_array($q)) {
            if($field_name == $row['Field'])
            {
                //                                echo "{$row['Field']} - {$row['Type']}";echo nl2br("\n");
                echo "<tr><td>".$row['Field']."</td>";
                echo "<td>".$row['Type']."</td>";
                echo "<td>".$row['Key']."</td></tr>";
            }
        }
    }
    else
    {
        echo "<script>alert('Please enter value.!!');</script>";
    }
            ?>
        </tbody>
    </table>
</div>
<?php
}

if(isset($_POST['directory']) && $_POST['show'] == 10)
{
    $directory_path=$_POST['directory_path'];
    $directory_name=$_POST['directory_name'];
    $length=strlen($directory_path);
    $slash=substr($directory_path,($length-1));
    if($slash=='/' or $directory_path==''){
        if(is_dir($directory_path.$directory_name)){
            echo "Directory Already Exists.!!!";
        }
        else
        {
            mkdir($directory_path.$directory_name);
            echo 1;
        }
    }
    else{
        echo "Please Enter Proper Path.!!!";
    }
}

if(isset($_POST['directory_rm']) && $_POST['show'] == 11)
{
    $rm_directory_path=$_POST['rm_directory_path'];
    $rm_directory_name=$_POST['rm_directory_name'];
    $length=strlen($rm_directory_path);
    $slash=substr($rm_directory_path,($length-1));
    if($slash=='/' or $rm_directory_path==''){
        if(!is_dir($rm_directory_path.$rm_directory_name)){
            echo "Directory Doesn't  Exists.!!!";
        }
        else
        {
            function delete_directory($dirname) {
                if (is_dir($dirname))
                    $dir_handle = opendir($dirname);
                if (!$dir_handle)
                    return false;
                while($file = readdir($dir_handle)) {
                    if ($file != "." && $file != "..") {
                        if (!is_dir($dirname."/".$file)){
                            unlink($dirname."/".$file);
                        }
                        else{
                            delete_directory($dirname.'/'.$file);
                        }
                    }
                }
                closedir($dir_handle);
                rmdir($dirname);
                return true;
            }
            echo delete_directory($rm_directory_path.$rm_directory_name);
        }
    }
    else{
        echo "Please Enter Proper Path.!!!";
    }
}

if(isset($_POST['DirectName']) && $_POST['show'] == 12)
{
    function listFolderFiles($dir,$abc){
        $ffs = scandir($dir);
        $abc=$abc;

        unset($ffs[array_search('.', $ffs, true)]);
        unset($ffs[array_search('..', $ffs, true)]);
        unset($ffs[array_search('...', $ffs, true)]);
        unset($ffs[array_search('....', $ffs, true)]);
        unset($ffs[array_search('.....', $ffs, true)]);
        unset($ffs[array_search('......', $ffs, true)]);
        unset($ffs[array_search('.......', $ffs, true)]);
        unset($ffs[array_search('........', $ffs, true)]);
        unset($ffs[array_search('.........', $ffs, true)]);
        unset($ffs[array_search('..........', $ffs, true)]);
        unset($ffs[array_search('...........', $ffs, true)]);
        unset($ffs[array_search('............', $ffs, true)]);
        unset($ffs[array_search('.............', $ffs, true)]);
        unset($ffs[array_search('..............', $ffs, true)]);
        unset($ffs[array_search('...............', $ffs, true)]);
        unset($ffs[array_search('................', $ffs, true)]);
        unset($ffs[array_search('.................', $ffs, true)]);
        unset($ffs[array_search('..................', $ffs, true)]);
        unset($ffs[array_search('...................', $ffs, true)]);
        unset($ffs[array_search('....................', $ffs, true)]);
        unset($ffs[array_search('.....................', $ffs, true)]);
        unset($ffs[array_search('......................', $ffs, true)]);
        unset($ffs[array_search('.......................', $ffs, true)]);
        unset($ffs[array_search('........................', $ffs, true)]);
        unset($ffs[array_search('.........................', $ffs, true)]);
        unset($ffs[array_search('..........................', $ffs, true)]);
        unset($ffs[array_search('...........................', $ffs, true)]);
        unset($ffs[array_search('............................', $ffs, true)]);
        unset($ffs[array_search('.............................', $ffs, true)]);
        unset($ffs[array_search('..............................', $ffs, true)]);
        unset($ffs[array_search('...............................', $ffs, true)]);
        unset($ffs[array_search('................................', $ffs, true)]);
        unset($ffs[array_search('.................................', $ffs, true)]);
        unset($ffs[array_search('..................................', $ffs, true)]);
        unset($ffs[array_search('...................................', $ffs, true)]);
        unset($ffs[array_search('....................................', $ffs, true)]);
        unset($ffs[array_search('.....................................', $ffs, true)]);
        unset($ffs[array_search('......................................', $ffs, true)]);
        unset($ffs[array_search('.......................................', $ffs, true)]);
        unset($ffs[array_search('........................................', $ffs, true)]);
        unset($ffs[array_search('.........................................', $ffs, true)]);
        unset($ffs[array_search('..........................................', $ffs, true)]);
        unset($ffs[array_search('...........................................', $ffs, true)]);
        unset($ffs[array_search('............................................', $ffs, true)]);
        unset($ffs[array_search('.............................................', $ffs, true)]);
        unset($ffs[array_search('..............................................', $ffs, true)]);
        unset($ffs[array_search('...............................................', $ffs, true)]);
        unset($ffs[array_search('................................................', $ffs, true)]);
        unset($ffs[array_search('.................................................', $ffs, true)]);
        unset($ffs[array_search('..................................................', $ffs, true)]);
        if (count($ffs) < 1)
            return;

        echo '<ol>';
        foreach($ffs as $ff){
            if($abc == 'no')
            {
                if(strpos($ff,'.') == false)
                {
                    echo '<li>'.$ff;
                    echo '</li>';
                }
            }
            else
            {
                echo '<li>'.$ff;
                if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff,$abc);
                echo '</li>';
            }
        }
        echo '</ol>';
    }

    $folder=$_POST['DirectName'];
    $with_file=$_POST['with_file'];
    if($folder =='')
    {
        $folder= '../'.basename(getcwd());
    }
    //    $folder='../test';
    $xyz=$with_file;
    listFolderFiles($folder,$xyz); 
}

if(isset($_POST['folder_name1']) && isset($_POST['file_name1']) && $_POST['show'] == 14)
{
    $folder_name=$_POST['folder_name1'];
    $file_name=$_POST['file_name1'];
    $variabl=$folder_name.$file_name;
    echo "<br><p>";
    $myfile = fopen($variabl, "r") or die("Unable to open file!");
    // Output one line until end-of-file
    while(!feof($myfile)) {
        echo fgets($myfile) . "<br>";
    }
    fclose($myfile);
    echo "</p>";
}
?>
