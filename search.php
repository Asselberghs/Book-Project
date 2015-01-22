<?php
/*
    This is a media database to mange your Books.
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
include('Connect.php');

session_start();

echo '<form name="Book" action="'.$_SERVER['PHP_SELF'].'" method="post">';
echo '<p><select name="Type"><option value="Title">Titel</option><option value="Author">Forfatter</option><option value="Genre">Genre</option><option value="Series">Serie</option><option value="Publisher">Forlag</option><option value="ISBN">ISBN</option><option value="Format">Format</option></select> <input type="text" name="Search"></p><br>';
echo '<input type="submit" name="submit" value="Search">';
echo '</form>';

if(isset($_POST['submit']) && $_POST['Search']!=''){

$Search=$_POST['Search'];
$Type=$_POST['Type'];
$Search= '%'.$Search.'%';

$Query_String=$db->prepare("SELECT * FROM Book WHERE ".$Type." LIKE :search");
$Query_String->bindParam(':search', $Search, PDO::PARAM_STR);
try {
    $Query_String->execute();
}catch(PDOException $e) {
    echo $e->getMessage();
}

echo '<center>';
echo '<table border="1">';
echo '<tr>';
echo '<td><p>Titel</p></td><td><p>Forfatter</p></td><td><p>Genre</p></td><td><p>Serie</p></td><td><p>Copyright</p></td><td><p>Forlag</p></td><td><p>ISBN</p></td><td><p>Pris</p></td><td><p>Format</p></td>';
echo '</tr>';

while($row = $Query_String->fetch(PDO::FETCH_OBJ)) 
{


if($row->Lend == 'Yes') {
	
	echo "<tr>";
	echo "<td bgcolor='red'>$row->Title</td><td bgcolor='red'>$row->Author</td><td bgcolor='red'>$row->Genre</td><td bgcolor='red'>$row->Series</td><td bgcolor='red'>$row->Copyright</td><td bgcolor='red'>$row->Publisher</td><td bgcolor='red'>$row->ISBN</td><td bgcolor='red'>$row->Price</td><td bgcolor='red'>$row->Format</td>";

			if(isset($_SESSION['Logged_In'])){
         	 echo "<td bgcolor='red'><a href='update_display.php?Title=$row->Title&ID=$row->ID&Author=$row->Author&Genre=$row->Genre&Series=$row->Series&Copyright=$row->Copyright&Publisher=$row->Publisher&ISBN=$row->ISBN&Price=$row->Price&Format=$row->Format'>Edit</a></td><td bgcolor='red'><a href='delete_display.php?Title=$row->Title&ID=$row->ID'>Delete</a></td><td><p>$row->Loaner</p></td>";
      					}			
	echo "</tr>";
	}
	else {
echo "<tr>";
echo "<td><p>$row->Title</p></td><td><p>$row->Author</p></td><td><p>$row->Genre</p></td><td><p>$row->Series</p></td><td><p>$row->Copyright</p></td><td><p>$row->Publisher</p></td><td><p>$row->ISBN</p></td><td><p>$row->Price</p></td><td><p>$row->Format</p></td>";

			if(isset($_SESSION['Logged_In'])){
          			echo "<td bgcolor='#808080'><a href='update_display.php?Title=$row->Title&ID=$row->ID&Author=$row->Author&Genre=$row->Genre&Series=$row->Series&Copyright=$row->Copyright&Publisher=$row->Publisher&ISBN=$row->ISBN&Price=$row->Price&Format=$row->Format'>Edit</a></td><td bgcolor='#808080'><a href='delete_display.php?Title=$row->Title&ID=$row->ID'>Delete</a></td>";
      					}
		}
echo "</tr>";
}

echo "</table>";
echo "</center>";



}

else {
	
		echo '<p>Formen er tom, ingen data er indsaette</p>';
}
?>