</div> <!-- Close container -->

<footer class="bg-white border-top text-center py-3 mt-auto">
    <div class="container">
        <p class="text-muted small mb-0">&copy; 2026 Politeknik Malaysia - DFP40443 Full Stack Web Development Mini Project 2</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('form.client-validate').forEach(form => {
    form.addEventListener('submit', function(e) {
        let valid = true;
        form.querySelectorAll('[required]').forEach(input => {
            if (!input.value.trim()) {
                valid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });
        if (!valid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
});
</script>
</body>
</html>