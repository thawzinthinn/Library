<?php
include __DIR__ . '/../layout/header.php';
?>

<div class="modern-profile-scope">
    <div class="profile-container">
        <div class="profile-card animate-fade-in">

            <div class="profile-header">
                <div class="avatar-circle">
                    <?= strtoupper(substr(htmlspecialchars($_SESSION['user_name'] ?? 'U'), 0, 1)) ?>
                </div>
                <h2>Account Profile</h2>
                <p class="profile-subtitle">Manage your personal account details</p>
            </div>

            <form action="<?= BASE_URL ?>/Public/index.php?page=profile&action=update" method="POST"
                class="profile-form">

                <div class="form-group">
                    <label for="profile_name">Full Name</label>
                    <div class="profile-input-wrapper">
                        <span class="profile-input-icon">👤</span>
                        <input type="text" id="profile_name" name="name"
                            value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" required
                            placeholder="Enter your name" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="profile_email">Email Address</label>
                    <div class="profile-input-wrapper">
                        <span class="profile-input-icon">✉️</span>
                        <input type="email" id="profile_email" name="email"
                            value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>" required
                            placeholder="name@example.com" />
                    </div>
                </div>

                <div class="profile-actions">
                    <button type="submit" class="btn-update">
                        <span>Save Changes</span>
                        <span class="btn-icon">✨</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/../layout/footer.php';
?>