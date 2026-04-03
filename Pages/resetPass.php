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
    <div class="reset-container">
      <h1>Trinity</h1>
      <form action="#">
        <div class="form-group">
          <label for="password">Your New Password</label>
          <input type="password" id="password" name="password" required />
        </div>

        <div class="form-group">
          <label for="confirm-password">Confirm password</label>
          <input
            type="password"
            id="confirm-password"
            name="confirm-password"
            required
          />
        </div>

        <button type="submit" class="btn-reset">Submit Password</button>
      </form>
      <form action="../Database/reset_token.php" style="display: none">
        <div class="form-group">
          <label for="confirm-otp">OTP</label>
          <input type="text" id="confirm-otp" name="confirm-otp" required />
        </div>
      </form>
      <a href="reglog.php" class="back-link">Back to Login</a>
    </div>
  </body>
</html>
