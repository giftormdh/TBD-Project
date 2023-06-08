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
  }

  .content {
    padding: 20px;
    margin-left: 200px; /* Adjust margin to accommodate the width of the sidebar */
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
      $cn = pg_connect("host=localhost port=5432 dbname=tbdproject user=giftormdh password=Llplga10");
          if ($cn) {
              $query = "SELECT Book_ID, Book_Name, Author_ID FROM bookstore.Book";
              $result = pg_query($cn, $query);
              $data = pg_fetch_all($result);
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
          <h3 class="centered-bold">Staff List</h3>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Position</th>
                <th>Store</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
                $DetailsOfStaff = postgreQuery('SELECT * FROM bookstore."staff"');
                foreach ($DetailsOfStaff as $index => $row):
                  $NameOfStaff = substr($row['staff_name'], 0, 100);
                  $StaffPosition = substr($row['position'], 0, 100);
                  $StoreDetails = postgreQuery("SELECT store_name FROM bookstore.bookstore_branch WHERE store_id = {$row['staff_id']}");
                  $Storename = substr($StoreDetails[0]['store_name'], 0, 100);
              ?>
              
              <tr>
                <th scope="row"><?= $index + 1 ?></th>
                <td><?= $NameOfStaff ?></td>
                <td><?= $StaffPosition ?></td>
                <td><?= $Storename ?></td>
                <td>
                    <a href="?selected_id=<?= $row['staff_id'] ?>"><button type="button" class="btn btn-primary">details</button></a>
                </td>
              </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
        <div class ="col-5 content">
          <?php
            [ $selectedStaff, $selectedStore, $selectedAddress] = [ null, null, null];
            if (isset($_GET['selected_id'])):
                $selectedStaff = postgreQuery('SELECT * FROM bookstore."staff" WHERE staff_id = '.$_GET['selected_id'])[0];
                $selectedStore = postgreQuery('SELECT * FROM bookstore."bookstore_branch" WHERE store_id = '.$selectedStaff['store_id'])[0];
                $selectedAddress = postgreQuery('SELECT * FROM bookstore."address" WHERE address_id = '.$selectedStaff['address_id'])[0];    
          ?>
          <table class="table mt-3 table-bordered centered-table">
            <tbody>
              <h2>Staff Details</h2>
              <tr>
                <th>Name</th>
                <td><?php echo str_replace(['}', '"', '{'], '', $selectedStaff["staff_name"]) ?></td>
              </tr>
              <tr>
                <th>Position</th>
                <td><?php echo str_replace(['}', '"', '{'], '', $selectedStaff["position"]) ?></td>
              </tr>
              <tr>
                <th>Email</th>
                <td><?php echo str_replace(['}', '"', '{'], '', $selectedStaff["email"]) ?></td>
              </tr>
              <tr>
                <th>Phone</th>
                <td><?php echo str_replace(['}', '"', '{'], '', $selectedStaff["phone"]) ?></td>
              </tr>
              <tr>
                <th>Salary</th>
                <td>Rp<?php echo str_replace(['}', '"', '{'], '', $selectedStaff["salary"]) ?></td>
              </tr>
              <tr>
                <th>Store Branch</th>
                <td><?php echo str_replace(['}', '"', '{'], '', $selectedStore["store_name"]) ?></td>
              </tr>
              <tr>
                <th>Address</th>
                <td><?php echo str_replace(['}', '"', '{'], '', $selectedAddress["address_id"]) ?></td>
              </tr>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>
