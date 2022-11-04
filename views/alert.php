<?php if ($admin_notice and mpl_starts_with($admin_notice, '_VALIDATION_ERROR_')): ?>
    <div class="notice notice-info is-dismissible">
        <p>⚠️ <b><?php _e("Validation errors were found:", "publisher"); ?></b></p>
        <pre><?php echo esc_html(str_replace('_VALIDATION_ERROR_', '', $admin_notice)); ?></pre>
    </div>
<?php elseif ($admin_notice and mpl_starts_with($admin_notice, '_SHARING_LINK_')): ?>
    <div class="notice notice-info is-dismissible">
        <p>✅ <?php _e("Your book is available at the following link:", "publisher"); ?></p>
        <p class="alert alert-info">🔗 <a href="<?php echo str_replace('_SHARING_LINK_', '', $admin_notice); ?>" target="_blank"><?php echo str_replace('_SHARING_LINK_', '', $admin_notice); ?></a></p>
        <p><?php _e("<b>Online Book</b> will provide you a public URL to share within your readers. <a href='https://mpl-publisher.com/online-book-wordpress' target='_blank'>Learn more about what you can do with Online Books</a>.", "publisher"); ?>
    </div>
<?php elseif ($admin_notice): ?>
    <div class="notice notice-info is-dismissible">
        <p><?php echo esc_html($admin_notice); ?></p>
    </div>
<?php endif; ?>