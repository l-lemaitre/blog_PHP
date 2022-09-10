<?php
    namespace App\Classes\Controllers\Public;

    use App\Classes\Controllers\Controller;
    use App\Classes\Models\PostRepository;
    use App\Classes\Services\Mail;
    use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

    class HomeController extends Controller {
        public function displayHomePage() {
            if(isset($_GET["reply"])) {
                $reply = filter_input(INPUT_GET, 'reply', FILTER_SANITIZE_URL);

                $reply = htmlspecialchars($reply);
            } else {
                $reply = false;
            }

            $this->render('../views/templates/front',
                'index.html.twig',
                ['reply' => $reply]
            );
        }

        public function renderFormDownloadCV() {
            if (isset($_POST["cv_download_link"])) {
                $cv = __DIR__.'/../../../public/files/CV_Ludovic_LEMAITRE_2022.pdf';

                if (file_exists($cv)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="'.basename($cv).'"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($cv));
                    readfile($cv);
                }
            }
        }

        public function renderContactForm() {
            // If the "message" POST variable is declared and different from NULL
            if (isset($_POST["message"])) {
                $args = [
                    'firstname' => FILTER_SANITIZE_STRING,
                    'lastname' => FILTER_SANITIZE_STRING,
                    'email' => FILTER_SANITIZE_STRING,
                    'subject' => FILTER_SANITIZE_STRING,
                    'contents' => FILTER_SANITIZE_STRING
                ];

                $formInputs = filter_input_array(INPUT_POST, $args);

                $firstname = htmlspecialchars(trim($formInputs["firstname"])); // We get the firstname
                $lastname = htmlspecialchars(trim($formInputs["lastname"])); // We get the lastname
                $email = htmlspecialchars(strtolower(trim($formInputs["email"]))); // We retrieve the email
                $subject = strip_tags(trim($formInputs["subject"])); // We retrieve the subject of the message
                $contents = strip_tags(trim($formInputs["contents"])); // We retrieve the contents of the message
                $valid = true;
                $errors = [];

                // Check first and last name
                if (empty($firstname) || empty($lastname)) {
                    $valid = false;
                    $errors['emptyName'] = "Le \"Prénom\" et/ou le \"Nom\" ne peuvent être vide.";
                }
                // Check that the first and last name are in the correct format
                elseif (!preg_match("/^[A-Za-zàäâçéèëêïîöôùüû\s_-]{2,}$/", $firstname) || !preg_match("/^[A-Za-zàäâçéèëêïîöôùüû\s_-]{2,}$/", $lastname)) {
                    $valid = false;
                    $errors['invalidName'] = "Le \"Prénom\" et/ou le \"Nom\" doivent contenir au moins 2 caractères et ne pas comporter de caractères spéciaux.";
                }

                // Verification of the e-mail address
                if (empty($email)) {
                    $valid = false;
                    $errors['emptyMail'] = "L'adresse \"E-mail\" ne peut être vide.";
                }
                // Check that the email address is in the correct format
                elseif (!preg_match("/^[0-9a-z\-_.]+@[0-9a-z]+\.[a-z]{2,3}$/i", $email)) {
                    $valid = false;
                    $errors['invalidMail'] = "L'adresse \"E-mail\" n'est pas valide.";
                }

                // Check message subject
                if (empty($subject)) {
                    $valid = false;
                    $errors['emptySubject'] = "Le \"Sujet\" du message ne peut être vide.";
                }

                // Check message contents
                if (empty($contents)) {
                    $valid = false;
                    $errors['emptyContents'] = "Le champ de saisie \"Votre message\" ne peut être vide.";
                }

                // If all the conditions are met then we move on to processing
                if ($valid) {
                    date_default_timezone_set("Europe/Paris");

                    $emailSender = $email;
                    $emailRecipient = "contact@llemaitre.com";

                    // Add the message in HTML format
                    $message = "<!DOCTYPE html><html lang=\"fr\"><body>
                        <h2 style=\"color: #6610f2;\">Message envoyé depuis le formulaire de contact du blog <a href=\"http://127.0.0.1/blog_php\" style=\"color: #6610f2; text-decoration:none;\">llemaitre.com</a></h2>"
                        . nl2br("<p><b>Prénom : </b>" . $firstname . "
                           <b>Nom : </b>" . $lastname . "
                           <b>E-mail : </b>" . $email . "
                           <b>Date : </b>" . date("d-m-Y H:i:s") . "
                           <b>Sujet : </b>" . $subject . "
                           <b>Message : </b>" . $contents . "</p>
                           </body></html>");

                    $sendMail = Mail::send($emailSender, $emailRecipient, $subject, $message);

                    try {
                        $sendMail;
                    } catch (TransportExceptionInterface $e) {
                        header("location:index?reply=error#containerContact");
                    }

                    header("location:index?reply=ok#containerContact");
                } else {
                    $this->render('../views/templates/front',
                        'index.html.twig',
                        ['firstname' => $firstname,
                        'lastname' => $lastname,
                        'email' => $email,
                        'subject' => $subject,
                        'contents' => $contents,
                        'errors' => $errors]
                    );
                }
            }
        }
    }
