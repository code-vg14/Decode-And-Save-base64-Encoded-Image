<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Response;
use Exception;

class ImageDecodeController extends AppController
{
    /**
     * BeforeFilter execution hook.
     * 
     * @param \Cake\Event\EventInterface $event An Event instance
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        // If using CakePHP Auth, allow access if necessary:
        // $this->Authentication->addUnauthenticatedActions(['saveImage']);
    }

    /**
     * Process and save base64 image data.
     *
     * @param string|null $data Raw base64 payload
     * @return \Cake\Http\Response
     */
    public function saveImage(?string $data = null): Response
    {
        $this->request->allowMethod(['post']); // Enforce secure request method

        try {
            if (empty($data)) {
                throw new BadRequestException('No image data provided.');
            }

            $imageData = $this->decodeBase64Image($data);
            
            // Securely write file using native PHP
            if (file_put_contents($imageData['path'], $imageData['decodedData']) === false) {
                throw new Exception('Failed to write image file to disk.');
            }

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'success',
                    'message' => 'Image saved successfully',
                    'path' => $imageData['imageName']
                ]));

        } catch (Exception $e) {
            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]));
        }
    }
    
    /**
     * Decode base64 and validate image integrity.
     *
     * @param string $base64String
     * @return array
     * @throws \Exception
     */
    private function decodeBase64Image(string $base64String): array
    {
        // 1. Split structural comma if it exists (e.g., data:image/png;base64,...)
        if (strpos($base64String, ',') !== false) {
            $data = explode(',', $base64String);
            $base64String = $data[1];
        }

        $decodedData = base64_decode($base64String, true);
        if (!$decodedData) {
            throw new Exception('Invalid base64 string alignment.');
        }

        // 2. Validate image structure safely using a temporary memory stream
        $imageInfo = getimagesizefromstring($decodedData);
        if (!$imageInfo) {
            throw new Exception('The provided file string is not a valid image format.');
        }

        // 3. Extrapolate mime and extension safely
        $mime = $imageInfo['mime']; 
        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp'
        ];

        if (!array_key_exists($mime, $allowedTypes)) {
            throw new Exception('Unsupported image type: ' . $mime);
        }

        $ext = $allowedTypes[$mime];
        $imageName = 'img_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $path = WWW_ROOT . 'img' . DS . $imageName;

        return [
            'decodedData' => $decodedData,
            'path' => $path,
            'imageName' => $imageName
        ];
    }
}
