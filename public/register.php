<?php
require '../controllers/RegisterController.php';
require '../views/header.php';
require '../views/navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_id = $_POST['role'];

    $message = RegisterController::register($name, $email, $password, $role_id);
    echo $message;
}
?>

<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 30rem;">
        <div class="card-body">
            <h2 class="card-title text-center">Register</h2>
            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="1">Admin</option>
                        <option value="2">Editor</option>
                        <option value="3">Contributor</option>
                        <option value="4">User</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>
</div>

<?php require '../views/footer.php'; ?>