<html>
    <body>
        <?php
        session_start();

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;



        $subject = 'Your foodorder on website "The Personal Ham Processor';
        $deliverySpeed = $_SESSION['deliverySpeed'];
        $deliveryStreet_nr = $_SESSION['street'];
        $deliveryStreetNumber= $_SESSION['streetnumber'];
        $deliveryZipcode = $_SESSION['zipcode'];
        $deliveryCity = $_SESSION['city'];
        $deliveryTime = $_SESSION["deliveryTime"];
        $Amount = $_SESSION["finalTotal"];
        $nameSender = "The Personal Ham Processor";

        $email = $_COOKIE['userEmail'];

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        require './vendor/PHPMailer/src/Exception.php';
        require './vendor/PHPMailer/src/PHPMailer.php';
        require './vendor/PHPMailer/src/SMTP.php';


        $url = 'http://localhost:8888/Simple_order_form/';

        $html = "Dear "."\n"."<html><body><br><br></body></html>".$nameSender." sends your food. This will reach you before ".$deliveryTime.
                "\n"."Total paid:. <bold>$Amount &euro;</bold>.\"<html><body><br><br></body></html>\".
                
                <html><body><br><a href=\"" . $url . "\">.Visit our website</a><br><br><em>This tool is made by ED Web&Photo STUDIO.</em></body></html>";


        /*// HTML body
        $body  = "Hello <font size=\"4\">" . $row["full_name"] . "</font>, <p>";
        $body .= "<i>Your</i> personal photograph to this message.<p>";
        $body .= "Sincerely, <br>";
        $body .= "phpmailer List manager";

        // Plain text body (for mail clients that cannot read HTML)
        $text_body  = "Hello " . $row["full_name"] . ", \n\n";
        $text_body .= "Your personal photograph to this message.\n\n";
        $text_body .= "Sincerely, \n";
        $text_body .= "phpmailer List manager";*/

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'fullstacksyntra.be';
            $mail->SMTPAuth = true;
            $mail->Username = 'syntracursisten@fullstacksyntra.be';
            $mail->Password = 'syntracursisten';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('syntracursisten@fullstacksyntra.be', 'Mailer');
            $mail->addAddress($email);
//            $mail->addAddress('danny.eeraerts@proximus.be', 'Danny Eeraerts');
            $mail->addAddress('danny.eeraerts@proximus.be');
//            $mail->addReplyTo('syntracursisten@fullstacksyntra.be', 'Syntra Cursisten');

            $mail->isHTML(true); //zorgt er voor dat $html is opgemaakt als html en niet als tekst
            $mail->Subject = $subject;
            $mail->Body = $html;
            $mail->altBody = $url;
            // embedded file
//            $mail->AddAttachment('images/card_01.jpg','card_01.jpg');

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->send();
            echo("Message is send");

        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
        header('Location: index.php');
        ?>
    </body>
</html>