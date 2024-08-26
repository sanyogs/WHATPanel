<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

$db = \Config\Database::connect();

?>

<section class='custom-section-top-pad' >
 
</section>
<!-- TopBarText End -->

</section>

<!-- Product and Services Section Start -->
    <section id="regSection" class="bg-silver-light">
      <div class="regRow">
       
        <div class="col-lg-10 col-12">
          <div class="reg-form-wrap">
            <div class="reg-title-wrap">
              <h2 class="sectionTitle">Register <span>Now</span></h2>
              <p class="secText">
                Please enter your details to signup. | <span>Already have an account? <a href="<?php echo base_url('login'); ?>" class="bg-primary">Sign in</a></span>
              </p>
            </div>
            <form method="POST" action="<?php echo base_url('auth/register'); ?>">
              <div class="reg-form">
                <div class="col-md-6 col-12">
                  <div class="reg-left">
                    <div class="c-name-wrap">
                      <label for="">Company Name</label>
                      <input type="text" name="company_name">
                    </div>
                    <div class="f-name-wrap">
                      <label for="">Full Name</label>
                      <input type="text" name="fullname">
                    </div>
                    <div class="username-wrap">
                      <label for="">Username</label>
                      <input type="text" name="username">
                    </div>
                    <div class="email-wrap">
                      <label for="">Email</label>
                      <input type="email" name="email" id="email">
                    </div>
                    <div class="pass-wrap">
                      <label for="">Password</label>
                      <input type="password" name="password" id="password">
                    </div>
                    <div class="confirm-pass-wrap">
                      <label for="">Confirm Password</label>
                      <input type="password" name="confirmpassword" id="confirmpassword">
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="reg-right">
                    <div class="address-wrap">
                      <label for="">Address</label>
                      <input type="text" name="address">
                    </div>
                    <div class="city-wrap">
                      <label for="">City</label>
                      <input type="text" name="city">
                    </div>
                    <div class="state-wrap">
                      <label for="">State/Province</label>
                      <input type="text" name="state">
                    </div>
                    <div class="postal-wrap">
                      <label for="">Zip/Postal Code</label>
                      <input type="text" name="zip" id="zip">
                    </div>
                    <div class="country-wrap">
                      <label for="country">Country</label>
                      <select name="country" id="country">
						<?php $states = $db->table('hd_indian_states')->get()->getResult();
							foreach($states as $state) {
						?>
                        <option value="<?= $state->state_name; ?>"><?= $state->state_name; ?></option>
						<?php } ?>
                      </select>
                    </div>
                    <div class="phone-wrap">
                      <label for="">Phone</label>
                      <input type="text" name="" id="">
                    </div>
                  </div>
                </div>
              </div>
              <div class="submit-wrap">
                <button class="btn btn-lg btn-primary">Sign Up</button>
              </div>
            </form>
          </div>
        </div>
        
      </div>
    </section>

<?= $this->endSection() ?>