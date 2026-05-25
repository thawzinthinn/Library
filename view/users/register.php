<?php require BASE_PATH . '/view/layout/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL ?>/Public/css/register.css">

<div class="auth-page">

    <div class="form-container">

        <h1>Create Account</h1>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <label>Full Name</label>
            <input type="text" name="name" placeholder="Enter your name" required>

            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Create a password" required>

            <button type="submit">
                Register
            </button>

        </form>

        <div class="login-link">
            Already have an account?
            <a href="<?= BASE_URL ?>/Public/index.php?page=login">
                Login
            </a>
        </div>

    </div>

</div>

<?php require BASE_PATH . '/view/layout/footer.php'; ?>