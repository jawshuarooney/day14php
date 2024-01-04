<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <!-- Latest compiled and minified CSS -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
 <!-- Latest compiled JavaScript -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
 <title>Document</title>
</head>

<body>
 <div class="container my-3">
  <h2>Form User</h2>
  <form class="" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
   <!-- first name -->
   <div class="form-floating mb-3 mt-3">
    <input required type="text" class="form-control" id="fname" placeholder="Enter First Name" name="fname">
    <label for="fname">First Name</label>
   </div>
   <!-- last name -->
   <div class="form-floating mt-3 mb-3">
    <input required type="text" class="form-control" id="lname" placeholder="Enter password" name="lname">
    <label for="lname">Last Name</label>
   </div>
   <!-- email -->
   <div class="form-floating mt-3 mb-3">
    <input required type="email" class="form-control" id="email" placeholder="Enter email" name="email">
    <label for="email">Email</label>
   </div>
   <!-- address -->
   <div class="form-floating mt-3 mb-3">
    <textarea required type="text" class="form-control" id="address" placeholder="Enter address" name="address"></textarea>
    <label for="address">Address</label>
   </div>
   <!-- password -->
   <div class="form-floating mt-3 mb-3">
    <input required type="password" class="form-control" id="password" placeholder="Enter password" name="password">
    <label for="password">Password</label>
   </div>
   <!-- submit button -->
   <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
  </form>
 </div>

 <!-- PHP -->
 <?php
 session_start();

 if ($_SERVER["REQUEST_METHOD"] == "POST") {
  /* form context */
  $firstName = filter_input(INPUT_POST, "fname", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $lastName = filter_input(INPUT_POST, "lname", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
  $address = $_POST["address"];
  $password = $_POST["password"];
  $hash = password_hash($password, PASSWORD_DEFAULT);
  /* db context */
  $servername = "localhost";
  $username = "root";
  $passwordDb = "";
  $dbname = "student_data";

  // Create connection
  $conn = new mysqli($servername, $username, $passwordDb, $dbname);

  // Check connection
  if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
  }

  // Check if email, first name, and last name already exist
  $checkSql = "SELECT * FROM users WHERE email = ?";
  $checkStmt = $conn->prepare($checkSql);
  $checkStmt->bind_param("s", $email,);
  $checkStmt->execute();
  $checkStmt->store_result();

  if ($checkStmt->num_rows > 0) {
   echo "<script>alert('Email already exist')</script>";
  } else {
   // Record doesn't exist, proceed with the insert
   $insertSql = "INSERT INTO users (first_name, last_name, email, address, password) VALUES (?, ?, ?, ?, ?)";
   $insertStmt = $conn->prepare($insertSql);
   $insertStmt->bind_param("sssss", $firstName, $lastName, $email, $address, $hash);

   if ($insertStmt->execute()) {
    echo "<script>alert('New records created successfully')</script>";
   } else {
    echo "Error: " . $insertStmt->error;
   }

   $insertStmt->close();
  }

  $checkStmt->close();

  //retrieve data from table
  $sql = "SELECT * FROM users";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
   while ($row = mysqli_fetch_assoc($result)) {
    // echo $row["id"] . "<br>";
    echo $row["first_name"] . "<br>";
    echo $row["last_name"] . "<br>";
    echo $row["email"] . "<br>";
    echo $row["address"] . "<br> <br> <br>";
   }
  } else {
   echo "no user found";
  }
  $conn->close();
 }


 ?>


</body>

</html>