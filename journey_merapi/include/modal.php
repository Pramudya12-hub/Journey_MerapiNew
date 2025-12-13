<!-- ======================= LOGIN MODAL ======================= -->
<div id="loginModal" class="modal-overlay">
    <div class="modal-content">

        <!-- CLOSE BUTTON -->
        <button class="close-btn" data-close="login">&times;</button>

        <h2>Login <br> Journey<span>Merapi</span></h2>

        <!-- FORM LOGIN -->
        <form method="POST" action="auth/login.php">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan Username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan Password" required>
            </div>

            <button type="submit" class="submit-btn">Login</button>

            <div class="form-footer">
                Belum punya akun?
                <a href="#" data-switch="register">Daftar sekarang</a>
            </div>
        </form>

    </div>
</div>


<!-- ======================= REGISTER MODAL ======================= -->
<div id="registerModal" class="modal-overlay">
    <div class="modal-content">

        <!-- CLOSE BUTTON -->
        <button class="close-btn" data-close="register">&times;</button>

        <h2>Registrasi <br> Journey<span>Merapi</span></h2>

        <!-- FORM REGISTER -->
        <form method="POST" action="auth/register.php">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Buat Username" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Masukkan Email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Buat Password" required>
            </div>

            <button type="submit" class="submit-btn">Registrasi</button>

            <div class="form-footer">
                Sudah punya akun?
                <a href="#" data-switch="login">Login sekarang</a>
            </div>
        </form>

    </div>
</div>
