<?php require BASE_PATH . '/view/layout/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL ?>/Public/css/login.css">

<div class="auth-page">

    <div class="form-container">

        <h1>Welcome Back</h1>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/Public/index.php?page=login">

            <label>Email Address</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>

        </form>

        <div class="register-link">
            Don’t have an account?
            <a href="<?= BASE_URL ?>/Public/index.php?page=register">
                Create Account
            </a>
        </div>

    </div>

</div>

<?php require BASE_PATH . '/view/layout/footer.php'; ?>