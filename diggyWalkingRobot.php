//$link = mysqli_connect('aditya.sanjeevanihomoeo.in', 'aditya57', 'sainath@5998');
$link = mysqli_connect('aditya.sanjeevanihomoeo.in', 'fakedata', 'working_demo');
$db_list = mysqli_query($link, "SHOW DATABASES");
$sr = 0;
while ($row = mysqli_fetch_assoc($db_list)) {
    echo ++$sr.' ) '.$row['Database'] . "<br>";
}