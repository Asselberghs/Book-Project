<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<?php

echo '<a href="index.php">Forside</a> &#124; <a href="login_display.php">Login</a> &#124; <a href="List_display.php">Bog Oversigt</a> &#124; <a href="search_display.php">S&#248;g i databasen</a>';

if(isset($_SESSION['Logged_In'])){

echo ' &#124; <a href="add_display.php">Tilf&#248;j bog</a> &#124; <a href="backup_display.php">Tag backup af database</a> &#124; <a href="restore_display.php">Gendan backup af database</a> &#124; <a href="Logout_display.php">Logout</a>';

}
?>