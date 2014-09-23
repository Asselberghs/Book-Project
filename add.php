<?php


include('Connect.php');
include('ErrorControl.php');
include('AccessControl.php');
echo '<form name="login" action="'.$_SERVER['PHP_SELF'].'" method="post">';
echo '<p>Titel: <input type="text" name="Title"><br>';
echo 'Forfatter: <input type="text" name="Author"><br>';
echo 'Genre: <input type="text" name="Genre"><br>';
echo 'Serie: <input type="text" name="Series"><br>';
echo 'Copyright: <input type="text" name="Copyright"><br>';
echo 'Forlag: <input type="text" name="Publisher"><br>';
echo 'ISBN: <input type="text" name="ISBN"><br>';
echo 'Pris: <input type="text" name="Price"><br>';
echo 'Format:';
echo '<input type="checkbox" name="FormatCheck[]" value="Paperback">Paperback<br />';
echo '<input type="checkbox" name="FormatCheck[]" value="Hardback">Hardback<br />';
echo '<input type="checkbox" name="FormatCheck[]" value="E-Book">E-Book<br />';
echo '<input type="checkbox" name="FormatCheck[]" value="Comic">Comic<br />';
echo '<input type="checkbox" name="FormatCheck[]" value="Manga">Manga<br />';
echo '</p>';
echo '<input type="submit" name="submit" value="Add">';

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

	$Query_String=$db->prepare("INSERT INTO Book (Title, Author, Genre, Series, Copyright, Publisher, ISBN, Price, Format) VALUES (:title,:author,:genre,:series,:copyright,:publisher,:isbn,:price,:formatdata)");
	$Query_String->bindParam(':title', $Title, PDO::PARAM_STR);
    $Query_String->bindParam(':author', $Author, PDO::PARAM_STR);
    $Query_String->bindParam(':genre', $Genre, PDO::PARAM_STR);
    $Query_String->bindParam(':series', $Series, PDO::PARAM_STR);
    $Query_String->bindParam(':copyright', $Copyright, PDO::PARAM_STR);
    $Query_String->bindParam(':publisher', $Publisher, PDO::PARAM_STR);
    $Query_String->bindParam(':isbn', $ISBN, PDO::PARAM_STR);
    $Query_String->bindParam(':price', $Price, PDO::PARAM_INT);
    $Query_String->bindParam(':formatdata', $FormatData, PDO::PARAM_STR);
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