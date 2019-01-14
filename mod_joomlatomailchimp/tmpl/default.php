<?php 
// No direct access
defined('_JEXEC') or die; ?>
  
<div class="mod<?php echo $moduleclass_sfx; ?>">
    <<?php echo $headerTag; ?> class="pb-5 text-uppercase">
      <?php echo $modTitle; ?>
    </<?php echo $headerTag; ?>>
    <form id="newsletter-signup" action="<?php echo JURI::base() ?>modules/mod_joomlatomailchimp/subscribe.php?action=subscribe" method="post">
            <p><input type="email" name="signup-email" id="signup-email" placeholder="E-Mail address" class="form-control" required></p>
            <p><input type="text" name="signup-fname" id="signup-fname" placeholder="Firstname (optional)" class="form-control"></p>
            <p><input type="text" name="signup-lname" id="signup-lname" placeholder="Lastname (optional)" class="form-control"></p>
            <p><input type="checkbox" name="signup-agree" id="signup-agree" class="form-check-input m-0 position-relative" required>
            By signing up I agree with the <a href="<?php if($dppLink !== '') echo $dppLink; ?>">Data Protection Policy</a>.
            </p>
            <p><input type="submit" id="signup-button" class="btn btn-primary" value="I'm in!" ></p>
            <p id="signup-response"></p>
    </form>
</div><!-- /.mod -->

