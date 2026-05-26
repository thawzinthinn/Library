<?php

namespace App\Controller;

use App\Service\FormatService;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class SuggestController extends BaseController
{
    private FormatService $formatService;

    public function __construct(FormatService $formatService)
    {
        $this->formatService = $formatService;
    }

    public function index(): void
    {
        $this->requireLogin();
        $data = [
            'pageTitle' => "Suggest a media item",
            'section' => "suggest",
            'hideSearch' => true,
            'error_message' => null,
        ];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $formResult = $this->handleForm();

            $data = array_merge($data, $formResult);
        }

        $data['categories'] = $this->formatService->category_drop_down();
        $data['formats'] = $this->formatService->format_array();
        $data['genres'] = $this->formatService->genres_array();

        $this->view('suggest', $data);
    }

    private function handleForm(): array
    {
        $data = [
            'error_message' => null
        ];

        $name = trim($this->post('name'));
        $email = trim($this->post('email'));
        $category = trim($this->post('category'));
        $title = trim($this->post('title'));

        if (!$name || !$email || !$category || !$title) {
            $data['error_message'] =
                "Please fill required fields: Name, Email, Category, Title";
            return $data;
        }

        if (!empty($_POST['address'])) {
            $data['error_message'] = "Bad form input";
            return $data;
        }

        if (!PHPMailer::validateAddress($email)) {
            $data['error_message'] = "Invalid email address";
            return $data;
        }

        $mail = new PHPMailer(true);

        try {
            if (!empty($_ENV['MAIL_HOST']) && !empty($_ENV['MAIL_PORT'])) {
                $mail->isSMTP();
                $mail->Host = $_ENV['MAIL_HOST'];
                $mail->Port = $_ENV['MAIL_PORT'];
                $mail->SMTPAuth = true;
                $mail->Username = $_ENV['MAIL_USERNAME'];
                $mail->Password = $_ENV['MAIL_PASSWORD'];

                if ($_ENV['MAIL_PORT'] === '465') {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                } else {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                }
            } else {
                $mail->isMail();
            }

            $mail->setFrom($_ENV['MAIL_FROM_EMAIL'], $_ENV['MAIL_FROM_NAME']);
            $mail->addReplyTo($email, $name);
            $mail->addAddress($_ENV['MAIL_FROM_EMAIL']);

            $mail->Subject = "Library Suggestion from: $name";
            $mail->Body = "Suggestion received from $name";

            if ($mail->send()) {
                $this->redirect("index.php?page=suggest&status=thanks");
            }

            $data['error_message'] = $mail->ErrorInfo;
        } catch (Exception $e) {
            $data['error_message'] = $e->getMessage();
        }

        return $data;
    }
}