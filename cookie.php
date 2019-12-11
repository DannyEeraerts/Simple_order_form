<?php
/**
 * Created by PhpStorm.
 * User: danny
 * Date: 2019-12-10
 * Time: 11:52
 */

   $DisplayForm = True;
    if ( isset($_COOKIE['userEmail']) ){
        $DisplayForm = False;
    }
    if ( isset($_POST['setcookie']) ) {
        $DisplayForm = False;
        $_SESSION['setcookie']= true;
    }
    if ( isset($_POST['nothing']) ) {
        $DisplayForm = False;
    }
    if ($DisplayForm) {

    ?>
    <div class="cookiealert-container container">
        <div class="alert alert-dismissible text-center cookiealert border border-primary mt-3 mb-3" role="alert">
            <div>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <h2>Do you like cookies &#x1F36A; ?</h2>We use cookies to ensure you get the best experience on our website. <a  href="#" target="_blank"> Read our Cookie policy</a>
                    <div class="mt-3">
                        <p class="mb-2">
                      <button type="submit" name ="setcookie" class="btn btn-primary btn-sm acceptcookies" aria-label="Close">
                          I agree
                        </button>
                        <button type="submit" name = "nothing" class="btn btn-primary btn-sm ml-3 refusecookies" aria-label="Close">
                          I not agree
                        </button>
                      </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
  }?>
