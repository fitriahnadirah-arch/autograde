<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
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
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      width: 350px;
      text-align: center;
    }

    .logo {
      background: #2196f3;
      width: 70px;
      height: 70px;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 auto 15px;
    }

    .logo i {
      font-size: 30px;
      color: white;
    }

    .card-title {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #0b1220;
    }


    .alert {
      padding: 10px;
      border-radius: 6px;
      font-size: 14px;
      margin-bottom: 15px;
    }
    .alert.error { background: #ffdddd; color: #a94442; }
    .alert.success { background: #ddffdd; color: #3c763d; }

    

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
button:hover { background-color: #2563eb; }

    .card p {
      margin-top: 15px;
      font-size: 14px;
    }

    .card a {
      color: #2196f3;
      text-decoration: none;
    }

    .card a:hover {
      text-decoration: underline;
    }

    @media (max-width: 400px) {
      .card { width: 90%; padding: 25px; }
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="logo"><i class="fa-solid fa-key"></i></div>
    <h2>Reset Password</h2>

    <!-- Flash Messages -->
    <?php if(session()->getFlashdata('error')): ?>
      <div class="alert error"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('success')): ?>
      <div class="alert success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- Form -->
    <form method="post" action="<?= base_url('/reset-password') ?>">
      <?= csrf_field() ?>
      <input type="email" name="email" placeholder="Enter Email" required>
      <input type="password" name="new_password" placeholder="New Password" required minlength="8">
      <button type="submit" class="btn">Reset</button>
    </form>

    <div class="links">
        <a href="<?= base_url('/login') ?>">Back to Login</a>
    </div>

  </div>
</body>
</html>
