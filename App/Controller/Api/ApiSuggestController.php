<?php

namespace App\Controller\Api;
use App\Service\FormatService;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class ApiSuggestController
{
    private FormatService $formatService;
    public function __construct(FormatService $formatService)
    {
        $this->formatService = $formatService;
    }

    public function store(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            http_response_code(405);

            echo json_encode([
                'success' => false,
                'message' => 'Method Not Allowed'
            ]);

            return;
        }

        $data = $this->validateInput();

        /*
        |--------------------------------------------------------------------------
        | Validation Failed
        |--------------------------------------------------------------------------
        */
        if (!empty($data['error_message'])) {

            http_response_code(422);

            echo json_encode([
                'success' => false,
                'message' => $data['error_message']
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Build Email Body
        |--------------------------------------------------------------------------
        */
        $email_body = "Name: {$data['name']}\n";
        $email_body .= "Email: {$data['email']}\n\n";
        $email_body .= "Category: {$data['category']}\n";
        $email_body .= "Title: {$data['title']}\n";
        $email_body .= "Format: {$data['format']}\n";
        $email_body .= "Genre: {$data['genre']}\n";
        $email_body .= "Year: {$data['year']}\n";
        $email_body .= "Details:\n{$data['details']}\n";

        /*
        |--------------------------------------------------------------------------
        | Send Mail
        |--------------------------------------------------------------------------
        */
        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();

            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->Port = $_ENV['MAIL_PORT'];

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->SMTPAuth = true;

            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];

            $mail->setFrom(
                $_ENV['MAIL_FROM_EMAIL'],
                $_ENV['MAIL_FROM_NAME']
            );

            $mail->addReplyTo(
                $data['email'],
                $data['name']
            );

            $mail->addAddress($_ENV['MAIL_FROM_EMAIL']);

            $mail->Subject =
                'Library Suggestion from: ' . $data['name'];

            $mail->Body = $email_body;

            $mail->send();

            echo json_encode([
                'success' => true,
                'message' => 'Suggestion sent successfully'
            ]);

        } catch (Exception $e) {

            http_response_code(500);

            echo json_encode([
                'success' => false,
                'message' => $mail->ErrorInfo
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */
    private function validateInput(): array
    {
        $data = [

            'name' => trim(
                filter_input(
                    INPUT_POST,
                    'name',
                    FILTER_SANITIZE_SPECIAL_CHARS
                )
            ),

            'email' => trim(
                filter_input(
                    INPUT_POST,
                    'email',
                    FILTER_SANITIZE_EMAIL
                )
            ),

            'category' => trim(
                filter_input(
                    INPUT_POST,
                    'category',
                    FILTER_SANITIZE_SPECIAL_CHARS
                )
            ),

            'title' => trim(
                filter_input(
                    INPUT_POST,
                    'title',
                    FILTER_SANITIZE_SPECIAL_CHARS
                )
            ),

            'format' => trim(
                filter_input(
                    INPUT_POST,
                    'format',
                    FILTER_SANITIZE_SPECIAL_CHARS
                )
            ),

            'genre' => trim(
                filter_input(
                    INPUT_POST,
                    'genre',
                    FILTER_SANITIZE_SPECIAL_CHARS
                )
            ),

            'year' => trim(
                filter_input(
                    INPUT_POST,
                    'year',
                    FILTER_SANITIZE_NUMBER_INT
                )
            ),

            'details' => trim(
                filter_input(
                    INPUT_POST,
                    'details',
                    FILTER_SANITIZE_SPECIAL_CHARS
                )
            ),

            'error_message' => null
        ];

        /*
        |--------------------------------------------------------------------------
        | Required Fields
        |--------------------------------------------------------------------------
        */
        if (
            empty($data['name']) ||
            empty($data['email']) ||
            empty($data['category']) ||
            empty($data['title'])
        ) {

            $data['error_message'] =
                'Required fields are missing';

            return $data;
        }

        /*
        |--------------------------------------------------------------------------
        | Honeypot
        |--------------------------------------------------------------------------
        */
        if (!empty($_POST['address'])) {

            $data['error_message'] =
                'Spam detected';

            return $data;
        }

        /*
        |--------------------------------------------------------------------------
        | Email Validation
        |--------------------------------------------------------------------------
        */
        if (!PHPMailer::validateAddress($data['email'])) {

            $data['error_message'] =
                'Invalid email address';

            return $data;
        }

        return $data;
    }
}