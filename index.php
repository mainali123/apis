<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>APIS</title>
</head>
<body>

<h1>Video File Upload</h1>
<form action="system_apis/video/video_api.php" method="post" enctype="multipart/form-data">
    Select video to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Video" name="submit">
</form>

</body>
</html>