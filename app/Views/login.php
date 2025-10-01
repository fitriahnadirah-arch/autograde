<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
      background-color: #e9eef5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .card {
      background: #fff;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      width: 350px;
      text-align: center;
    }
    .logo {
      margin-bottom: 15px;
    }
    .logo i {
      font-size: 55px;
      color: #3b82f6;
    }
    .card-title {
      font-size: 22px;
      font-weight: bold;
      font-family: Arial, Helvetica, sans-serif;
      margin-top: 10px;
      margin-bottom: 20px;
      color: #0b1220;
    }
    input {
      width: calc(100% - 24px);
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      border: 1px solid #d4d7dd;
      font-size: 14px;
      background: #f7f9fc;
    }
    input:focus {
      outline: none;
      border-color: #3b82f6;
      background: #fff;
    }
    .forgot {
      text-align: left;
      margin: 5px 0 15px 0;
      font-size: 14px;
    }
    .forgot a {
      color: #3b82f6;
      text-decoration: none;
    }
    button {
      width: 100%;
      padding: 12px;
      background-color: #3b82f6;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
    }
    button:hover {
      background-color: #2563eb;
    }
    .links {
      margin-top: 15px;
      font-size: 14px;
    }
    .links a {
      color: #3b82f6;
      text-decoration: none;
    }
    .alert {
      padding: 10px;
      border-radius: 6px;
      font-size: 14px;
      margin-bottom: 15px;
    }
    .alert.error {
      background: #ffdddd;
      color: #a94442;
    }
    .alert.success {
      background: #ddffdd;
      color: #3c763d;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="logo">
      <i class="fa-solid fa-laptop-code"></i>
      <h2 class="card-title">AutoGrade+Code</h2>
    </div>

    <!-- Flash Messages -->
    <?php if(session()->getFlashdata('error')): ?>
      <div class="alert error"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('success')): ?>
      <div class="alert success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('/login') ?>">
      <?= csrf_field() ?>
      <input type="email" name="email" placeholder="Email" required autofocus>
      <input type="password" name="password" placeholder="Password" required onkeypress="if(event.key==='Enter'){this.form.submit();}">
      <div class="forgot"><a href="<?= base_url('/reset-password') ?>">Forgot Password?</a></div>
      <button type="submit">Login</button>
    </form>
    <div class="links">
      Don’t have an account? <a href="<?= base_url('/register') ?>">Sign Up</a>
    </div>
  </div>
</body>
</html>
