    </main>
    
    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><?php echo SITE_NAME; ?></h5>
                    <p><?php echo SITE_DESCRIPTION; ?></p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5><?php echo t('links'); ?></h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>" class="text-decoration-none text-light"><?php echo t('home'); ?></a></li>
                        <li><a href="#" class="text-decoration-none text-light"><?php echo t('about'); ?></a></li>
                        <li><a href="#" class="text-decoration-none text-light"><?php echo t('contact'); ?></a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5><?php echo t('contact'); ?></h5>
                    <p>
                        <i class="fas fa-envelope"></i> <?php echo MAIL_FROM; ?><br>
                        <i class="fas fa-phone"></i> +966 XX XXX XXXX
                    </p>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. <?php echo t('allRightsReserved'); ?></p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
