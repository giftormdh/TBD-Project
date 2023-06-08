<?php require_once __DIR__ . '/../connect.php' ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bookstore Web App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    
    <style>
    body {
    font-family: Montserrat, sans-serif;
    margin: 0;
    padding: 0;
    }

    .sidebar {
      background-color: #1374cf;
      color: #ffffff;
      height: 100vh;
      padding: 20px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      width: 200px;
      position: fixed;
      top: 0;
      left: 0;
    }

    .sidebar ul {
      list-style-type: none;
      padding: 0;
      margin: 0;
    }

    .sidebar ul li {
      margin-bottom: 30px;
    }

    @media (max-width: 400px) {
      .sidebar {
        width: 100%;
      }
    }

    .sidebar ul li:hover {
      background-color: #fff;
    }

    .sidebar ul li:hover a {
      color: #1374cf;
      border-radius: 10px;
    }

    .sidebar ul li a {
      color: #fff;
      text-decoration: none;
      display: block;
      padding: 10px 20px;
      margin:;
      transition: all 0.3s ease;
    }

    .sidebar ul li a:hover {
      color: #fff;
      color: #79a7d1;
      display: block;
      width: 100%;
      border-radius: 10px;
    }

    .content {
      padding: 20px;
      margin-left: 200px;
    }

    .centered-bold {
      text-align: center;
      font-weight: bold;
    }

    .centered-table {
      margin-left: auto;
      margin-right: auto;
    }

    .scrollable-table {
        max-height: 700px;
        overflow-y: scroll;
      }

    .details-button {
      padding: 10px 50px; 
      margin: 10px;
    }
  
    </style>

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
          $genreId = intval($_POST['genreId']);
          $languageId = intval($_POST['languageId']);
          $publisherId = intval($_POST['publisherId']);
          $authorId = intval($_POST['authorId']);
      
          $query = "INSERT INTO bookstore.book (Book_Name, Release_Year, Pages, Stock, Genre_ID, Language_ID, Publisher_ID, Author_ID) VALUES ('$bookName', $releaseYear, $pages, $stock, $genreId, $languageId, $publisherId, $authorId)";
      
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
          ?>
          <table class="table mt-3 table-bordered centered-table">
            <tbody>
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
          </form>
        <?php endif; ?>    

      </div>
    </div>
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
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>
