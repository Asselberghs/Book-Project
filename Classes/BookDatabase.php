<?php
require('MediaDatabase.php');


class BookDatabase extends MediaDatabase
{
    //showTable
    protected $table_stmt;
    protected $row;
    //addBook
    protected $title;
    protected $author;
    protected $genre;
    protected $series;
    protected $copyright;
    protected $publisher;
    protected $isbn;
    protected $price;
    protected $formatdata;


    public function __construct($server, $user, $password, $dbname)
    {
        parent::__construct($server, $user, $password, $dbname);
    }

    public function showTable($search = '', $field = '') {
        if ($search == '' && $field == '') {
            $this->table_stmt = $this->db->prepare("SELECT * FROM Book");
        } else {
            $this->table_stmt = $this->db->prepare("SELECT * FROM Book WHERE $field LIKE '%$search%'");
        }

        try {
            $this->table_stmt->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }
        echo '<center>';
        echo '<table border="1">';
        if($this->row->Lend == 'Yes') {
            echo '<tr class="borrowed">';
        } else {
            echo '<tr>';
        }
        echo '<td><p>Titel</p></td><td><p>Forfatter</p></td><td><p>Genre</p></td><td><p>Serie</p></td><td><p>Copyright</p></td><td><p>Forlag</p></td><td><p>ISBN</p></td><td><p>Pris</p></td><td><p>Format</p></td><td><p>Ejer</p></td></tr>';

        while($this->row = $this->table_stmt->fetch(PDO::FETCH_OBJ)) {
            if($this->row->Lend == 'Yes') {
                echo '<tr class="borrowed">';
            } else {
                echo '<tr>';
            }
            $User = $this->row->User;
            $User = ucfirst($User);
            echo '<td>'.$this->row->Title.'</td>';
            echo '<td>'.$this->row->Author.'</td>';
            echo '<td>'.$this->row->Genre.'</td>';
            echo '<td>'.$this->row->Series.'</td>';
            echo '<td>'.$this->row->Copyright.'</td>';
            echo '<td>'.$this->row->Publisher.'</td>';
            echo '<td>'.$this->row->ISBN.'</td>';
            echo '<td>'.$this->row->Price.'</td>';
            echo '<td>'.$this->row->Format.'</td>';
            echo '<td>'.$User.'</td>';
            if($_SESSION['Logged_In']) {
                echo "<td><a href=\"update.php?Title=" . $this->row->Title . "&ID=" . $this->row->ID . "&Author=" . $this->row->Author . "&Genre=" . $this->row->Genre . "&Series=" . $this->row->Series . "&Copyright=" . $this->row->Copyright . "&Publisher=" . $this->row->Publisher . "&ISBN=" . $this->row->ISBN ."&Price=".$this->row->Price."&Format=".$this->row->Format."&Lend=".$this->row->Lend."&Loaner=".$this->row->Loaner."\">Update</a></td>";
                echo "<td><a href=\"delete.php?Title=" . $this->row->Title . "\">Delete</a></td>";
            }
            echo '</tr>';

        }

        echo '</table>';
    }

    public function showAddBook() {
        if($_SESSION['Logged_In']) {
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

            $this->addBook($_POST['Title'],$_POST['Author'],$_POST['Genre'],$_POST['Series'],$_POST['Copyright'],$_POST['Publisher'],$_POST['ISBN'],$_POST['Price'],$_POST['FormatCheck']);
        } else {
            echo 'You need to login to be able to add a book to the database.';
        }
    }

    public function showUpdateBook($ID, $Title, $Author, $Genre, $Series, $Copyright, $Publisher, $ISBN, $Price) {
        if($_SESSION['Logged_In']) {

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

            $this->updateBook($_POST['ID'],$_POST['Title'],$_POST['Author'],$_POST['Genre'],$_POST['Series'],$_POST['Copyright'],$_POST['Publisher'],$_POST['ISBN'],$_POST['Price'],$_POST['FormatCheck'],$_POST['Lend'],$_POST['Loaner']);
        } else {
            echo 'You need to login to be able to update a book in the database.';
        }
    }

    public function showSearch() {
        parent::showSearch();
        $this->showTable($_POST['Search'],$_POST['Type']);
    }

    public function showRestore(){
        parent::showRestore();
        $this->restore($_POST['restore']);
    }

    private function updateBook($ID, $title, $author, $genre, $series, $copyright, $publisher, $isbn, $price, $formatcheck, $lend, $loaner) {
        @$formatdata = implode(",",$formatcheck);

        $update_query_title = $this->db->prepare("UPDATE Book SET Book.Title = :title WHERE Book.ID = :id");
        $update_query_title->bindParam(':title', $title, PDO::PARAM_STR);
        $update_query_title->bindParam(':id',$ID,PDO::PARAM_INT);
        try{
            $update_query_title->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }

        $update_query_author = $this->db->prepare("UPDATE Book SET Book.Author = :author WHERE Book.ID = :id");
        $update_query_author->bindParam(':author', $author, PDO::PARAM_STR);
        $update_query_author->bindParam(':id',$ID,PDO::PARAM_INT);
        try {
            $update_query_author->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }

        $update_query_genre = $this->db->prepare("UPDATE Book SET Book.Genre = :genre WHERE Book.ID = :id");
        $update_query_genre->bindParam(':genre', $genre, PDO::PARAM_STR);
        $update_query_genre->bindParam(':id', $ID, PDO::PARAM_INT);
        try {
            $update_query_genre->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }

        $update_query_series = $this->db->prepare("UPDATE Book SET Book.Series = :series WHERE Book.ID = :id");
        $update_query_series->bindParam(':series', $series, PDO::PARAM_STR);
        $update_query_series->bindParam(':id', $ID, PDO::PARAM_INT);
        try {
            $update_query_series->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }

        $update_query_copyright = $this->db->prepare("UPDATE Book SET Book.Copyright = :copyright WHERE Book.ID = :id");
        $update_query_copyright->bindParam(':copyright',$copyright, PDO::PARAM_STR);
        $update_query_copyright->bindParam(':id',$ID,PDO::PARAM_INT);
        try{
            $update_query_copyright->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }

        $update_query_publisher = $this->db->prepare("UPDATE Book SET Book.Publisher = :publisher WHERE Book.ID = :id");
        $update_query_publisher->bindParam(':publisher', $publisher, PDO::PARAM_STR);
        $update_query_publisher->bindParam(':id',$ID,PDO::PARAM_INT);
        try{
            $update_query_publisher->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }

        $update_query_isbn = $this->db->prepare("UPDATE Book SET Book.ISBN = :isbn WHERE Book.ID = :id");
        $update_query_isbn->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $update_query_isbn->bindParam(':id', $ID, PDO::PARAM_INT);
        try{
            $update_query_isbn->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }

        $update_query_price = $this->db->prepare("UPDATE Book SET Book.Price = :price WHERE Book.ID = :id");
        $update_query_price->bindParam(':price', $price, PDO::PARAM_INT);
        $update_query_price->bindParam(':id', $ID, PDO::PARAM_INT);
        try{
            $update_query_price->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }

        $update_query_format = $this->db->prepare("UPDATE Book SET Book.Format = :format WHERE Book.ID = :id");
        $update_query_format->bindParam(':format', $formatdata, PDO::PARAM_STR);
        $update_query_format->bindParam(':id', $ID, PDO::PARAM_INT);
        try {
            $update_query_format->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }

        $update_query_lend = $this->db->prepare("UPDATE Book SET Book.Lend = :lend WHERE Book.ID = :id");
        $update_query_lend->bindParam(':lend', $lend, PDO::PARAM_STR);
        $update_query_lend->bindParam(':id', $ID, PDO::PARAM_INT);
        try{
            $update_query_lend->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }

        $update_query_loaner = $this->db->prepare("UPDATE Book SET Book.Loaner = :loaner WHERE Book.ID = :id");
        $update_query_loaner->bindParam(':loaner', $loaner, PDO::PARAM_STR);
        $update_query_loaner->bindParam(':id', $ID, PDO::PARAM_INT);
        try{
            $update_query_loaner->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }

        echo 'Book has been updated';
    }

    private function addBook($title, $author, $genre, $series, $copyright, $publisher, $isbn, $price, $format){
        $this->title = $title;
        $this->author = $author;
        $this->genre = $genre;
        $this->series = $series;
        $this->copyright = $copyright;
        $this->publisher = $publisher;
        $this->isbn = $isbn;
        $this->price = $price;
        @$this->formatdata = implode(",", $format);

        $query_for_book = $this->db->prepare("SELECT * FROM Book WHERE Title LIKE :title");
        $query_for_book->bindParam(':title', $this->title, PDO::PARAM_STR);

        try{
            $query_for_book->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }

        $titlecheck = "";

        while($row = $query_for_book->fetch(PDO::FETCH_OBJ)) {
            $titlecheck = $row->Title;
        }

        if($titlecheck != $this->title) {
            $insert_statement = $this->db->prepare("INSERT INTO Book (Title, Author, Genre, Series, Copyright, Publisher, ISBN, Price, Format, User) VALUES (:title, :author, :genre, :series, :copyright, :publisher, :isbn, :price, :format, :user)");
            $insert_statement->bindParam(':title', $this->title, PDO::PARAM_STR);
            $insert_statement->bindParam(':author',$this->author,PDO::PARAM_STR);
            $insert_statement->bindParam(':genre', $this->genre, PDO::PARAM_STR);
            $insert_statement->bindParam(':series', $this->series, PDO::PARAM_STR);
            $insert_statement->bindParam(':copyright', $this->copyright, PDO::PARAM_STR);
            $insert_statement->bindParam(':publisher', $this->publisher, PDO::PARAM_STR);
            $insert_statement->bindParam(':isbn', $this->isbn, PDO::PARAM_STR);
            $insert_statement->bindParam(':price', $this->price, PDO::PARAM_INT);
            $insert_statement->bindParam(':format', $this->formatdata, PDO::PARAM_STR);
            $insert_statement->bindParam(':user', $_SESSION['User'], PDO::PARAM_STR);

            try{
                $insert_statement->execute();
            }catch (PDOException $e) {
                echo $e->getMessage();
            }

            echo 'Bogen er tilføjet til databasen.';

        } else {
            echo 'Bogen findes allerede i databasen.';
        }

    }

    public function deleteBook($Title) {
        $delete_query_statement = $this->db->prepare("DELETE FROM Book WHERE Title = :title");
        $delete_query_statement->bindParam(':title', $Title);
        try {
            $delete_query_statement->execute();
        }catch (PDOException $e) {
            echo $e->getMessage();
        }

        echo 'The book "'.$Title.'" has been deleted.';
    }
}
?>