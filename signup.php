<?php 
session_start(); // Start session to access form data
include_once('./includes/headerNav.php');

// Get form data from session if available
$formData = $_SESSION['signup_form_data'] ?? [];
unset($_SESSION['signup_form_data']); // Clear it after use

// Affichage d'un message d'erreur stylé si présent dans l'URL
$errorMsg = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'emptyInput':
            $errorMsg = "Veuillez remplir tous les champs.";
            break;
        case 'enterValidNumber':
            $errorMsg = "Veuillez entrer un numéro de téléphone valide.";
            break;
        case 'invalidemail':
            $errorMsg = "Veuillez entrer une adresse e-mail valide.";
            break;
        case 'pwdnotmatch':
            $errorMsg = "Les mots de passe ne correspondent pas.";
            break;
        case 'dbError':
            $errorMsg = isset($_GET['msg']) ? urldecode($_GET['msg']) : "Erreur lors de l'inscription. Veuillez réessayer.";
            break;
        default:
            $errorMsg = "Erreur lors de l'inscription. Veuillez vérifier vos informations.";
    }
}

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
    <title>Signup</title>
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
      .registeration-box {
        width: 60%;
        /* border: 1px solid red; */
        padding: 15px;
      }
      .logo-box{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-bottom: 25px;
      }
      .signup-title {
        text-align: center;
        text-transform: uppercase;
        margin: 10px;
      }
    </style>
  </head>
  <body>

    <div class="registeration-box">
      <?php if (!empty($errorMsg)): ?>
        <div style="background:#ffeaea;color:#a1001a;padding:12px 20px;border-radius:8px;margin-bottom:18px;font-weight:600;text-align:center;box-shadow:0 2px 8px #f8d7da;">
          <?php echo $errorMsg; ?>
        </div>
      <?php endif; ?>
      <div class="logo-box">
        <img
          src="admin/upload/<?php echo $_SESSION['web-img']; ?>"
          alt="logo"
          width="200px"
        />
      </div>
      <h1 class="signup-title">Sign Up Please</h1>
    <hr>
      <form action="includes/signup.inc.php" method="post"  class="row g-3">
        <div class="col-md-6">
          <label for="fullName" class="form-label">Full Name</label>
          <input
            type="text"
            class="form-control"
            name="name"
            id="fullName"
            required="required"
            placeholder="Full Name..."
            autocomplete="name"
            value="<?php echo htmlspecialchars($formData['name'] ?? ''); ?>"
          />
        </div>
        <div class="col-md-6">
          <label for="phoneNumber" class="form-label">Number</label>
          <input
            type="number"
            class="form-control"
            name="number"
            id="phoneNumber"
            required="required"
            placeholder="+92 123 456 789 1"
            autocomplete="tel"
            value="<?php echo htmlspecialchars($formData['number'] ?? ''); ?>"
          />
        </div>
        <div class="col-md-6">
          <label for="emailAddress" class="form-label">Email</label>
          <input 
          type="email" 
          class="form-control"
          name="email"
          id="emailAddress"
          placeholder="Email"
          required="required"
          autocomplete="email"
          value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>"
           />
        </div>
        <div class="col-md-6">
          <label for="userAddress" class="form-label">Address</label>
          <input
            type="text"
            class="form-control"
            name="address"
            id="userAddress"
            required="required"
            placeholder="1234 Main St"
            autocomplete="street-address"
            value="<?php echo htmlspecialchars($formData['address'] ?? ''); ?>"
          />
        </div>

        <div class="col-md-6" style="position:relative;">
          <label for="userPassword" class="form-label">Password</label>
          <input 
            type="password"
            class="form-control"
            name="pwd"
            id="userPassword"
            placeholder="password"
            required="required"
            autocomplete="new-password"
            style="padding-right:40px;"
          />
          <span style="position:absolute;top:50%;right:10px;transform:translateY(-50%);cursor:pointer;" onclick="toggleSignupPassword('userPassword','eyeIcon1')">
            <ion-icon id="eyeIcon1" name="eye-outline"></ion-icon>
          </span>
        </div>
        <div class="col-md-6" style="position:relative;">
          <label for="confirmPassword" class="form-label">Confirm Password</label>
          <input 
            type="password" 
            class="form-control" 
            name="rpwd"
            id="confirmPassword"
            placeholder="Confirm Password"
            required="required"
            autocomplete="new-password"
            style="padding-right:40px;"
          />
          <span style="position:absolute;top:50%;right:10px;transform:translateY(-50%);cursor:pointer;" onclick="toggleSignupPassword('confirmPassword','eyeIcon2')">
            <ion-icon id="eyeIcon2" name="eye-outline"></ion-icon>
          </span>
        </div>

        <div class="col-12" style="text-align:center; margin-top:20px;">
          <button 
            type="submit" 
            class="btn btn-primary"
            name="submit"
            style="width:200px; font-size:1.2rem;"
          >Register</button>
        </div>
      </form>
    </div>


    <!-- Script Tags -->
<script src="./js/jquery.js" type="text/javascript"></script>
<script src="./js/bootstrap.min.js" type="text/javascript"></script>
<script>
function toggleSignupPassword(inputId, eyeId) {
  var pwd = document.getElementById(inputId);
  var eye = document.getElementById(eyeId);
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
