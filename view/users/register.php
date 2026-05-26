<?php require BASE_PATH . '/view/layout/header.php'; ?>

<?php
$error = $_SESSION['error'] ?? [];
$old = $_SESSION['old'] ?? [];
?>

<div class="auth-page">

    <div class="form-container">

        <h1>Create Account</h1>

        <?php if (!empty($error['general'])): ?>
            <div class="error-message">
                <?= htmlspecialchars($error['general']) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <!-- NAME -->
            <label>Full Name</label>
            <input type="text"
                   name="name"
                   value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                   class="<?= isset($error['name']) ? 'input-error' : '' ?>">

            <?php if (!empty($error['name'])): ?>
                <small class="field-error"><?= htmlspecialchars($error['name']) ?></small>
            <?php endif; ?>

            <!-- EMAIL -->
            <label>Email Address</label>
            <input type="email"
                   name="email"
                   value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                   class="<?= isset($error['email']) ? 'input-error' : '' ?>">

            <?php if (!empty($error['email'])): ?>
                <small class="field-error"><?= htmlspecialchars($error['email']) ?></small>
            <?php endif; ?>

            <!-- PASSWORD -->
            <label>Password</label>

            <div class="password-wrapper">
                <input type="password"
                       name="password"
                       id="register-password"
                       class="<?= isset($error['password']) ? 'input-error' : '' ?>">

                <span class="toggle-password" onclick="toggleRegisterPassword()">
                    👁
                </span>
            </div>

            <?php if (!empty($error['password'])): ?>
                <small class="field-error"><?= htmlspecialchars($error['password']) ?></small>
            <?php endif; ?>

            <button type="submit">Register</button>

        </form>

        <div class="login-link">
            Already have an account?
            <a href="<?= BASE_URL ?>/Public/index.php?page=login">
                Login
            </a>
        </div>

    </div>

</div>

<script>
function toggleRegisterPassword() {
    const input = document.getElementById("register-password");
    input.type = input.type === "password" ? "text" : "password";
}
</script>

<?php
unset($_SESSION['error'], $_SESSION['old']);
?>

<?php require BASE_PATH . '/view/layout/footer.php'; ?>