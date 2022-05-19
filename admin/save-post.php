<?php
include "config.php";
if(isset($_FILES['fileToUpload']))
{
    $errors = array();
    
    $file_name = $_FILES['fileToUpload']['name'];
    $file_size = $_FILES['fileToUpload']['size'];
    $file_tmp = $_FILES['fileToUpload']['tmp_name'];
    $file_type = $_FILES['fileToUpload']['type'];
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
session_start(); //always have to start the session, if we have to use session values;
$title = mysqli_real_escape_string($conn, $_POST['post_title']);
$description = mysqli_real_escape_string($conn, $_POST['postdesc']);
$category = mysqli_real_escape_string($conn, $_POST['category']);
$date = date("d M,Y");
$author = $_SESSION['user_id']; //the person who is logged in will be adding the post

$sql ="INSERT INTO post(title,description,category,post_date,author,post_img) 
VALUES('{$title}','{$description}',{$category},'{$date}',{$author},'{$file_name}');"; 
//when we have to run multiple query we have to use double ; as in the end of above line ie. }');";

// below sql code is to show increase the "number of posts" in one category under category tab
//$sql .= //.= will add this sql query to previous query
$sql .= "UPDATE category SET post = post + 1 WHERE category_id = {$category}"; 

//if we run mulitple queries then instead mysqli_query we use mysqli_multi_query
if(mysqli_multi_query($conn, $sql))
{
    header("location: {$hostname}/admin/post.php");
}
else
{
    echo "<div class='alert alert-danger'>Query Failed </div>";
}
?>