<?php

    ini_set ('display_errors', 1);
    error_reporting(E_ALL);

//set cookie
    if( (isset($_POST['order'])) && ((filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))) )
    {
        $email = htmlentities($_POST['email']);
        if (isset($_SESSION['setcookie'])){
            setcookie('userEmail', $email, time()+3600); // 1 Hour
        }
    }
        $errorStreet = $errorMail = $errorStreetnumber = $errorCity = $errorZipCode = $errorQuantity = "";
        $sendMessage ="";
        date_default_timezone_set("Europe/Brussels");
        $style = "d-none";

//valided input
/*if ($_SERVER["REQUEST_METHOD"] == "POST") {*/

if (isset($_POST['order']) ) {

    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errorMail = "Email has no valid format";
    }
    else {
        $errorMail = "";
        $_SESSION["email"] = $_POST['email'];
    }

    if ( empty(trim($_POST["street"]))  ){
        $errorStreet = "You did not fill out this required field.";
    }else {
        $_SESSION["street"] = testInputStreet($_POST['street']);
    }

    if ( empty(trim($_POST["streetnumber"])) ){
        $errorStreetnumber = "You did not fill out this required field.";
    } else {
        $_SESSION["streetnumber"] = testInputStreetNumber($_POST['streetnumber']);
    }

    if ( (empty(trim($_POST["city"])) ) ){
        $errorCity = "You did not fill out this required field.";
    } else {
       $_SESSION["city"] = testInputCity($_POST['city']);
    }

    if ( (empty($_POST["zipcode"])) ) {
      $errorZipCode =  "You did not fill out this required field.";
    }
    $pattern = "/^(?:(?:[1-9])(?:\d{3}))$/";
    if (preg_match($pattern, INTVAL($_POST["zipcode"]))) {
          $errorZipCode ="";
          $_SESSION["zipcode"] = $_POST['zipcode'];
    } else {
          $errorZipCode = "Only values between 1000 and 9999";
    }

    if ((!isset($_POST["drinks"]))&&(!isset($_POST["foods"])) ) {
        $errorQuantity = 'You have not ordered yet. Please make your choice';
    }
    elseif (isset($_POST["foods"])){
        $_SESSION["foods"]= $_POST["foods"];
        foreach ($_SESSION["foods"] AS $food) {
            $totalValue +=($foods[$food]['price']);
        }
        $_SESSION["totalfoods"] = $totalValue;
        if ( (isset($_SESSION["finalTotal"]))&&(isset($_SESSION["totaldrinks"])) ){
            $_SESSION["finalTotal"] = $_SESSION["totalfoods"] + $_SESSION["totaldrinks"];
        }
        else {
            $_SESSION["finalTotal"] = $_SESSION["totalfoods"];
        }
    }

    if ((!isset($_POST["drinks"]))&&(!isset($_POST["foods"])) ){
        $errorQuantity = 'You have not ordered yet. Please make your choice';
    }
    elseif (isset($_POST["drinks"])){
        $_SESSION["drinks"]= $_POST["drinks"];
        foreach ($_SESSION["drinks"] AS $drink) {
              $totalValue +=($drinks[$drink]['price']);
        }
        $_SESSION["totaldrinks"] = $totalValue;
        if ( (isset($_SESSION["finalTotal"]))&&(isset($_SESSION["totalfoods"])) ){
            $_SESSION["finalTotal"] = $_SESSION["totalfoods"] + $_SESSION["totaldrinks"];
        }
        else {
            $_SESSION["finalTotal"] = $_SESSION["totaldrinks"];
        }
    }

    // set message
    if ($errorStreet == "" && $errorStreetnumber == "" && $errorCity =="" && $errorZipCode == "" && $errorMail == "" && $totalValue > 0){
        date('Y-m-d H:i:s', strtotime('6 hour'));
        $style= 'border border-success text-success text-center p-4 mb-2';
        $radioVal = $_POST["radioButton"];
        if($radioVal == "First")
        {
            $_SESSION["deliverySpeed"] = $radioVal;
            $sendMessage = "Thanks for your order. This will be delivered before " . date("H:i", strtotime("2 hour"));
            $_SESSION["deliveryTime"] = date("H:i", strtotime("2 hour"));
            sleep(2);
            header('Location: mail.php');
        }
        else if ($radioVal == "Second")
        {
            $_SESSION["deliverySpeed"] = $radioVal;
            $sendMessage = "Thanks for your order. This will be delivered before " . date("H:i", strtotime("+45 minutes"));
            $_SESSION["deliveryTime"]= date("H:i", strtotime("+45 minutes"));
            $_SESSION["finalTotal"] += 7.5;
            sleep(2);
            header('Location: mail.php');
        }
    }

}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" type="text/css"
          rel="stylesheet"/>
    <link rel="stylesheet" href="style.css" type="text/css">
    <title>Order food & drinks</title>
</head>
<body>
<?php
    require 'cookie.php';
?>
<div class="container">
    <h1 class="mb-3 text-center bg-primary text-light p-3">Order food in restaurant <br> "The Personal Ham Processor"</h1>
    <nav>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link active" href="?food=1"><h3>Order food</h3></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?food=0"><h3>Order drinks</h3></a>
            </li>
        </ul>
    </nav>
    <h4 class = "<?php echo $style ?> "><?php echo $sendMessage ?> </h4>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" novalidate>
          <div class="form-row">
              <div class="form-group required col-12">
                  <label class ="control-label" for="email">E-mail:</label>
                  <label class="error float-right"><?php echo $errorMail; ?></label>
                  <input type="email" id="email" name="email" value ="
                       <?php
                       if(isset($_COOKIE['userEmail']))
                       {
                           echo $_COOKIE['userEmail'];
                       }
                       else
                       {
                           echo "";
                       }
                       ?>" class="form-control"/>
                  <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
              </div>
          </div>


          <fieldset>
              <legend>Delivery address</legend>

              <div class="form-row">
                  <div class="form-group required col-md-6">
                      <label class="control-label mr-5" for="street">Street:</label>
                      <label class="error float-right"><?php echo $errorStreet; ?></label>
                      <input type="text" name="street"
                             value = "<?php
                             if(isset($_SESSION['street']))
                             {
                                 echo $_SESSION['street'];
                             }
                             else
                             {
                                 echo "";
                             }
                             ?>"  class="form-control">
                  </div>
                  <div class="form-group required col-md-6">
                      <label class ="control-label" for="streetnumber">Street number:</label>
                      <label class="error float-right"><?php echo $errorStreetnumber; ?></label>
                      <input type="text" id="streetnumber" name="streetnumber"
                             value = "<?php
                             if(isset($_SESSION['streetnumber']))
                              {
                              echo $_SESSION['streetnumber'];
                              }
                             else
                              {
                              echo "";
                              }
                              ?>" class="form-control">
                  </div>
              </div>
              <div class="form-row">

                  <div class="form-group required col-md-6">
                      <label class ="control-label" for="zipcode">Zipcode:</label>
                      <label class="error float-right"><?php echo $errorZipCode; ?></label>
                      <input type="text" id="zipcode" name="zipcode"
                             value = "<?php
                             if(isset($_SESSION['zipcode']))
                             {
                                 echo $_SESSION['zipcode'];
                             }
                             else
                             {
                                 echo "";
                             }
                             ?> " class="form-control" placeholder="between 1000 and 9000">
                  </div>
                  <div class="form-group required col-md-6">
                    <label class ="control-label" for="city">City:</label>
                    <label class="error float-right"><?php echo $errorCity; ?></label>
                    <input type="text" id="city" name="city"
                           value = "<?php
                           if(isset($_SESSION['city']))
                           {
                               echo $_SESSION['city'];
                           }
                           else
                           {
                               echo "";
                           }
                           ?>" class="form-control">
                  </div>
              </div>
          </fieldset>

          <fieldset>
              <legend>Products</legend>
              <label class="error float-right"><?php echo $errorQuantity; ?></label>


              <div class="form-row">
                  <?php if($_SERVER['REQUEST_URI'] == "/Simple_order_form/index.php?food=0"):?>
                      <div class="form-group col-md-6">
                          <p class="font-weight-bold">Drinks</p>
                              <?php foreach ($drinks AS $i => $drink): ?>
                                  <label>
                                      <input type="checkbox"
                                          <?php
                                          if (isset($_SESSION['drinks'][$i])){
                                              if($i==intval($_SESSION['drinks'][$i])){
                                                  echo "checked ='checked'";
                                              }
                                              else {
                                                  echo "";}
                                          }
                                          ?>
                                             value="<?php echo $i;?>" name="drinks[<?php echo $i ?>]"/> <?php echo $drink['name'] ?> -
                                            &euro; <?php echo number_format($drink['price'], 2) ?></label><br />
                                <?php endforeach; ?>
                      </div>
                  <?php else: ($_SERVER['REQUEST_URI'] == "/Simple_order_form/index.php?food=1") ?>
                      <div class="form-group col-md-6">
                          <p class="font-weight-bold">Food</p>
                              <?php foreach ($foods AS $j => $food): ?>
                                  <label>
                                      <input type="checkbox"
                                          <?php
                                          if (isset($_SESSION['foods'][$j])){
                                              if($j==intval($_SESSION['foods'][$j])){
                                                  echo "checked ='checked'";
                                              }
                                              else {
                                                  echo "";}
                                          }
                                          ?>
                                             value="<?php echo $j;?>" name="foods[<?php echo $j ?>]"/> <?php echo $food['name'] ?> -
                                      &euro; <?php echo number_format($food['price'], 2) ?> </label> <br />
                              <?php endforeach; ?>
                      </div>
                  <?php endif; ?>


              </div>
          </fieldset>

          <fieldset>

              <legend class="mt-3">Delivery Time</legend>
              <!-- Default inline normal-->
              <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" value ="First" id="radiobutton1" name="radioButton" <?php
                      if($_SESSION['deliverySpeed'] == "Second")
                      {
                         echo ">";
                      }
                      else
                      {
                          echo "checked>";
                      }
                      ?>
                  <label class="custom-control-label" for="radiobutton1">Normal (within 2 hours)</label>
              </div>

              <!-- Default inline express-->
              <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" value="Second" id="radiobutton2" name="radioButton" <?php
                      if(($_SESSION['deliverySpeed'] == "Second"))
                      {
                          echo "checked>";
                      }
                      else
                      {
                          echo ">";
                      }
                      ?>

                  <label class="custom-control-label" for="radiobutton2">Express (within 45 minutes). <span class ="font-weight-bold"> Extra price: € 7.5</span> </label>
              </div>

          </fieldset>

          <button type="submit" name="order" class="btn btn-primary mt-3">Order! </button>

      </form>

      <?php

    /*$requiredValues = ['email','street','streetnumber','city','zipcode','products'];
    foreach($requiredValues as $field){
        if(!isset($_POST[$field]) || empty($_POST[$field])){
            $inputFieldErrors[$field] = "$field field is required";
            continue;
        }
        //sanitize strings
        $$field = (is_string($_POST[$field])) ? htmlspecialchars($_POST[$field], ENT_QUOTES, 'UTF-8') : $_POST[$field];
        if(!isset($inputFieldErrors[$field])){
            switch ($field) {
                case 'email':
                    $inputFieldErrors['email'] = (isValidEmail($email) ? false : "Invalid email");
                    break;
                case 'street':
                    $inputFieldErrors['street'] = (isValidStreet($street) ? false : "Invalid street");
                    break;
                case 'streetnumber':
                    $inputFieldErrors['streetnumber'] = (isValidStreetnumber($streetnumber) ? false : "Invalid streetnumber. Accepts Numbers Only");
                    break;
                case 'city':
                    $inputFieldErrors['city'] =  (isValidCity($city) ? false : "Invalid City");
                    break;
                case 'zipcode':
                    $inputFieldErrors['zipcode'] =  (isValidZipcode($zipcode) ? false : "Invalid Zipcode. Accepts Numbers Only");
                    break;
                case 'products':
                    $inputFieldErrors['products'] =  (isValidProducts($products) ? false : "Invalid products");
                    break;
            }
        }
    }*/

//sanitize input
    function firstSanitize($data){
        $data = trim($data);
        $data = filter_var($data, FILTER_SANITIZE_STRING);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    function testInputStreet($street){
        $firstTestInputStreet = firstSanitize($street);
        $firstTestInputStreet = filter_var($firstTestInputStreet, FILTER_SANITIZE_STRING);
        $firstTestInputStreet = ucwords(strtolower($firstTestInputStreet));
        return $firstTestInputStreet;
    }

    function testInputStreetNumber($streetNumber){
        $streetNumber = trim($streetNumber);
        $streetNumber = htmlspecialchars($streetNumber);
        $firstTestInputStreetNumber = filter_var($streetNumber, FILTER_SANITIZE_STRING);
        return $streetNumber;
    }

    function testInputCity($city){
        $city = firstSanitize($city);
        $city = ucwords(strtolower($city));
        return $city;
    }


      ?>

    <legend class="mt-3">Total</legend>
    <p>You already ordered <strong><span class = "border border-primary p-2 ">&euro; <?php
        if(isset($_SESSION['finalTotal']))
        {
            echo $_SESSION['finalTotal'];
        }
        else
        {
            echo "0";
        }
        ?></span></strong> in food and drinks.</p>

    <footer class="container mt-3">

        <!-- Grid row-->
        <div class="row py-3 d-flex align-items-center bg-primary text-light">

            <!-- Grid column -->
            <div class="col-md-6 col-lg-5 text-center text-md-left">
                <p>&copy;&nbsp;<?php echo date("Y"); ?>&nbsp;The Personal Ham Processor</p>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-6 col-lg-7 text-center text-md-right ">

              <!-- Facebook -->
                <a href="#" class ="text-light mr-4">disclaimer</a>
                <a href="#" class ="text-light mr-4">privacy policy</a>
                <a href="#" class ="text-light mr-2">cookie policy</a>
            </div>

    </footer>
</div>

<style>

</style>
</body>
</html>