<div class="error">
    <p><?php echo BASE_NAME; ?> error: Your environment doesn't meet all of the system requirements listed below.</p>
	<ul class="ul-disc">
            <li>
                <strong>PHP <?php echo BASE_REQUIRED_PHP_VERSION; ?>+</strong>
                <em>(You're running version <?php echo PHP_VERSION; ?>)</em>
            </li>
            <li>
                <strong>WordPress <?php echo BASE_REQUIRED_WP_VERSION; ?>+</strong>
                <em>(You're running version <?php echo esc_html( get_bloginfo( 'version' )); ?>)</em>
            </li>
	</ul>
</div>
