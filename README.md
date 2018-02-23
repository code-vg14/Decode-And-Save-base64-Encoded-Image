# Decode-base64-encoded-image
This is the code to decode and store any base64 encoded file (here we are using image) in your PHP application
Usage: You have to call the getImage() function in your controller and pass data(encoded image string) to this function.

    use App\Controller\UsersController

    $imageData = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/mQll+8'  // ADD YOUR IMAGE STRING HERE
    $decodeImage = new ImageDecodeController();
    $decodeImage->getImage($imageData); 
