<?php  session_start();
 include_once 'includes/config.php';
//  all functions
require_once 'includes/functions.php';

 //run whenever this file is used no need of isset or any condition to get website image footer etc
 $sql5 ="SELECT * FROM  settings;";
 $result5 = $conn->query($sql5);
 $row5 = $result5->fetch_assoc();
 $_SESSION['web-name'] = $row5['website_name'];
 $_SESSION['web-img'] = $row5['website_logo'];
 $_SESSION['web-footer'] = $row5['website_footer'];

if (isset($_POST['login_submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['pwd']);

    if (empty($email) || empty($password)) {
        header("location: login.php?error=emptyinput");
        exit();
    }

    // Vérification spéciale pour admin2@example.com
    if ($email === 'admin2@example.com' && $password === 'ayoub123') {
        $_SESSION["userid"] = 1; // ID spécial pour l'admin
        $_SESSION["useremail"] = $email;
        $_SESSION["userrole"] = 'admin';
        header("location: admin/index.php");
        exit();
    }

    $sql = "SELECT customer_id, customer_email, customer_pwd, customer_role FROM customer WHERE customer_email = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: login.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        $pwdHashed = $row['customer_pwd'];
        $checkPwd = password_verify($password, $pwdHashed);

        if ($checkPwd === false) {
            header("location: login.php?error=wronglogin");
            exit();
        } elseif ($checkPwd === true) {
            $_SESSION["userid"] = $row["customer_id"];
            $_SESSION["useremail"] = $row["customer_email"];
            $_SESSION["userrole"] = $row["customer_role"];

            // Redirection vers la page d'accueil pour les utilisateurs normaux
            header("location: index.php");
            exit();
        }
    } else {
        header("location: login.php?error=wronglogin");
        exit();
    }

    mysqli_stmt_close($stmt);
} // end if login_submit

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />
    <title>Login(USER)</title>
    <style>
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }
      body {
        display: flex;
        flex-direction: column;
        height: 100vh;
        justify-content: center;
        align-items: center;
      }
      form {
        border: 1px solid red;
        width: 400px;
        padding: 25px;
        border-radius: 10px;
      }
      .logo-box {
        padding: 10px;
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
      }
      #signup-btn {
        text-decoration: none;
        color: white;
      }
    </style>
  </head>
  <body>

  	 <?php 
     if( !( isset( $_SESSION['id']))){
     ?>
    <form action="login.php" method="post">
      <div class="logo-box">
        <img
          src="admin/upload/<?php echo $_SESSION['web-img']; ?>"
          alt="logo"
          width="200px"
        />
      </div>
      <div class="row mb-3">
        <!-- <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label> -->
        <div class="col-sm-12">
          <input
            id="inputEmail"
            name="email"
            type="email"
            class="form-control"
            placeholder="Email"
            required="required"
            autocomplete="email"
          />
        </div>
      </div>
      <div class="row mb-3">
        <!-- <label for="inputPassword3" class="col-sm-2 col-form-label"
          >Password</label
        > -->
        <div class="col-sm-12" style="position:relative;">
          <input
            id="inputPassword"
            name="pwd"
            type="password"
            class="form-control"
            placeholder="Password"
            required="required"
            autocomplete="current-password"
            style="padding-right: 40px;"
          />
          <span style="position:absolute;top:50%;right:10px;transform:translateY(-50%);cursor:pointer;" onclick="toggleLoginPassword()">
            <ion-icon id="eyeIcon" name="eye-outline"></ion-icon>
          </span>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-sm-10">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="gridCheck1" />
            <label class="form-check-label" for="gridCheck1">
              Remeber Me
            </label>
          </div>
        </div>
      </div>
      <div style="float: right">
        <button 
        type="submit" 
        class="btn btn-primary">
        <a href="./signup.php" id="signup-btn">
             Sign up
		</a>
           
        </button>

        <button 
        type="submit" 
        class="btn btn-primary"
        name="login_submit">
            Login
        </button>
      </div>
    </form>

	<?php }?>

<script>
function toggleLoginPassword() {
  var pwd = document.getElementById('inputPassword');
  var eye = document.getElementById('eyeIcon');
  if (pwd.type === 'password') {
    pwd.type = 'text';
    eye.setAttribute('name', 'eye-off-outline');
  } else {
    pwd.type = 'password';
    eye.setAttribute('name', 'eye-outline');
  }
}
</script>

  </body>
</html>
