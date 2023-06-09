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
          <h3 class="centered-bold">Customer List</h3>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $DetailsOfCust = postgreQuery('SELECT * FROM bookstore."customer"');
                foreach ($DetailsOfCust as $index => $row):
                  $NameOfCust = substr($row['customer_name'], 0, 100);
                  $EmailOfCust = substr($row['email'], 0, 100);
                  $PhoneOfCust = substr($row['phone'], 0, 100);
                  $addressDetails = postgreQuery("SELECT * FROM bookstore.address WHERE address_id = {$row['address_id']}");
                  $NameOfAddress = substr($addressDetails[0]['street'], 0, 100);
                  $NameOfDistrict = substr($addressDetails[0]['district'], 0, 100);
                  $NameOfCity = substr($addressDetails[0]['city'], 0, 100);
                  $NameOfState = substr($addressDetails[0]['state'], 0, 100);
                  $NameOfCountry = substr($addressDetails[0]['country'], 0, 100);
                  $PostalCode = substr($addressDetails[0]['postal_code'], 0, 100);
              ?>
              
              <tr>
                <th scope="row"><?= $index + 1 ?></th>
                <td><?= $NameOfCust ?></td>
                <td><?= $EmailOfCust ?></td>
                <td><?= $PhoneOfCust ?></td>
                <td><?= $NameOfAddress ?>, <?= $NameOfDistrict ?>, <?= $NameOfCity ?>, <?= $NameOfState ?>, <?= $NameOfCountry ?>, <?= $PostalCode ?></td>
              </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>
