<?php
$roles = explode(',', session('role')); // boleh ada lebih dari satu role
?>

<div class="sidebar">
    <div class="menu">
        <div class="logo">
            <i class="fa-solid fa-laptop-code"></i>
            <span>AutoGrade+Code</span>
        </div>
        <hr>

        <!-- Dashboard link semua role -->
        <a href="<?= base_url('admin/dashboard') ?>">
            <i class="fa-solid fa-gauge"></i> Dashboard
        </a>
        <hr>

        <!-- Admin Menu -->
        <?php if (in_array("admin", $roles)) : ?>
            <p>Admin</p>
            <a href="<?= base_url('admin/session-management') ?>"><i class="fa-solid fa-calendar-days"></i> Session Management</a>
            <a href="<?= base_url('admin/courses') ?>"><i class="fa-solid fa-book"></i> Set Courses</a>

            <!-- Dropdown Setup Pengguna -->
            <div class="dropdown">
                <a href="#" class="dropdown-toggle">
                    <i class="fa-solid fa-user-gear"></i> User Setup
                </a>
                <ul class="dropdown-menu">
                    <li><a href="<?= base_url('admin/user-update') ?>">User Update</a></li>
                    <li><a href="<?= base_url('admin/account-register') ?>">Account Register</a></li>
                </ul>
            </div>
            <hr>
        <?php endif; ?>

        <!-- Course Coordinator Menu -->
        <?php if (in_array("course_coordinator", $roles)) : ?>
            <p>Course Coordinator</p>
            <a href="<?= base_url('coordinator/courses') ?>"><i class="fa-solid fa-book"></i> Courses</a>
            <a href="<?= base_url('coordinator/rubric') ?>"><i class="fa-solid fa-list-check"></i> Rubric</a>
            <a href="<?= base_url('coordinator/testcases') ?>"><i class="fa-solid fa-vial"></i> Test Cases</a>
            <hr>
        <?php endif; ?>

        <!-- Lecturer Menu -->
        <?php if (in_array("lecturer", $roles)) : ?>
            <p>Lecturer</p>
            <a href="<?= base_url('lecturer/courses') ?>"><i class="fa-solid fa-book"></i> Courses</a>
            <a href="<?= base_url('lecturer/code-evaluation') ?>"><i class="fa-solid fa-code"></i> Code Evaluation</a>
            <a href="<?= base_url('lecturer/reports') ?>"><i class="fa-solid fa-chart-bar"></i> Reports</a>
        <?php endif; ?>
    </div>

    <!-- Logout sentiasa bawah -->
    <div class="logout">
        <hr>
        <a href="<?= base_url('logout') ?>">
            <i class="fa-solid fa-right-from-bracket"></i> Log Out
        </a>
    </div>
</div>

<style>
/* Sidebar dropdown styling */
.dropdown {
    position: relative;
}

.dropdown-toggle {
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 0;
    color: #fff;
    text-decoration: none;
}

.dropdown-toggle i {
    margin-right: 8px;
}

/* Dropdown menu hidden by default */
.dropdown-menu {
    display: none;
    background: #f7f7f7;
    padding-left: 10px;
    margin: 5px 0 0 0;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

/* Menu items */
.dropdown-menu li a {
    display: block;
    padding: 8px 12px;
    color: #333;
    text-decoration: none;
    border-radius: 5px;
    transition: all 0.2s ease;
}

/* Hover effect */
.dropdown-menu li a:hover {
    background: #4f46e5;
    color: #fff;
}

/* Caret icon */
.dropdown-toggle::after {
    content: "\f0d7"; /* FontAwesome down caret */
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    margin-left: auto;
    transition: transform 0.3s ease;
}

/* Rotate caret when active */
.dropdown.active .dropdown-toggle::after {
    transform: rotate(180deg);
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggle = document.querySelector(".dropdown-toggle");
    const menu = document.querySelector(".dropdown-menu");
    const dropdown = document.querySelector(".dropdown");

    toggle.addEventListener("click", function(e) {
        e.preventDefault();
        menu.style.display = (menu.style.display === "none" || menu.style.display === "") ? "block" : "none";
        dropdown.classList.toggle("active");
    });
});
</script>
