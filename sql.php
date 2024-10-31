<?php
$con=mysqli_connect("localhost","root","","quickbite");
if(!$con)
{
echo"not connected";
}
$create=$con-> query("CREATE TABLE `quickbite`.`foood` (`name` VARCHAR(20) , `password` VARCHAR (20) )");
echo mysqli_error($con);
if(!$create)
{
    echo"error";
}
?>