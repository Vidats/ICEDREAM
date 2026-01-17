    </div> <!-- End Main Content -->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);
    </script>
    <?php if (isset($_SESSION['swal_message'])): ?>
    <script>
        Swal.fire({
            title: "<?= $_SESSION['swal_title'] ?? 'Thông báo' ?>",
            text: "<?= $_SESSION['swal_message'] ?>",
            icon: "<?= $_SESSION['swal_type'] ?? 'info' ?>",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then((result) => {
            <?php if (isset($_SESSION['swal_redirect'])): ?>
                window.location.href = "<?= $_SESSION['swal_redirect'] ?>";
            <?php endif; ?>
        });
    </script>
    <?php 
        // Clear session after showing
        unset($_SESSION['swal_title']);
        unset($_SESSION['swal_message']);
        unset($_SESSION['swal_type']);
        unset($_SESSION['swal_redirect']);
    ?>
    <?php endif; ?>
</body>
</html>