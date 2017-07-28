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
require('Classes/BookDatabase.php');
$database = new BookDatabase('IP_Address','Username','Password','Database_name');
?>
<html>
<head>
<Title>Asselberghs.dk
</Title>
<link href="style.css" rel="stylesheet" type="text/css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <meta charset="UTF-8">
</head>
<body>
<div id="Top"><br>
Asselberghs.dk
</div>
<div id="MainMenu">
    <?php
    include('mainmenu.php');
    ?>
</div>
<div id="Menu">
    <?php
    include('menu.php');
    ?>
</div>
<div id="Content">
    <?php
    $database->showTable();
    ?>
</div>
<div id="Footer">
    <?php
    include('footer.php');
    ?>
</div>
</body>
</html>
?>
