<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url(images/qbank-page2.jpg);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }


    .container {
      width: 400px;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 15px;
      background-color: transparent;
      backdrop-filter: blur(20px);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      color: white;
      display: flex;  
      align-items: center;  
      justify-content: center;
      flex-direction: column;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .form-group input {
      width: 300px;
      padding: 8px;
      border: 1px solid #000;
      border-radius: 4px;
      box-sizing: border-box;
      outline: none;
      padding: 15px 15px 15px 15px;
      border-radius: 10px;
      font-size: 20px;
    }
    

    .form-group button {
      width: 100%;
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      padding: 15px 15px 15px 15px;
      margin-top: 20px;
    }

    .form-group button:hover {
      background-color: #45a049;
    }

    .form-group a {
      display: block;
      text-align: center;
      margin-top: 10px;
      color: white;
      text-decoration: underline;
    }

    .form-group a:hover {
      text-decoration: underline;
    }
    .form-group a,
    .form-group p {
      display: inline;
      margin-right: 20px;
    }
    #below {
      margin-top: 30px;
    }

  </style>
</head>
<body>
  <div class="container">
    <h1>Login</h1>
    <form action="process_login.php" method="post">
      <div class="form-group">
        <!-- <label for="username">Username</label> -->
        <input type="text" id="username" name="username" placeholder="Username" required autofocus>
      </div>
      <div class="form-group">
        <!-- <label for="password">Password</label> -->
        <input type="password" id="password" name="password" placeholder="Password" required>
      </div>
      <div class="form-group">
        <button type="submit">Login</button>
      </div>
      <div class="form-group" id="below">
        <p>Don't have an account?</p><a href="register.php">Register here</a>
      </div>
    </form>
  </div>
</body>
</html>
