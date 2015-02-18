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

session_start();


include('Connect.php');

try{
    $result = $db->prepare("SELECT * FROM Book ORDER BY Title");
    $result->execute();
}catch(PDOException $e) {
    echo $e->getMessage();
}

echo '<center>';
echo '<table border="1">';
echo '<tr>';
echo '<td><p>Titel</p></td><td><p>Forfatter</p></td><td><p>Genre</p></td><td><p>Serie</p></td><td><p>Copyright</p></td><td><p>Forlag</p></td><td><p>ISBN</p></td>';
if(isset($_SESSION['Logged_In'])) {
echo '<td><p>Pris</p></td>';	
}
echo '<td><p>Format</p></td>';
echo '<td><p>Ejer</p></td>';
echo '</tr>';

while($row = $result->fetch(PDO::FETCH_OBJ)) 
{

	if($row->Lend == 'Yes') {
	
	echo "<tr>";
    echo "<td bgcolor='red'>$row->Title</td><td bgcolor='red'>$row->Author</td><td bgcolor='red'>$row->Genre</td><td bgcolor='red'>$row->Series</td><td bgcolor='red'>$row->Copyright</td><td bgcolor='red'>$row->Publisher</td><td bgcolor='red'>$row->ISBN</td>";
	if(isset($_SESSION['Logged_In'])) 
	{
	 	echo "<td bgcolor='red'><p>$row->Price</p></td>";
	}
    
    $User = $row->User;
    $User = ucfirst($User);
    
	echo "<td bgcolor='red'>$row->Format</td>";
    echo "<td bgcolor='red'>".$User."</td>";

      	if(isset($_SESSION['Logged_In'])){
         	 echo "<td bgcolor='red'><a href='update_display.php?Title=$row->Title&ID=$row->ID&Author=$row->Author&Genre=$row->Genre&Series=$row->Series&Copyright=$row->Copyright&Publisher=$row->Publisher&ISBN=$row->ISBN&Price=$row->Price&Format=$row->Format'>Edit</a></td><td bgcolor='red'><a href='delete_display.php?Title=$row->Title&ID=$row->ID'>Delete</a></td><td><p>$row->Loaner</p></td>";
      	}

echo "</tr>";
	
	}
	else {

echo "<tr>";
echo "<td><p>$row->Title</p></td><td><p>$row->Author</p></td><td><p>$row->Genre</p></td><td><p>$row->Series</p></td><td><p>$row->Copyright</p></td><td><p>$row->Publisher</p></td><td><p>$row->ISBN</p></td>";
if(isset($_SESSION['Logged_In'])) 
{
	echo "<td><p>$row->Price</p></td>";
}

$User = $row->User;
$User = ucfirst($User);

echo "<td><p>$row->Format</p></td>";
echo "<td><p>".$User."</p></td>";

      if(isset($_SESSION['Logged_In'])){
          echo "<td bgcolor='#808080'><a href='update_display.php?Title=$row->Title&ID=$row->ID&Author=$row->Author&Genre=$row->Genre&Series=$row->Series&Copyright=$row->Copyright&Publisher=$row->Publisher&ISBN=$row->ISBN&Price=$row->Price&Format=$row->Format'>Edit</a></td><td bgcolor='#808080'><a href='delete_display.php?Title=$row->Title&ID=$row->ID'>Delete</a></td>";
      }

echo "</tr>";
	}
}


$CountStatement=$db->prepare('SELECT COUNT(id) FROM Book');
$CountStatement->execute();
$CountResult = $CountStatement->fetch();

$WorthStatement=$db->prepare('SELECT SUM(Price) FROM Book');
$WorthStatement->execute();
$WorthResult = $WorthStatement->fetch();

echo "<tr>";
echo "<td></td><td></td><td></td><td></td><td></td><td><p>Total". $CountResult['COUNT(id)'] ."Titler</p></td><td></td>";
if (isset($_SESSION['Logged_In'])) {
echo "<td>B&oslash;gernes V&aelig;rdi". $WorthResult['SUM(Price)'] ."</td><td></td>";	
} else {
echo "<td></td>";	
}
echo "</tr>";

echo "</table>";
echo "</center>";
?>