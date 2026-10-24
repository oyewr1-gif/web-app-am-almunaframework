<h1><?= $_ENV['APP_NAME'] ?></he>
<h2>Selamat Datang, <?php e($data['title']); ?></h2>

<!-- Contoh di app/views/auth/login.php atau register.php -->
<form action="<?= BASE_URL; ?>auth/loginProcess" method="POST">
    
    <!-- PANGGIL HELPER CSRF DI SINI -->
    <?= csrf_field(); ?>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>
    <button type="submit">Login</button>
</form>
