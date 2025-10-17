		</div><!-- .container -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="footer-content">
				<nav class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Menu', 'renalinfo' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer-menu',
							'menu_id'        => 'footer-menu',
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
					?>
				</nav><!-- .footer-navigation -->

				<div class="site-info">
					<p>
						&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<?php bloginfo( 'name' ); ?>
						</a>
					</p>
					<p>
						<?php
						/* translators: %s: WordPress */
						printf( esc_html__( 'Powered by %s', 'renalinfo' ), '<a href="https://wordpress.org/">WordPress</a>' );
						?>
					</p>
				</div><!-- .site-info -->
			</div><!-- .footer-content -->
		</div><!-- .container -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
