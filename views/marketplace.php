<div class="wrap mpl" id="mpl-wrapper">

    <h1 id="mpl-logo" class="clearfix">
        <div class="float-left hidden-xs">
            <img src="<?php echo MPL_BASEURL; ?>assets/imgs/mpl-logo-60x60.png" alt="MPL - Publisher" style="width:30px;height:30px">
            MPL - Publisher <?php if ($mpl_is_premium): ?>Premium ⭐<?php endif; ?>
            <span class="release-notes"></span>
        </div>
        <div class="float-left visible-xs">
            <img src="<?php echo MPL_BASEURL; ?>assets/imgs/mpl-logo-60x60.png" alt="MPL - Publisher" style="width:40px;height:40px">
        </div>
    </h1>

    <?php if ( ! $mpl_is_premium): ?>
        <hr />
        <p>📚 <?php _e("To get all the available formats and more cool features, visit", "publisher"); ?> <a href="https://mpl-publisher.ferranfigueredo.com?utm_medium=plugin&utm_campaign=premium" target="_blank">MPL-Publisher Premium</a> ⭐</p>
        <hr />
    <?php endif; ?>

    <iframe src="<?php echo $marketplace_url; ?>&utm_campaign=resources" id="marketplace-iframe" style="width:100%"></iframe>

</div>