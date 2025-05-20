        </div>
    </div>
    <script>
        // Add any admin-specific JavaScript here
        document.addEventListener('DOMContentLoaded', function() {
            // Confirm before deleting
            const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to delete this item?')) {
                        e.preventDefault();
                    }
                });
            });

            // Password confirmation validation
            const passwordFields = document.querySelectorAll('input[type="password"]');
            passwordFields.forEach(field => {
                field.addEventListener('input', function() {
                    const form = this.closest('form');
                    if (!form) return;

                    const password = form.querySelector('input[name="password"]');
                    const confirmPassword = form.querySelector('input[name="confirm_password"]');
                    
                    if (password && confirmPassword) {
                        if (password.value !== confirmPassword.value) {
                            confirmPassword.setCustomValidity('Passwords do not match');
                        } else {
                            confirmPassword.setCustomValidity('');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
