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
echo '<form name="login" action="'.$_SERVER['PHP_SELF'].'" method="post">';
echo 'Titel: <input type="text" name="Title"><br>';
echo 'Forfatter: <input type="text" name="Author"><br>';
echo 'Genre: <input type="text" name="Genre"><br>';
echo 'Serie: <input type="text" name="Series"><br>';
echo 'Copyright: <input type="text" name="Copyright"><br>';
echo 'Forlag: <input type="text" name="Publisher"><br>';
echo 'ISBN: <input type="text" name="ISBN"><br>';
echo 'Pris: <input type="text" name="Price"><br>';
echo 'Format:<br />';
echo '<input type="checkbox" name="FormatCheck[]" id="Paperback" value="Paperback"> <label for="Paperback">Paperback</label><br />';
echo '<input type="checkbox" name="FormatCheck[]" id="Hardback" value="Hardback"> <label for="Hardback">Hardback</label><br />';
echo '<input type="checkbox" name="FormatCheck[]" id="E-Book" value="E-Book"> <label for="E-Book">E-Book</label><br />';
echo '<input type="checkbox" name="FormatCheck[]" id="Comic" value="Comic"> <label for="Comic">Comic</label><br />';
echo '<input type="checkbox" name="FormatCheck[]" id="Manga" value="Manga"> <label for="Manga">Manga</label><br /><br />';
echo '<input type="submit" name="submit" value="Tilføj"><br />';

$TitleErrCheckIn=$_POST['Title'];
$AuthorErrCheckIn=$_POST['Author'];
$GenreErrCheckIn=$_POST['Genre'];
$SeriesErrCheckIn=$_POST['Series'];
$CopyrightErrCheckIn=$_POST['Copyright'];
$PublisherErrCheckIn=$_POST['Publisher'];
$ISBNErrCheckIn=$_POST['ISBN'];
$PriceErrCheckIn=$_POST['Price'];



$TitleErrCheck=ErrorControl($TitleErrCheckIn);
$AuthorErrCheck=ErrorControl($AuthorErrCheckIn);
$GenreErrCheck=ErrorControl($GenreErrCheckIn);
$SeriesErrCheck=ErrorControl($SeriesErrCheckIn);
$CopyrightErrCheck=ErrorControl($CopyrightErrCheckIn);
$PublisherErrCheck=ErrorControl($PublisherErrCheckIn);
$ISBNErrCheck=ErrorControl($ISBNErrCheckIn);
$PriceErrCheck=ErrorControl($PriceErrCheckIn);



if($TitleErrCheck==TRUE || $AuthorErrCheck==TRUE || $GenreErrCheck==TRUE || $SeriesErrCheck==TRUE || $CopyrightErrCheck==TRUE || $PublisherErrCheck==TRUE || $ISBNErrCheck==TRUE || $PriceErrCheck==TRUE) {
    
	$ErrCheck=TRUE;
}



if(isset($_POST['submit']) && $_POST['Title']!='' && $_POST['Author']!='' && $_POST['Genre'] !='' && $ErrCheck != TRUE){

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

$Query_Check=$db->prepare("SELECT * FROM Book WHERE Title LIKE :title");
$Query_Check->bindParam(':title', $Title, PDO::PARAM_STR);
try {
    $Query_Check->execute();
}catch(PDOException $e){
    echo $e->getMessage();
}
$titlecheck="";


	while($row = $Query_Check->fetch(PDO::FETCH_OBJ)) 
		{
		$titlecheck=$row->Title;		
		}

	if($titlecheck!=$Title){

	$Query_String=$db->prepare("INSERT INTO Book (Title, Author, Genre, Series, Copyright, Publisher, ISBN, Price, Format, User) VALUES (:title,:author,:genre,:series,:copyright,:publisher,:isbn,:price,:formatdata,:user)");
	$Query_String->bindParam(':title', $Title, PDO::PARAM_STR);
    $Query_String->bindParam(':author', $Author, PDO::PARAM_STR);
    $Query_String->bindParam(':genre', $Genre, PDO::PARAM_STR);
    $Query_String->bindParam(':series', $Series, PDO::PARAM_STR);
    $Query_String->bindParam(':copyright', $Copyright, PDO::PARAM_STR);
    $Query_String->bindParam(':publisher', $Publisher, PDO::PARAM_STR);
    $Query_String->bindParam(':isbn', $ISBN, PDO::PARAM_STR);
    $Query_String->bindParam(':price', $Price, PDO::PARAM_INT);
    $Query_String->bindParam(':formatdata', $FormatData, PDO::PARAM_STR);
    $Query_String->bindParam(':user', $_SESSION['User'], PDO::PARAM_STR);
    try{
        $Query_String->execute();
    }catch(PDOException $e) {
        echo $e->getMessage();
    }
	echo '<p>Bogen er tilfoejet til databasen</p>';

	} 
	else 
	{
	
	echo '<p>Bogen findes allerede i databasen</p>';
	
	}
	
}

if ($ErrCheck==TRUE) {
	
	
	echo '<p>Du har indtastet ugyldige karaktere</p>';
	
}

else {
	
		echo '<p>Formen er tom, ingen data er indsaette</p>';
}

?>