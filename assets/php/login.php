<?php
# Initialize session
session_start();

# Check if user is already logged in, If yes then redirect him to index page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == TRUE) {
  echo "<script>" . "window.location.href='http://localhost/pancar/Main.html'" . "</script>";
  exit;
}

# Include connection
require_once "./config.php";

# Define variables and initialize with empty values
$user_login_err = $user_password_err = $login_err = "";
$user_login = $user_password = "";

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty(trim($_POST["user_login"]))) {
    $user_login_err = "Please enter your username or an email id.";
  } else {
    $user_login = trim($_POST["user_login"]);
  }

  if (empty(trim($_POST["user_password"]))) {
    $user_password_err = "Please enter your password.";
  } else {
    $user_password = trim($_POST["user_password"]);
  }

  # Validate credentials 
  if (empty($user_login_err) && empty($user_password_err)) {
    # Prepare a select statement
    $sql = "SELECT id, username, password FROM users WHERE username = ? OR email = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      # Bind variables to the statement as parameters
      mysqli_stmt_bind_param($stmt, "ss", $param_user_login, $param_user_login);

      # Set parameters
      $param_user_login = $user_login;

      # Execute the statement
      if (mysqli_stmt_execute($stmt)) {
        # Store result
        mysqli_stmt_store_result($stmt);

        # Check if user exists, If yes then verify password
        if (mysqli_stmt_num_rows($stmt) == 1) {
          # Bind values in result to variables
          mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

          if (mysqli_stmt_fetch($stmt)) {
            # Check if password is correct
            if (password_verify($user_password, $hashed_password)) {

              # Store data in session variables
              $_SESSION["id"] = $id;
              $_SESSION["username"] = $username;
              $_SESSION["loggedin"] = TRUE;

              # Redirect user to index page
              echo "<script>" . "window.location.href='http://localhost/pancar/Main.html'" . "</script>";
              exit;
            } else {
              # If password is incorrect show an error message
              $login_err = "The email or password you entered is incorrect.";
            }
          }
        } else {
          # If user doesn't exists show an error message
          $login_err = "Invalid username or password.";
        }
      } else {
        echo "<script>" . "alert('Oops! Something went wrong. Please try again later.');" . "</script>";
        echo "<script>" . "window.location.href='./login.php'" . "</script>";
        exit;
      }

      # Close statement
      mysqli_stmt_close($stmt);
    }
  }

  # Close connection
  mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset=UTF-8 />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scales1.0" />

  <link rel="stylesheet" href="../css/login.css">
  <title>Welcome</title>
</head>
<body>
    <section>
        <div class="col-lg-5">
            <?php
            if (!empty($login_err)) {
              echo "<div class='alert alert-danger'>" . $login_err . "</div>";
            }
            ?>

            <div class="form-box">
                <div class="form-value">
                    <form id="loginForm" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate">
                        <h2>Login</h2>
                        <div class="inputbox">
                            <ion-icon name="mail-outline"></ion-icon>
                            <input type="text" class="form-control" name="user_login" id="user_login" value="<?= $user_login; ?>" required>
                            <label for="">Email</label>
                            <small class="text-danger"><?= $user_login_err; ?></small>
                        </div>
                        <div class="inputbox">
                            <ion-icon name="lock-closed-outline"></ion-icon>
                            <input type="password" class="form-control" name="user_password" id="password" required>
                            <label for="">Password</label>
                            <small class="text-danger"><?= $user_password_err; ?></small>
                        </div>
                        <div class="forget">
                            <label for="togglePassword" id="togglePassword"><input type="checkbox">Show Pass</label>
                        </div>
                        <button type="submit" name="submit">LogIn</button>
                    
                        <div class="register">
                            <p>Don't Have an Account? <a href="./register.php">Register Here!</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        
        <div id="popup" class="popup">
            <p>Email or Password is incorrect!</p>
            <button onclick="hidePopup()">Close</button>
        </div>

        </div>
       
</body>

</html>