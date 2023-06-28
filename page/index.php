<?php require_once __DIR__ . '/../connect.php' ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bookstore Web App</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../style.css" />

    <?php
      $host = 'localhost';  
      $port = '5432';       
      $dbname = 'tbdproject'; 
      $user = 'giftormdh';   
      $password = 'Llplga10'; 
      
      $cn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
      
      if (!$cn) {
          echo "Failed to connect to the database.";
          exit;
      }
      
      if (isset($_POST['Send_AddBook'])) {
          $bookName = pg_escape_string($_POST['bookName']);
          $releaseYear = intval($_POST['releaseYear']);
          $pages = intval($_POST['pages']);
          $stock = intval($_POST['stock']);
          $price = intval($_POST['price']);
          $genreId = intval($_POST['genreId']);
          $languageId = intval($_POST['languageId']);
          $publisherId = intval($_POST['publisherId']);
          $authorId = intval($_POST['authorId']);
      
          $query = "INSERT INTO bookstore.book (Book_Name, Release_Year, Pages, Stock, Price, Genre_ID, Language_ID, Publisher_ID, Author_ID) VALUES ('$bookName', $releaseYear, $pages, $stock,$price, $genreId, $languageId, $publisherId, $authorId)";
      
          $result = pg_query($cn, $query);
      
          if ($result) {
          echo "<script>alert('Book added successfully.');</script>";
          } else {
              echo "<script>alert('Error adding book: " . pg_last_error($cn) . "');</script>";
          }
          }
      
        
    ?>
  </head>

  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-2 sidebar">
          <ul>
            <li>
              <a href="index.php"> Books</a>
            </li>
            <li>
              <a href="Branch.php"> Bookstore Branch</a>
            </li>
            <li>
              <a href="Customer.php"> Customer</a>
            </li>
            <li>
              <a href="Staff.php"> Staff</a>
            </li>
            <li>
              <a href="Transaction.php"> Transaction</a>
            </li>
          </ul>
        </div>
        <div class="col-md-10 content scrollable-table">

          <h1 class="centered-bold">Good Reading Bookstore</h1>
          <h3 class="centered-bold">Books</h3>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Book ID</th>
                <th>Book Name</th>
                <th>Author</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
                $DetailsOfBook = postgreQuery('SELECT * FROM bookstore."book"');
                foreach ($DetailsOfBook as $index => $row):
                  $NameOfBooks = substr($row['book_name'], 0, 100);
                  $authorDetails = postgreQuery("SELECT author_name FROM bookstore.author WHERE author_id = {$row['author_id']}");
                  $NameOfAuthors = substr($authorDetails[0]['author_name'], 0, 100);
              ?>
              
              <tr>
                <th scope="row"><?= $index + 1 ?></th>
                <td><?= $NameOfBooks ?></td>
                <td><?= $NameOfAuthors ?></td>
                <td>
                    <a href="?selected_id=<?= $row['book_id'] ?>"><button type="button" class="btn btn-primary">details</button></a>
                </td>
              </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
        <div class ="col-5 content">
          <?php
            [ $selectedBook, $selectedGenre, $selectedLanguage, $selectedPublisher, $selectedAuthor] = [ null, null, null, null, null ];
            if (isset($_GET['selected_id'])):
                $selectedBook = postgreQuery('SELECT * FROM bookstore."book" WHERE book_id = '.$_GET['selected_id'])[0];
                $selectedGenre = postgreQuery('SELECT * FROM bookstore."genre" WHERE genre_id = '.$selectedBook['genre_id'])[0];
                $selectedLanguage = postgreQuery('SELECT * FROM bookstore."language" WHERE language_id = '.$selectedBook['language_id'])[0];
                $selectedPublisher = postgreQuery('SELECT * FROM bookstore."publisher" WHERE publisher_id = '.$selectedBook['publisher_id'])[0];
                $selectedAuthor = postgreQuery('SELECT * FROM bookstore."author" WHERE author_id = '.$selectedBook['author_id'])[0];
                if (isset($_POST['deleteBook'])) {
                  $bookId = intval($_GET['selected_id']);
                  $deleteQuery = "DELETE FROM bookstore.book WHERE book_id = $bookId";
                  $deleteResult = pg_query($cn, $deleteQuery);
          
                  if ($deleteResult) {
                      echo "<script>alert('Book deleted successfully.');</script>";
                      // Redirect back to the main page
                      echo "<script>window.location.href='index.php';</script>";
                  } else {
                      echo "<script>alert('Error deleting book: " . pg_last_error($cn) . "');</script>";
                  }
              }
              if (isset($_POST['editBook'])) {
                $selectedBook = postgreQuery('SELECT * FROM bookstore."book" WHERE book_id = '.$_GET['selected_id'])[0];
                $selectedGenre = postgreQuery('SELECT * FROM bookstore."genre" WHERE genre_id = '.$selectedBook['genre_id'])[0];
                $selectedLanguage = postgreQuery('SELECT * FROM bookstore."language" WHERE language_id = '.$selectedBook['language_id'])[0];
                $selectedPublisher = postgreQuery('SELECT * FROM bookstore."publisher" WHERE publisher_id = '.$selectedBook['publisher_id'])[0];
                $selectedAuthor = postgreQuery('SELECT * FROM bookstore."author" WHERE author_id = '.$selectedBook['author_id'])[0];
              ?>
                <form action="" method="POST">
                  <h2>Edit Book</h2>
                  <div class="form-group">
                    <label for="bookName">Book Name:</label>
                    <input type="text" class="form-control" id="bookName" name="bookName" value="<?= $selectedBook['book_name'] ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="releaseYear">Release Year:</label>
                    <input type="text" class="form-control" id="releaseYear" name="releaseYear" value="<?= $selectedBook['release_year'] ?>" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="pages">Pages:</label>
                    <input type="text" class="form-control" id="pages" name="pages" value="<?= $selectedBook['pages'] ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="stock">Stock:</label>
                    <input type="text" class="form-control" id="stock" name="stock" value="<?= $selectedBook['stock'] ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" class="form-control" id="price" name="price" value="<?= $selectedBook['price'] ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="genre">Genre:</label>
                    <input type="text" class="form-control" id="genre" name="genre" value="<?= $selectedGenre['genre_id'] ?>" required> '
                  </div>

                  <div class="form-group">
                    <label for="language">Language:</label>
                    <input type="text" class="form-control" id="language" name="language" value="<?= $selectedLanguage['language_id'] ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="publisher">Publisher:</label>
                    <input type="text" class="form-control" id="publisher" name="publisher" value="<?= $selectedPublisher['publisher_id'] ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="author">Author:</label>
                    <input type="text" class="form-control" id="author" name="author" value="<?= $selectedAuthor['author_id'] ?>" required>
                  </div>

                  <input type="submit" class="btn btn-primary details-button" name="updateBook" value="Update Book">
                  <input type="button" id="cancelEditButton" class="btn btn-danger details-button" value="Cancel">
                </form>
              <?php
              }
              if (isset($_POST['updateBook'])) {
                $bookId = intval($_GET['selected_id']);
                $bookName = pg_escape_string($_POST['bookName']);
                $releaseYear = intval($_POST['releaseYear']);
                $pages = intval($_POST['pages']);
                $stock = intval($_POST['stock']);
                $price = intval($_POST['price']);
                $genreId = intval($_POST['genre']);
                $languageId = intval($_POST['language']);
                $publisherId = intval($_POST['publisher']);
                $authorId = intval($_POST['author']);
                
                
                $updateQuery = "UPDATE bookstore.book SET book_name='$bookName', release_year='$releaseYear', pages = '$pages', stock='$stock', price='$price', genre_id='$genreId', language_id='$languageId', publisher_id='$publisherId', author_id='$authorId' WHERE book_id=$bookId";
                // Create the update query using the variables for other input fields
              
                $updateResult = pg_query($cn, $updateQuery);
              
                if ($updateResult) {
                  echo "<script>alert('Book updated successfully.');</script>";
                  // Redirect back to the main page
                  echo "<script>window.location.href='index.php';</script>";
                } else {
                  echo "<script>alert('Error updating book: " . pg_last_error($cn) . "');</script>";
                }
              }
          ?>
          <table class="table mt-3 table-bordered centered-table">
            <tbody>
              <input type="button" class="btn btn-white" value="Cancel" onclick="goBack()">
              <h2>Book Details</h2>
              <tr>
                <th>Book Title</th>
                <td><?php echo str_replace(['}', '"', '{'], '', $selectedBook["book_name"]) ?></td>
              </tr>
              <tr>
                <th>Release Year</th>
                <td><?php echo str_replace(['}', '"', '{'], '', $selectedBook["release_year"]) ?></td>
              </tr>
              <tr>
                <th>Pages</th>
                <td><?php echo str_replace(['}', '"', '{'], '', $selectedBook["pages"]) ?></td>
              </tr>
              <tr>
                <th>Stock</th>
                <td><?php echo str_replace(['}', '"', '{'], '', $selectedBook["stock"]) ?></td>
              </tr>
              <tr>
                <th>Price</th>
                <td>Rp<?php echo str_replace(['}', '"', '{'], '', $selectedBook["price"]) ?></td>
              </tr>
              <tr>
                <th>Genre</th>
                <td><?php echo str_replace(['}', '"', '{'], '', $selectedGenre["genre_name"]) ?></td>
              </tr>
              <tr>
                <th>Language</th>
                <td><?php echo str_replace(['}', '"', '{'], '', $selectedLanguage["language_name"]) ?></td>
              </tr>
              <tr>
                <th>Publisher</th>
                <td><?php echo str_replace(['}', '"', '{'], '', $selectedPublisher["publisher_name"]) ?></td>
              </tr>
              <tr>
                <th>Author</th>
                <td><?php echo str_replace(['}', '"', '{'], '', $selectedAuthor["author_name"]) ?></td>
              </tr>
            </tbody>
          </table>
          <form action="" method="POST">
              <input type="hidden" name="selected_id" value="<?= $_GET['selected_id'] ?>">
              <input type="submit" class="btn btn-danger details-button" name="deleteBook" value="Delete Book">
              <input type="submit" class="btn btn-primary details-button" name="editBook" value="Edit Book">
          </form>
        <?php endif; ?>    
        <button id="addBookButton" type="button" class="btn btn-primary details-button" onclick="showAddBookForm()">Add Book</button>
      </div>
    </div>
    <div id="addBookForm" style="display: none;">
      <div class="col-5 content">
          <h2>Add Book</h2>
          <form action="" method="POST">
              <div class="form-group">
                  <label for="bookName">Book Name:</label>
                  <input type="text" class="form-control" id="bookName" name="bookName" required>
              </div>
              <div class="form-group">
                  <label for="releaseYear">Release Year:</label>
                  <input type="number" class="form-control" id="releaseYear" name="releaseYear" required>
              </div>
              <div class="form-group">
                  <label for="pages">Pages:</label>
                  <input type="number" class="form-control" id="pages" name="pages" required>
              </div>
              <div class="form-group">
                  <label for="stock">Stock:</label>
                  <input type="number" class="form-control" id="stock" name="stock" required>
              </div>
              <div class="form-group">
                  <label for="price">Price:</label>
                  <input type="number" class="form-control" id="price" name="price" required>
              </div>
              <div class="form-group">
                  <label for="genreId">Genre ID:</label>
                  <input type="number" class="form-control" id="genreId" name="genreId" required>
              </div>
              <div class="form-group">
                  <label for="languageId">Language ID:</label>
                  <input type="number" class="form-control" id="languageId" name="languageId" required>
              </div>
              <div class="form-group">
                  <label for="publisherId">Publisher ID:</label>
                  <input type="number" class="form-control" id="publisherId" name="publisherId" required>
              </div>
              <div class="form-group">
                  <label for="authorId">Author ID:</label>
                  <input type="number" class="form-control" id="authorId" name="authorId" required>
              </div>
              <input type="submit" class="btn btn-primary details-button" name="Send_AddBook" value="Add Book">
              <input type="button" id="cancelButton" class="btn btn-danger details-button" value="Cancel" onclick="hideAddBookForm()">
          </form>
      </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
      // Fungsi untuk menampilkan form "Add Book"
      function showAddBookForm() {
        var addBookForm = document.getElementById("addBookForm");
        var addBookButton = document.getElementById("addBookButton");
        addBookForm.style.display = "block";
        addBookButton.style.display = "none";
      }
      // Fungsi untuk menyembunyikan form "Add Book" dan menampilkan tombol "Add Book"
      function hideAddBookForm() {
        var addBookForm = document.getElementById("addBookForm");
        var addBookButton = document.getElementById("addBookButton");
        addBookForm.style.display = "none";
        addBookButton.style.display = "block";
      }

      document.getElementById("cancelEditButton").addEventListener("click", function() {
        window.location.href = 'index.php';
      });

      var cancelButton = document.getElementById("cancelButton");
      cancelButton.addEventListener("click", hideAddBookForm);
      
      var cancelEditButton = document.getElementById("cancelEditButton");
      cancelEditButton.addEventListener("click", hideAddBookForm);

      function goBack() {
        window.location.href = "../index.html";
      }
  
    </script>
  </body>
</html>
