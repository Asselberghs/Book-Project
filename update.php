<?php
/*
    This is a media database to mange your Book.
    Copyright (C) 2013 Nick Tranholm Asselberghs

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
?>
<?php

include('Connect.php');
include('ErrorControl.php');
include('AccessControl.php');
$Title=$_GET['Title'];
$ID=$_GET['ID'];
$Author=$_GET['Author'];
$Genre=$_GET['Genre'];
$Series=$_GET['Series'];
$Copyright=$_GET['Copyright'];
$Publisher=$_GET['Publisher'];
$ISBN=$_GET['ISBN'];
$Price=$_GET['Price'];
$Format=$_GET['Format'];


$result = $db->prepare("SELECT * FROM Book WHERE ID = :ID");
$result->bindParam(':ID', $ID, PDO::PARAM_INT);
try {
    $result->execute();
}catch(PDOException $e) {
    echo $e->getMessage();
}

while($row = $result->fetch(PDO::FETCH_OBJ)) 
{
$Lend=$row->Lend;
$Loaner=$row->Loaner;
}

echo '<form name="login" action="'.$_SERVER['PHP_SELF'].'" method="post">';
echo '<p>Titel: </p><input type="text" name="Title" value="'.$Title.'"><br>';
echo '<p>Forfatter: </p><input type="text" name="Author" value="'.$Author.'"><br>';
echo '<p>Genre: </p><input type="text" name="Genre" value="'.$Genre.'"><br>';
echo '<p>Serie: </p><input type="text" name="Series" value="'.$Series.'"><br>';
echo '<p>Copyright: </p><input type="text" name="Copyright" value="'.$Copyright.'"><br>';
echo '<p>Publisher: </p><input type="text" name="Publisher" value="'.$Publisher.'"><br>';
echo '<p>ISBN: </p><input type="text" name="ISBN" value="'.$ISBN.'"><br>';
echo '<p>Price: </p><input type="text" name="Price" value="'.$Price.'"><br>';
echo '<p>Format: </p>';
echo '<input type="checkbox" name="FormatCheck[]" id="Paperback" value="Paperback"> <label for="Paperback">Paperback</label><br />';
echo '<input type="checkbox" name="FormatCheck[]" id="Hardback" value="Hardback"> <label for="Hardback">Hardback</label><br />';
echo '<input type="checkbox" name="FormatCheck[]" id="E-Book" value="E-Book"> <label for="E-Book">E-Book</label><br />';
echo '<input type="checkbox" name="FormatCheck[]" id="Comic" value="Comic"> <label for="Comic">Comic</label><br />';
echo '<input type="checkbox" name="FormatCheck[]" id="Manga" value="Manga"> <label for="Manga">Manga</label><br />';

echo '<p>Udlaant?</p><select name="Lend">';
echo '<option value="Yes">Yes</option>';
echo '<option value="No" selected="selected">No</option>';
echo '</select><br>';

echo '<p>Udlaant til: </p><input type="text" name="Loaner" value="'.$Loaner.'">';
echo '<input type="hidden" name="ID" value="'.$ID.'"><br><br />';
echo '<input type="submit" name="submit" value="Opdater"><br />';

$TitleErrCheckIn=$_POST['Title'];
$AuthorErrCheckIn=$_POST['Author'];
$GenreErrCheckIn=$_POST['Genre'];
$SeriesErrCheckIn=$_POST['Series'];
$CopyrightErrCheckIn=$_POST['Copyright'];
$PublisherErrCheckIn=$_POST['Publisher'];
$ISBNErrCheckIn=$_POST['ISBN'];
$PriceErrCheckIn=$_POST['Price'];
$LoanerErrCheckIn=$_POST['Loaner'];


$TitleErrCheck=ErrorControl($TitleErrCheckIn);
$AuthorErrCheck=ErrorControl($AuthorErrCheckIn);
$GenreErrCheck=ErrorControl($GenreErrCheckIn);
$SeriesErrCheck=ErrorControl($SeriesErrCheckIn);
$CopyrightErrCheck=ErrorControl($CopyrightErrCheckIn);
$PublisherErrCheck=ErrorControl($PublisherErrCheckIn);
$ISBNErrCheck=ErrorControl($ISBNErrCheckIn);
$PriceErrCheck=ErrorControl($PriceErrCheckIn);
$LoanerErrCheck=ErrorControl($LoanerErrCheckIn);




if($TitleErrCheck==TRUE || $AuthorErrCheck==TRUE || $GenreErrCheck==TRUE || $SeriesErrCheck==TRUE || $CopyrightErrCheck==TRUE || $PublisherErrCheck==TRUE || $ISBNErrCheck==TRUE || $PriceErrCheck==TRUE || $LoanerErrCheck==TRUE) {
    
	$ErrCheck=TRUE;
}


if(isset($_POST['submit']) && $_POST['Title']!='' && $_POST['Author']!='' && $_POST['Genre'] !='' && $ErrCheck != TRUE){

$ID=$_POST['ID'];
$Lend=$_POST['Lend'];
$Loaner=$_POST['Loaner'];
$Title=$_POST['Title'];
$Author=$_POST['Author'];
$Genre=$_POST['Genre'];
$Series=$_POST['Series'];
$Copyright=$_POST['Copyright'];
$Publisher=$_POST['Publisher'];
$ISBN=$_POST['ISBN'];
$Price=$_POST['Price'];
$Price=(int)$Price;
$Format=$_POST['FormatCheck'];
$FormatData = implode(",", $Format);

$Query_String=$db->prepare("UPDATE Book SET Title = :title WHERE ID = :id");
$Query_String->bindParam(':title', $Title, PDO::PARAM_STR);
$Query_String->bindParam(':id', $ID, PDO::PARAM_STR);
try {
    $Query_String->execute();
}catch(PDOException $e) {
    echo $e->getMessage();
}
$Query_String2=$db->prepare("UPDATE Book SET Format = :formatdata WHERE ID = :id");
$Query_String2->bindParam(':formatdata', $FormatData, PDO::PARAM_STR);
$Query_String2->bindParam(':id', $ID, PDO::PARAM_STR);
try {
    $Query_String2->execute();
}catch(PDOException $e) {
    echo $e->getMessage();
}
$Query_String3=$db->prepare("UPDATE Book SET Author = :author WHERE ID = :id");
$Query_String3->bindParam(':author', $Author, PDO::PARAM_STR);
$Query_String3->bindParam(':id', $ID, PDO::PARAM_STR);
try {
    $Query_String3->execute();
}catch(PDOException $e) {
    echo $e->getMessage();
}
$Query_String4=$db->prepare("UPDATE Book SET Series = :series WHERE ID = :id");
$Query_String4->bindParam(':series', $Series, PDO::PARAM_STR);
$Query_String4->bindParam(':id', $ID, PDO::PARAM_STR);
try {
    $Query_String4->execute();
}catch(PDOException $e) {
    echo $e->getMessage();
}
$Query_String5=$db->prepare("UPDATE Book SET Copyright = :copyright WHERE ID = :id");
$Query_String5->bindParam(':copyright', $Copyright, PDO::PARAM_STR);
$Query_String5->bindParam(':id', $ID, PDO::PARAM_STR);
try {
    $Query_String5->execute();
}catch(PDOException $e) {
    echo $e->getMessage();
}
$Query_String6=$db->prepare("UPDATE Book SET Lend = :lend WHERE ID = :id");
$Query_String6->bindParam(':lend', $Lend, PDO::PARAM_STR);
$Query_String6->bindParam(':id', $ID, PDO::PARAM_STR);
try {
    $Query_String6->execute();
}catch(PDOException $e) {
    echo $e->getMessage();
}
$Query_String7=$db->prepare("UPDATE Book SET Loaner = :loaner WHERE ID = :id");
$Query_String7->bindParam(':loaner', $Loaner, PDO::PARAM_STR);
$Query_String7->bindParam(':id', $ID, PDO::PARAM_STR);
try {
    $Query_String7->execute();
}catch(PDOException $e) {
    echo $e->getMessage();
}
$Query_String8=$db->prepare("UPDATE Book SET Genre = :genre WHERE ID = :id");
$Query_String8->bindParam(':genre', $Genre, PDO::PARAM_STR);
$Query_String8->bindParam(':id', $ID, PDO::PARAM_STR);
try {
    $Query_String8->execute();
}catch(PDOException $e) {
    echo $e->getMessage();
}
$Query_String9=$db->prepare("UPDATE Book SET Publisher = :publisher WHERE ID = :id");
$Query_String9->bindParam(':publisher', $Publisher, PDO::PARAM_STR);
$Query_String9->bindParam(':id', $ID, PDO::PARAM_STR);
try {
    $Query_String9->execute();
}catch(PDOException $e) {
    echo $e->getMessage();
}
$Query_String10=$db->prepare("UPDATE Book SET ISBN = :isbn WHERE ID = :id");
$Query_String10->bindParam(':isbn', $ISBN, PDO::PARAM_STR);
$Query_String10->bindParam(':id', $ID, PDO::PARAM_STR);
try {
    $Query_String10->execute();
}catch(PDOException $e) {
    echo $e->getMessage();
}
$Query_String11=$db->prepare("UPDATE Book SET Price = :price WHERE ID = :id");
$Query_String11->bindParam(':price', $Price, PDO::PARAM_INT);
$Query_String11->bindParam(':id', $ID, PDO::PARAM_STR);
try {
    $Query_String11->execute();
}catch(PDOException $e) {
    echo $e->getMessage();
}

$Query_String12=$db->prepare("UPDATE Book SET User = :user WHERE ID = :id");
$Query_String12->bindParam(':user', $_SESSION['User'], PDO::PARAM_STR);
$Query_String12->bindParam(':id', $ID, PDO::PARAM_STR);

try {
    $Query_String12->execute();
}catch(PDOException $e) {
    echo $e->getMessage();
}

echo '<p>Bogen er blevet opdateret</p>';

}

if ($ErrCheck==TRUE) {
	
	
	echo '<p>Du har indtastet ugyldige karaktere</p>';
	
}

else {
	
		echo '<p>Formen er tom, ingen data er indsaette</p>';
}

?>