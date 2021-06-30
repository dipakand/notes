<?php
error_reporting(0);
session_start();
$conn = mysqli_connect('localhost', 'root', '','testing');
if(isset($_POST["btn_refresh"])) 
{
    echo "<meta http-equiv='refresh' content='0'>";
}

if(isset($_POST["form_submit"])) 
{
    $file_gon_path=$_POST['file_path'];
    $photos=$_FILES['all_files']['type'];
    $count=0;
    $n=0;
    foreach($_FILES["all_files"]["name"] as $key => $filesa)  {
        $source = $_FILES['all_files']['tmp_name'][$count];
        $imagename = $filesa;
        $target = $file_gon_path.$imagename;
        $type=$_FILES["img_file"]["type"];
        if(move_uploaded_file($source, $target)==true){
            $n++;
        }
        $count++;
    }
    echo "<script>alert('Successfully Uploaded $n Files.!!');</script>";
    echo "<meta http-equiv='refresh' content='0'>";
}

if(isset($_POST['btn_down']))
{
    $filename = basename($_POST['file_name']);
    $path = $_POST['file_path_dwn']; 

    // Get parameters
    $file = urldecode($filename); // Decode URL-encoded string
    $filepath = $path . $file;
    // Process download
    if(file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        flush(); // Flush system output buffer
        readfile($filepath);
    }
}

if(isset($_POST['btn_filedelte']))
{
    function Delete($path)
    {
        if (is_dir($path) === true)
        {
            $files = array_diff(scandir($path), array('.', '..'));
            foreach ($files as $file)
            {
                Delete(realpath($path) . '/' . $file);
            }
            return rmdir($path);
        }
        else if (is_file($path) === true)
        {
            return unlink($path);
        }
        return false;
    }

    $file_name=$_POST['file_name'];
    $folder_name_del=$_POST['folder_name_del'];
    if($_POST['folder_name_del']!= '' && $_POST['file_name'] == '')
    {
        delete($folder_name_del);
        echo "<script>alert('Successfully Deleted Folder.!!');</script>";
        echo "<meta http-equiv='refresh' content='0'>";
    }
    elseif($_POST['folder_name_del'] == '' && $_POST['file_name'] != '')
    {
        delete($file_name);
        echo "<script>alert('Successfully Deleted File.!!');</script>";
        echo "<meta http-equiv='refresh' content='0'>";
    }
    elseif($_POST['folder_name_del'] != '' && $_POST['file_name'] != '')
    {
        $dele = $folder_name_del.$file_name;
        delete($dele);
        echo "<script>alert('Successfully Deleted File.!!');</script>";
        echo "<meta http-equiv='refresh' content='0'>";
    }


}

//$db_name=mysqli_query($conn,"SELECT schema_name FROM information_schema.schemata");
//while($db_fetch=mysqli_fetch_assoc($db_name))
//{
//    echo $db_fetch['schema_name'];echo nl2br("\n");
//}

/*$db_list = mysqli_query($conn, "SHOW DATABASES");
$sr = 0;
while ($row = mysqli_fetch_assoc($db_list)) {
    echo ++$sr.' ) '.$row['Database'] . "<br>";
}*/
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Update Files</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <?php
            if(!isset($_REQUEST['view'])){
            ?>
            <form  method="post" name="form1" class="form-group" enctype="multipart/form-data" style="padding-top:70px;">
                <div class="row">   
                    <div class="col-sm-4 col-sm-offset-1">
                        <div class="form-group">
                            <span class="btn  btn-file">
                                <input id="uploader" type="file" name="all_files[]" multiple="true" required/>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="file_path" placeholder="write something....">
                    </div>
                    <div class="col-sm-2" >
                        <input type="submit" name="form_submit" class="btn btn-success btn-block" value="Submit" />
                    </div>
                </div>
            </form>
            <?php
            }
            if(isset($_REQUEST['view'])){
            ?>
            <!--            <div class="row pull-right"><a type="submit" id="refresh_btn" >Refresh</a></div>-->
            <div class="row pull-right"><form method="post"><button type="submit" name="btn_refresh" >Refresh</button></form></div>
            <div class="panel-group" id="accordion"  style="padding-top:70px;"> 
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">File Upload</a>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form  method="post" name="form1" class="form-group" enctype="multipart/form-data">
                                <div class="row">   
                                    <div class="col-sm-4 col-sm-offset-1">
                                        <div class="form-group">
                                            <span class="btn  btn-file">
                                                <input id="uploader" type="file" name="all_files[]" multiple="true" required/>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="file_path" placeholder="Write Path eg.  master/upload/ ">
                                    </div>
                                    <div class="col-sm-2" >
                                        <input type="submit" name="form_submit" class="btn btn-success btn-block" value="Submit" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> 
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">View Table</a>
                        </h4>
                    </div>
                    <div id="collapse2" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form  method="post" name="form2" class="form-group">
                                <div class="row">   
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="table_name" name="table_name" placeholder="Enter Table Name">
                                    </div>
                                    <div class="col-sm-2" >
                                        <button type="button" name="btn_table" id="btn_table" class="btn btn-success btn-block">Submit</button>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="db_name" name="db_name" placeholder="">
                                    </div>
                                    <div class="col-sm-2 col-sm-offset-1" >
                                        <button type="button" name="btn_alltable" id="btn_alltable" class="btn btn-warning btn-block">View All Table List</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">File download</a>
                        </h4>
                    </div>
                    <div id="collapse3" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form  method="post" name="form3" class="form-group">
                                <div class="row">   
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="file_path_dwn" placeholder="Enter File Path eg.  estimate/ ">
                                    </div> 
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="file_name" placeholder="Enter File Name  eg.  index.php">
                                    </div>
                                    <div class="col-sm-2" >
                                        <input type="submit" name="btn_down" class="btn btn-success btn-block" value="Submit" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">View Table Field (Data Type)</a>
                        </h4>
                    </div>
                    <div id="collapse4" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form  method="post" name="form4" class="form-group">
                                <div class="row">   
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="table_name_dat_typ" name="table_name" placeholder="Enter Table Name">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="field_name_dat_typ" name="field_name" placeholder="Enter field Name">
                                    </div>
                                    <div class="col-sm-2" >
                                        <input type="submit" id="btn_datatype"  name="btn_datatype" class="btn btn-success btn-block" value="Submit" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">File Delete</a>
                        </h4>
                    </div>
                    <div id="collapse5" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form  method="post" name="form5" class="form-group">
                                <div class="row">   
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="folder_name_del" placeholder="Enter folder name eg. newfolder/ ">
                                    </div> 
                                    <div class="col-sm-1">
                                        <b style="color : red;">OR</b>
                                    </div> 
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="file_name" placeholder="Enter file Name">
                                    </div>
                                    <div class="col-sm-2" >
                                        <input type="submit" name="btn_filedelte" class="btn btn-success btn-block" value="Submit" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse6">View folder with Files</a>
                        </h4>
                    </div>
                    <div id="collapse6" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form  method="post" name="form6" class="form-group">
                                <div class="row">   
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="folder_name"  name="folder_name" placeholder="Enter Folder Name">
                                    </div>
                                    <div class="col-sm-2" >
                                        <button type="button" id="btn_fileview" name="btn_fileview" class="btn btn-success btn-block" >Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse7">Manual Query Result</a>
                        </h4>
                    </div>
                    <div id="collapse7" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form  method="post" name="form6" class="form-group">
                                <div class="row">   
                                    <div class="col-sm-10">
                                        <textarea type="text" class="form-control" id="enter_query" name="enter_query" placeholder="Enter query" rows="4"></textarea>
                                    </div>
                                    <div class="col-sm-2" >
                                        <button type="button" id="btn_qyeryfetch" name="btn_qyeryfetch" class="btn btn-success btn-block" >Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse8">Select Query Result</a>
                        </h4>
                    </div>
                    <div id="collapse8" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form  method="post" name="form6" class="form-group">
                                <div class="row">   
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="email">SELECT * FROM </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="s_table_name" name="s_table_name" placeholder="Enter Table name" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label >WHERE </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="s_where" name="s_where" placeholder="Enter where condition" >
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-sm-offset-2" >
                                            <button type="button" id="btn_select_query" name="btn_select_query" class="btn btn-success btn-block" >Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse9">Update Query Result</a>
                        </h4>
                    </div>
                    <div id="collapse9" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form  method="post" name="form6" class="form-group">
                                <div class="row">   
                                    <div class="col-sm-2 text-right" style="vertical-align:middle;">
                                        <div class="form-group">
                                            <label for="email">UPDATE </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="u_table_name" name="u_table_name" placeholder="Enter Table name" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="email">SET </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="u_values" name="u_values" placeholder="Enter value" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="pwd">Where </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="u_where" name="u_where" placeholder="Enter where condition" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-2 col-sm-offset-2" >
                                            <button type="button" id="btn_update_query" name="btn_update_query" class="btn btn-success btn-block">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse10">Make Directory</a>
                        </h4>
                    </div>
                    <div id="collapse10" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form  method="post" name="form7" class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-sm-6">   
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="directory_path" name="directory_path" placeholder="Enter Directory Path  eg. estimate/  " required>
                                                <label for="">Ex. Folder Name/</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="directory_name" name="directory_name" placeholder="Enter Directory Name  eg. upload  " required>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group">
                                            <div class="col-sm-4 col-sm-offset-4 " >
                                                <button type="button" id="btn_directory" name="btn_directory" class="btn btn-success btn-block">Submit</button>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-sm-6">   
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="rm_directory_path" name="rm_directory_path" placeholder="Enter Directory Path  eg. estimate/  " required>
                                                <label for="">Ex. Folder Name/</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="rm_directory_name" name="rm_directory_name" placeholder="Enter Directory Name  eg. upload  " required>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group">
                                            <div class="col-sm-4 col-sm-offset-4 " >
                                                <button type="button" id="btn_directory_rm" name="btn_directory_rm" class="btn btn-success btn-block">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse11">View Directory Structure</a>
                        </h4>
                    </div>
                    <div id="collapse11" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form  method="post" name="form8" class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="direct_name" name="direct_name" placeholder="Enter Directory " required>
                                            <input type="radio" name="with_file" value="yes" class="with_file">&nbsp;&nbsp;With File
                                            <input type="radio" name="with_file" value="no" class="with_file" checked>&nbsp;&nbsp;WithOut File
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-sm-offset-4 " >
                                        <div class="form-group">
                                            <button type="button" id="btn_dirtry" name="btn_dirtry" class="btn btn-success btn-block">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default ">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse12">View File</a>
                        </h4>
                    </div>
                    <div id="collapse12" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form  method="post" name="form12" class="form-group">
                                <div class="row">   
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="folder_name1" name="folder_name1" placeholder="Enter folder Name">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="file_name1" name="file_name1" placeholder="Enter file Name">
                                    </div>
                                    <div class="col-sm-2" >
                                        <input type="submit" name="btn_filsub" id="btn_filsub123" class="btn btn-success btn-block" value="Submit" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <?php }?>
                <div class="row" id="viewTable"></div>
                <div class="row ">
                    <?php
                    if(isset($_POST['btn_datatype']))
                    {
                        $table_name=$_POST['table_name'];
                        $field_name=$_POST['field_name'];
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

                    if(isset($_POST['btn_filsub']))
                    {

                        $folder_name=$_POST['folder_name1'];
                        $file_name=$_POST['file_name1'];
                        $variabl=$folder_name.$file_name;
                        //$data = htmlentities(file_get_contents($variabl));
                        //echo $data;

                        /*echo "<br>";
                        $myfile = fopen($variabl, "r") or die("Unable to open file!");
                        // Output one line until end-of-file
                        while(!feof($myfile)) {
                            echo fgets($myfile) . "<br>";
                        }
                        fclose($myfile);*/

                        $filename = $variabl;
                        $fp = fopen($filename, "r");

                        $content = fread($fp, filesize($filename));
                        $lines = explode("\n", $content);
                        fclose($fp);
                        //print_r($lines);
                        foreach($lines as $val)
                        {
                            echo $val .'<br>';
                        }

                    }
                    ?>
                </div>
            </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>

            <script>
                $(document).ready(function(){
                    $("#refresh_btn").click(function(){
                        location.reload();
                    });
                });
            </script>

            <script type="text/javascript">
                $(document).ready(function(){
                    $("#btn_select_query").click(function(){
                        var stablename = $('#s_table_name').val();
                        var swhere = $('#s_where').val();
                        if(s_table_name !='')
                        {
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_tables.php',
                                data:{
                                    show : 7,
                                    select : 'select',
                                    stablename : stablename,
                                    swhere : swhere
                                },
                                success: function(data) {
                                    $('#s_table_name').val("");
                                    $('#s_where').val("");
                                    $('#viewTable').html(data);
                                }
                            });
                        }
                    });
                });
            </script>

            <script type="text/javascript">
                $(document).ready(function(){
                    $("#btn_update_query").click(function(){
                        var utable_name = $('#u_table_name').val();
                        var uvalues = $('#u_values').val();
                        var uwhere = $('#u_where').val();
                        if(utable_name !='')
                        {
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_tables.php',
                                data:{
                                    show : 8,
                                    update : 'update',
                                    utablename : utable_name,
                                    uvalues : uvalues,
                                    uwhere : uwhere
                                },
                                success: function(data) {
                                    $('#utable_name').val("");
                                    $('#uvalues').val("");
                                    $('#uwhere').val("");
                                    $('#viewTable').html(data);
                                }
                            });
                        }
                    });
                });
            </script> 

            <script type="text/javascript">
                $(document).ready(function(){
                    $("#btn_qyeryfetch").click(function(){
                        var manul_query = $('#enter_query').val();
                        if(manul_query !='')
                        {
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_tables.php',
                                data:{
                                    show : 6,
                                    update_manual : 'update_manual',
                                    manul_query : manul_query,
                                },
                                success: function(data) {
                                    $('#enter_query').val("");
                                    $('#viewTable').html(data);
                                }
                            });
                        }
                    });
                });
            </script> 

            <script type="text/javascript">
                $(document).ready(function(){
                    $("#btn_fileview").click(function(){
                        var folder_name = $('#folder_name').val();
                        if(folder_name !='')
                        {
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_tables.php',
                                data:{
                                    show : 5,
                                    show_folder : 'show_folder',
                                    folder_name : folder_name,
                                },
                                success: function(data) {
                                    $('#folder_name').val("");
                                    $('#viewTable').html(data);
                                }
                            });
                        }
                    });
                });
            </script> 

            <script type="text/javascript">
                $(document).ready(function(){
                    $("#btn_datatype").click(function(){
                        var table_name_dat_typ = $('#table_name_dat_typ').val();
                        var field_name_dat_typ = $('#field_name_dat_typ').val();
                        if(field_name_dat_typ !='')
                        {
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_tables.php',
                                data:{
                                    show : 3,
                                    show_date_tp : 'show_date_tp',
                                    table_name_dat_typ : table_name_dat_typ,
                                    field_name_dat_typ : field_name_dat_typ,
                                },
                                success: function(data) {
                                    $('#table_name_dat_typ').val("");
                                    $('#field_name_dat_typ').val("");
                                    $('#viewTable').html(data);
                                }
                            });
                        }
                    });
                });
            </script> 

            <script type="text/javascript">
                $(document).ready(function(){
                    $("#btn_directory").click(function(){
                        var directory_path = $('#directory_path').val();
                        var directory_name = $('#directory_name').val();
                        if(directory_name !='')
                        {
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_tables.php',
                                data:{
                                    show : 10,
                                    directory : 'directory',
                                    directory_path : directory_path,
                                    directory_name : directory_name,
                                },
                                success: function(data1) {
                                    $('#directory_path').val("");
                                    $('#directory_name').val("");
                                    if(data1==1){
                                        alert("Directory Successfully Created.!!!");
                                    }
                                    else{
                                        alert(data1);
                                    }
                                }
                            });
                        }
                    });
                });
            </script> 

            <script type="text/javascript">
                $(document).ready(function(){
                    $("#btn_directory_rm").click(function(){
                        var rm_directory_path = $('#rm_directory_path').val();
                        var rm_directory_name = $('#rm_directory_name').val();
                        if(rm_directory_name !='')
                        {
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_tables.php',
                                data:{
                                    show : 11,
                                    directory_rm : 'directory_rm',
                                    rm_directory_path : rm_directory_path,
                                    rm_directory_name : rm_directory_name,
                                },
                                success: function(data1) {
                                    $('#rm_directory_path').val("");
                                    $('#rm_directory_name').val("");
                                    if(data1==1){
                                        alert("Directory Successfully Deleted.!!!");
                                    }
                                    else{
                                        alert(data1);
                                    }
                                }
                            });
                        }
                    });
                });
            </script>

            <script type="text/javascript">
                $(document).ready(function(){
                    $("#btn_dirtry").click(function(){
                        var direct_name = $('#direct_name').val();
                        var with_file=document.querySelector('input[name="with_file"]:checked').value;
                        $.ajax({
                            type: 'POST',
                            url: 'ajax_tables.php',
                            data:{
                                show : 12,
                                DirectName : direct_name,
                                with_file : with_file,
                            },
                            success: function(data1) {
                                $('#viewTable').html(data1);
                            }
                        });

                    });
                });
            </script>

            <script type="text/javascript">
                $(document).ready(function(){
                    $("#btn_alltable").click(function(){
                        $.ajax({
                            type: 'POST',
                            url: 'ajax_tables.php',
                            data:{
                                show : 13,
                                allTable : 'allTable',
                            },
                            success: function(data1) {
                                $('#viewTable').html(data1);
                            }
                        });

                    });
                });
            </script>

            <script type="text/javascript">
                $(document).ready(function(){
                    $("#btn_filsub").click(function(){
                        var folder_name1 = $('#folder_name1').val();
                        var file_name1 = $('#file_name1').val();
                        $.ajax({
                            type: 'POST',
                            url: 'ajax_tables.php',
                            data:{
                                show : 14,
                                'folder_name1' : folder_name1,
                                'file_name1' : file_name1,
                            },
                            success: function(data1) {
                                $('#viewTable').html(data1);
                            }
                        });

                    });
                });
            </script>

            <script type="text/javascript">
                $(document).ready(function(){
                    $("#btn_table").click(function(){
                        var table_name = $('#table_name').val();
                        var db_name = $('#db_name').val();
                        if(table_name !='')
                        {
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_tables.php',
                                data:{
                                    show : 1,
                                    table_name : table_name,
                                    db_name : db_name
                                },
                                success: function(data) {
                                    $('#table_name').val("");
                                    $('#viewTable').html(data);
                                }
                            });
                        }
                    });
                });
                function showEdit(editableObj) {
                    $(editableObj).css("background","#DCDCDC");
                    $(editableObj).attr('contenteditable','true');
                } 
                function saveToDatabase(editableObj,column,id,table_name,show,field) 
                {
                    $(editableObj).css("background","#FFF url(loaderIcon.gif) no-repeat right");
                    $.ajax({
                        url: "ajax_tables.php",
                        type: "POST",
                        data:{
                            update:1,
                            column:column,
                            editval:editableObj.innerHTML,
                            id:id,
                            table_name : table_name,
                            field : field,
                            show :show
                        },
                        success: function(data){
                            $(editableObj).css("background","#FDFDFD");
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_tables.php',
                                data:{
                                    show : show,
                                    table_name : table_name
                                },
                                success: function(data) {
                                    $('#viewTable').html(data);
                                }
                            });
                        }        
                    });
                    $(editableObj).css("background","#FDFDFD");
                }
            </script>
            </body>
        </html>
