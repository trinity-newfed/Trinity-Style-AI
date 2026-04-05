<?php 
      $host = "localhost";
      $user = "root";
      $password = "";
      $dbname = "TF_Database";

      $conn = new mysqli($host, $user, $password, $dbname);
      session_start();  
      $otp = 0;
      if(isset($_SESSION['resetOTP'])){
        $otp = 2;
      }
    ?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Trinity - Auth</title>
    <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
    <link
      href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@300;400;500&display=swap"
      rel="stylesheet"
    />
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: "Montserrat", sans-serif;
        background-color: #ffffff;
        color: #000000;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        overflow: hidden;
      }

      .reset-container {
        width: 100%;
        max-width: 400px;
        padding: 40px;
        text-align: center;
        border: 1px solid #eeeeee;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      }

      h1 {
        font-family: "Playfair Display", serif;
        font-size: 2rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 10px;
      }

      p {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 30px;
        line-height: 1.6;
      }

      .form-group {
        margin-bottom: 20px;
        text-align: left;
      }

      label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 500;
        display: block;
        margin-bottom: 8px;
      }

      input {
        width: 100%;
        padding: 12px 0;
        border: none;
        border-bottom: 1px solid #000;
        font-family: "Montserrat", sans-serif;
        font-size: 1rem;
        outline: none;
        transition: border-color 0.3s;
      }

      input:focus {
        border-bottom: 2px solid #000;
      }

      .btn-reset {
        width: 100%;
        padding: 15px;
        background-color: #000;
        color: #fff;
        border: 1px solid #000;
        font-family: "Montserrat", sans-serif;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        cursor: pointer;
        margin-top: 20px;
        transition: all 0.3s ease;
      }

      .btn-reset:hover {
        background-color: #fff;
        color: #000;
      }

      .back-link {
        display: inline-block;
        margin-top: 25px;
        font-size: 0.75rem;
        text-decoration: none;
        color: #000;
        border-bottom: 1px solid transparent;
        transition: border-color 0.3s;
      }

      .back-link:hover {
        border-bottom: 1px solid #000;
      }

      @media (max-width: 480px) {
        .reset-container {
          border: none;
          box-shadow: none;
        }
      }
    </style>
  </head>
  <body>
    <?php if($otp == 2 && isset($_SESSION['otp_verified']) != true): ?>
      <div class="reset-container">
      <h1>Trinity</h1>
      <form action="../Database/resetPasswordConfirm.php" method="POST">
        <div class="form-group">
          <label for="password">OTP</label>
          <input type="text" name="otp" id="password" maxlength="6" pattern="\d{6}" required/>
        </div>
        <button type="submit" class="btn-reset">Submit</button>
      </form>
      <a href="reglog.php" class="back-link">Back to Login</a>
    </div>
    <?php elseif($otp == 0 && isset($_SESSION['otp_verified']) != true): ?>
      <div class="reset-container">
      <h1>Trinity</h1>
      <form action="../Database/resetPassword.php" method="POST">
        <div class="form-group">
          <label for="password">Your Email</label>
          <input type="email" id="password" name="email" required/>
        </div>

        <button type="submit" class="btn-reset">Sent OTP</button>
      </form>
      <a href="reglog.php" class="back-link">Back to Login</a>
    </div>
    <?php else: ?>
      <div class="reset-container">
      <h1>Trinity</h1>
      <form action="../Database/resetPasswordConfirm.php" method="POST">
        <div class="form-group">
          <label for="password">Your New Password</label>
          <input type="password" id="password" name="password" required/>
        </div>
        <div class="form-group">
          <label for="password">Confirm Your Password</label>
          <input type="password" id="password"/>
        </div>

        <button type="submit" class="btn-reset">Submit Password</button>
      </form>
      <a href="reglog.php" class="back-link">Back to Login</a>
    </div>
    <?php endif; ?>
  </body>
</html>
