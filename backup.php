<?php

include('Connect.php');
include('ErrorControl.php');
include('AccessControl.php');
session_start();

echo 'Denne side er lavet med det form&aring;l at tage backup af databasens indhold på en let m&aring;de s&aring; du kan sikrer dig mod data tab i fremtiden.<br />';
echo 'Dette kunne g&oslash;res ved hjaelig;lp af phpmyadmin men jeg ville ikke antage at brugerne af dette script n&oslash;dvendigvis vidste hvordan dette g&oslash;res s&aring; jeg har fors&oslash;gt at implementere<br />';
echo 'En let m&aring;de at tage backup og genetablere en backup til dette system<br />';
echo 'Du kan blot markere teksten for neden, kopiere den over i en tekst fil på din computer og gemme den som en .sql fil, indholdet af denne fil kopiere du s&aring; over i en boks til gendannelse p&aring; gendan backup af databse linket og k&aring;rer den gennem databasen<br /><br />';

$query = $db->prepare('SELECT * FROM `Book`');
try{
    $query->execute();    
}
catch(PDOException $e) {
    echo $e->getMessage();
}

$backup = "Backup from Book Database sent from Asselberghs.dk:<br /><br />";
$headers = "From: nick@asselberghs.dk\r\n";
$headers .= "Reply-To: nick@asselberghs.dk\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

while($row = $query->fetch(PDO::FETCH_OBJ))
{
			echo 'INSERT INTO `Book` (`Title`, `Author`, `Genre`, `Series`, `Copyright`, `Publisher`, `ISBN`, `Price`, `Format`, `Lend`, `Loaner`) VALUES (\''. $row->Title . '\', \'' . $row->Author . '\', \'' . $row->Genre . '\', \'' . $row->Series . '\', \'' . $row->Copyright . '\', \'' . $row->Publisher . '\', \'' . $row->ISBN . '\', \'' . $row->Price . '\', \'' . $row->Format . '\', \'' . $row->Lend . '\', \'' . $row->Loaner . '\');';
        	echo '<br />';
            $backup .= 'INSERT INTO `Book` (`Title`, `Author`, `Genre`, `Series`, `Copyright`, `Publisher`, `ISBN`, `Price`, `Format`, `Lend`, `Loaner`) VALUES (\''. $row->Title . '\', \'' . $row->Author . '\', \'' . $row->Genre . '\', \'' . $row->Series . '\', \'' . $row->Copyright . '\', \'' . $row->Publisher . '\', \'' . $row->ISBN . '\', \'' . $row->Price . '\', \'' . $row->Format . '\', \'' . $row->Lend . '\', \'' . $row->Loaner . '\');<br />';
}

$user = $_SESSION['User'];

$user_query = $db->prepare("SELECT Email FROM `Users` WHERE User LIKE :user");
$user_query->bindParam(':user', $user, PDO::PARAM_STR);
try{
    $user_query->execute();
}catch(PDOException $e) {
    echo $e->getMessage();
}
$user_email = $user_query->fetch();
$subject = 'Backup from Book Database';
$email = $user_email['Email'];
mail($email, $subject, $backup, $headers);
?>