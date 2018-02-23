# Decode-base64-encoded-image
This is the code to decode and store any base64 encoded file  in your PHP application (here we are using base64 image string).
Usage: You have to call the getImage() function in your controller and pass data(encoded image string) to this function.

    use App\Controller\UsersController

    $imageData = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/mQll+8'  // ADD YOUR IMAGE STRING HERE
    $decodeImage = new ImageDecodeController();
    $decodeImage->saveImage($imageData); 
    
You can additionally check for valid base64 encode by adding this code in the saveImage() function.

    if (base64_decode($data, true) === false)
    {
        echo 'Not a Base64-encoded string';
    }
