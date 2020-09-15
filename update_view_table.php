<?php
error_reporting(0);
session_start();
include("include/config.php");
ini_set('max_file_uploads', '100');

if(isset($_POST["form_submit"])) {
    $file_gon_path=$_POST['file_path'];
    $photos=$_FILES['all_files']['type'];
    $count=0;
    $n=0;
//    print_r($_FILES["all_files"]["name"]);
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
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Update Files</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
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
                        <input type="text" class="form-control" name="file_path" placeholder="Enter Folder Name  eg. test/">
                    </div>
                    <div class="col-sm-2" >
                        <input type="submit" name="form_submit" class="btn btn-success btn-block" value="Submit" />
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
