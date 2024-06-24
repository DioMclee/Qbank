<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url(images/qbank-page2.jpg);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
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
      width: 100%;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"] {
      width: 300px;
      padding: 15px 15px 15px 15px;
      border: 1px solid #000;
      border-radius: 10px;
      font-size: 20px;
      box-sizing: border-box;
      outline: none;
    }

    .form-group.gender {
      display: flex;
      align-items: center;
    }

    .form-group.gender label {
      margin-right: 10px;
      font-weight: bold;
    }

    .form-group.gender .options {
      display: flex;
      align-items: center;
      margin-left: 50px;
    }

    .form-group.gender .options div {
      display: flex;
      align-items: center;
      margin-right: 15px;
    }

    .form-group.gender .options input {
      margin-right: 5px;
    }

    .form-group button {
      width: 100%;
      padding: 15px 15px 15px 15px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
    }

    .form-group button:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Register</h1>
    <form action="process_register.php" method="post">
      <div class="form-group">
        <input type="text" id="first_name" name="first_name" placeholder="First Name" required autofocus>
      </div>
      <div class="form-group">
        <input type="text" id="last_name" name="last_name"  placeholder="Last Name" required>
      </div>
      <div class="form-group">
        <input type="email" id="email" name="email" placeholder="Email" required>
      </div>
      <div class="form-group">
        <input type="text" id="username" name="username"  placeholder="Username" required>
      </div>
      <div class="form-group">
        <input type="password" id="password" name="password" placeholder="Password" required>
      </div>
      <div class="form-group gender">
        
        <div class="options">
          <div>
            <input type="radio" id="male" name="gender" value="Male" required>
            <label for="male">Male</label>
          </div>
          <div>
            <input type="radio" id="female" name="gender" value="Female" required>
            <label for="female">Female</label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <button type="submit">Register</button>
      </div>
    </form>
  </div>
</body>
</html>
