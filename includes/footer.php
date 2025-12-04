    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-calendar-check"></i> <?php echo SITE_NAME; ?></h5>
                    <p class="mb-0">Efficient resource booking management system for educational institutions.</p>
                </div>
                <div class="col-md-3">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>" class="text-white-50">Home</a></li>
                        <?php if (isLoggedIn()): ?>
                            <li><a href="<?php echo SITE_URL; ?>/<?php echo isAdmin() ? 'admin' : 'user'; ?>/dashboard.php" class="text-white-50">Dashboard</a></li>
                        <?php else: ?>
                            <li><a href="<?php echo SITE_URL; ?>/login.php" class="text-white-50">Login</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/register.php" class="text-white-50">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Contact</h6>
                    <p class="text-white-50 mb-0">
                        <i class="fas fa-envelope"></i> <?php echo ADMIN_EMAIL; ?><br>
                        <i class="fas fa-phone"></i> +92-300-1234567
                    </p>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All Rights Reserved.</p>
                    <p class="mb-0"><small>Web Engineering Lab - CSC-314(L) - Fall 2025</small></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
