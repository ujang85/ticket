<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\UploadedFile;
use yii\web\Response;
use app\models\Ticket;
use app\models\TicketFile;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Url;

class RestTicketController extends Controller
{
    public $modelClass = 'app\models\Ticket';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Menambahkan autentikasi bearer token menggunakan auth_key
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];

        return $behaviors;
    }
    public function actionCreate()
{
    $request = Yii::$app->getRequest();
    $response = Yii::$app->getResponse();
    $transaction = Yii::$app->db->beginTransaction(); // Memulai transaksi database

    try {
        $ticketData = $request->getBodyParam('Ticket');
        $ticketFileData = $request->getBodyParam('TicketFile');

        $ticket = new Ticket();
        $ticket->load($ticketData, '');

        if ($ticket->save()) {
            $ticketFiles = [];

            if (!empty($ticketFileData)) {
                foreach ($ticketFileData as $fileData) {
                    $ticketFile = new TicketFile();
                    $ticketFile->ticket_id = $ticket->id;

                    // Extract file extension from the data
                    $matches = [];
                    if (preg_match('/^data:image\/([a-z]+);base64,/', $fileData['file'], $matches)) {
                        $extension = $matches[1];
                        $ticketFile->ext = $extension;
                        
                        // Generate a unique filename
                        $filename = Yii::$app->security->generateRandomString(16) . '.' . $extension;
                        
                        // Decode and save the base64 data as a file
                        $fileContent = base64_decode(str_replace('data:image/' . $extension . ';base64,', '', $fileData['file']));
                        $filePath = Yii::getAlias('@app/web/image/') . $filename;
                        
                        if (file_put_contents($filePath, $fileContent) !== false) {
                            $ticketFile->file = $filename;
                            $ticketFile->save();
                            $ticketFiles[] = [
                                'id' => $ticketFile->id,
                                'ticket_id' => $ticketFile->ticket_id,
                                // 'file' => $ticketFile->file,
                                'file' => Url::to(['image/' . $ticketFile->file], true),
                                'ext' => $ticketFile->ext,
                            ];
                        } else {
                            // Handle file saving error
                            // You can choose to rollback the transaction here if needed.
                        }
                    }
                }
            }

            $transaction->commit(); // Commit transaksi database

            $response->setStatusCode(201); // Created
            $response->format = Response::FORMAT_JSON;
            return [
                'success' => true,
                'data' => [
                    'ticket' => [
                        'id' => $ticket->id,
                        'ticket_no' => $ticket->ticket_no,
                        'description' => $ticket->description,
                        'created_at' => Yii::$app->formatter->asDateTime($ticket->created_at, 'php:Y-m-d H:i:s'),
                    ],
                    'TicketFile' => $ticketFiles,
                    'message' => 'Saving Data successfully',
                ],
            ];
        } else {
            $transaction->rollBack(); // Rollback transaksi database jika penyimpanan gagal

            $response->setStatusCode(422); // Unprocessable Entity
            $response->format = Response::FORMAT_JSON;
            return [
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $ticket->errors,
            ];
        }
    } catch (Exception $e) {
        $transaction->rollBack(); // Rollback transaksi database jika terjadi kesalahan

        $response->setStatusCode(500); // Internal Server Error
        $response->format = Response::FORMAT_JSON;
        return [
            'status' => 'error',
            'message' => 'An error occurred while processing your request.',
            'error' => $e->getMessage(),
        ];
    }
}
}
