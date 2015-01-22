<?php
include('Connect.php');
include('AccessControl.php');
$Title=$_GET['Title'];
$ID=$_GET['ID'];

echo '<form name="login" action="'.$_SERVER['PHP_SELF'].'" method="post">';
echo '<p>Titel: </p><input type="text" name="Title" value="'.$Title.'"><br>';
echo '<p>Format: </p><select name="Format">';
echo '<option value="Paperback">Paperback</option>';
echo '<option vaue="Hardback">Hardback</option>';
echo '<option vaue="E-book">E-book</option>';
echo '<option vaue="Comic">Comic</option>';
echo '<option vaue="Manga">Manga</option>';
echo '</select><br>';
echo '<input type="hidden" name="ID" value="'.$ID.'">';
echo '<input type="submit" name="submit" value="Slet">';


if(isset($_POST['submit'])){

$Title=$_POST['Title'];
$Format=$_POST['Format'];
$ID=$_POST['ID'];

$Query_String=$db->prepare("DELETE FROM Book WHERE ID = :id");
$Query_String->bindParam('id', $ID, PDO::PARAM_INT);
try{
    $Query_String->execute();
}catch(PDOException $e) {
    echo $e->getMessage();    
}

echo '<p>Bogen er blevet slettet</p>';

}

else {
	
		echo '<p>Formen er tom, ingen data er indsaette</p>';
}


?>