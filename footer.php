</main>

<footer class="footer">
  <div class="footer-wrapper container">
    <?php if (is_active_sidebar('footer-widgets')) {
      dynamic_sidebar('footer-widgets');
    } ?>
  </div>
</footer>

<?php wp_footer(); ?>
</body>

</html>