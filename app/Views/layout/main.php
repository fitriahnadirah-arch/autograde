<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AutoGrade+Code</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* === SIDEBAR === */
        .sidebar {
            width: 190px;               
            background: #0d2c54;
            color: #fff;
            min-height: 100vh;        
            position: fixed;            
            top: 0;
            left: 0;
            padding: 12px 8px;          
            display: flex;
            flex-direction: column;
        }

        .menu {
            flex: 1;
            overflow-y: auto;
        }

        .logout {
            margin-top: auto;
        }

        /* LOGO */
        .logo {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 7px;
            margin-bottom: 22px;
            color: #fff;
            padding-left: 4px;
        }
        .logo i {
            font-size: 24px;            
        }
        .logo span {
            font-size: 15px;           
            font-weight: bold;
            color: #fff;
        }

        /* GARIS PEMISAH */
        .sidebar hr {
            border: 1px solid #3a5a92;
            margin: 9px 0;
        }

        /* TITLE */
        .sidebar p {
            font-size: 11px;           
            text-transform: uppercase;
            opacity: .7;
            margin: 7px 0 5px 7px;
            font-weight: bold;
        }

        /* MENU ITEM */
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #fff;
            text-decoration: none;
            padding: 7px;              
            margin: 3px 0;
            border-radius: 5px;
            font-size: 13px;            
        }
        .sidebar a:hover {
            background: #133d7a;
        }

        /* LOGOUT */
        .logout a {
            font-size: 13px;            
            padding: 7px;
        }

        /* CONTENT */
        .content {
            margin-left: 190px;         
            background: #f2f2f2;
            padding: 20px;
            min-height: 100vh;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <?php 
       $roles = explode(',', session('role')); // boleh ada lebih dari satu role

        // Gunakan satu sidebar universal, tapi tunjuk menu ikut role
        echo $this->include('partials/sidebar'); 
    ?>
    <!-- Dynamic Content -->
    <div class="content">
        <?= $this->renderSection('content') ?>
    </div>

    <!-- Bootstrap 5 JS Bundle (wajib untuk modal) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
