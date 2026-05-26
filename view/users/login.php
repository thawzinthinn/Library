<?php require BASE_PATH . '/view/layout/header.php'; ?>

<?php
$error = $_SESSION['error'] ?? [];
$old = $_SESSION['old'] ?? [];
?>

<div class="auth-page">

    <div class="form-container">

        <h1>Welcome Back</h1>

        <?php if (!empty($error['general'])): ?>
            <div class="error-message">
                <?= htmlspecialchars($error['general']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/Public/index.php?page=login">

            <!-- EMAIL -->
            <label>Email Address</label>
            <input type="email"
                   name="email"
                   value="<?= htmlspecialchars($old['email'] ?? '') ?>">

            <?php if (!empty($error['email'])): ?>
                <small class="field-error">
                    <?= htmlspecialchars($error['email']) ?>
                </small>
            <?php endif; ?>

            <!-- PASSWORD -->
            <label>Password</label>

            <div class="password-wrapper">
                <input type="password"
                       name="password"
                       id="login-password">

                <span class="toggle-password" onclick="toggleLoginPassword()">
                    👁
                </span>
            </div>

            <?php if (!empty($error['password'])): ?>
                <small class="field-error">
                    <?= htmlspecialchars($error['password']) ?>
                </small>
            <?php endif; ?>

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

<script>
function toggleLoginPassword() {
    const input = document.getElementById("login-password");

    input.type = input.type === "password" ? "text" : "password";
}
</script>

<?php
unset($_SESSION['error'], $_SESSION['old']);
?>

<?php require BASE_PATH . '/view/layout/footer.php'; ?>