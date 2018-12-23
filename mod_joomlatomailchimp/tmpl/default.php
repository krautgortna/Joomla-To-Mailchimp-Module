<?php 
// No direct access
defined('_JEXEC') or die; ?>
  
<div class="mod<?php echo $moduleclass_sfx; ?>">
    <<?php echo $headerTag; ?> class="pb-5 text-uppercase">
      <?php echo $modTitle; ?>
    </<?php echo $headerTag; ?>>
    <form id="newsletter-signup" action="<?php echo JURI::base() ?>modules/mod_joomlatomailchimp/subscribe.php?action=subscribe" method="post">
            <p><input type="email" name="signup-email" id="signup-email" placeholder="E-Mail address" class="form-control"></p>
            <p><input type="submit" id="signup-button" class="btn btn-primary" value="I'm in!" ></p>
            <p id="signup-response"></p>
    </form>
</div><!-- /.mod -->

