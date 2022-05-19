<?php
include "config.php";

if(empty($_FILES['new-image']['name']))
{
$file_name = $_POST['old-image'];
}
else
{
    $errors = array();
    
    $file_name = $_FILES['new-image']['name'];
    $file_size = $_FILES['new-image']['size'];
    $file_tmp = $_FILES['new-image']['tmp_name'];
    $file_type = $_FILES['new-image']['type'];
    $file_ext = end(explode('.',$file_name));
    $extensions = array("jpeg","jpg","png");
    //below will check uploading file type
    if(in_array($file_ext,$extensions) === false)
    {
        $errors[] = "This extension file is not allowed, Please choose a JPG or PNG file"; 
    }
    //below code will check filesize
    if($file_size > 2097152) //bits in 2MB
    {
        $errors[] = "File size must be 2mb or lower.";
    }

    if(empty($errors) == true)  // if no errors found below code will upload
    {
        move_uploaded_file($file_tmp,"upload/".$file_name);
    }
    else
    {
        print_r($errors);
        die();
    }
}

$sql = "UPDATE post SET title='{$_POST["post_title"]}',description='{$_POST["postdesc"]}',category={$_POST["category"]},post_img='{$file_name}'
        WHERE post_id={$_POST["post_id"]}";

$result = mysqli_query($conn,$sql);

if($result)
{
    header("Location: {$hostname}/admin/post.php");
}
else{
    echo "query failed";
}

?>