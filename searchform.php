<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="s" class="assistive-text"><?php _e('Search'); ?></label>
	<input type="text" name="s" id="s" placeholder="<?php esc_attr_e('Search'); ?>" />
	<button type="submit" id="searchsubmit"><span><?php _e('Search') ?></span></button>
</form>