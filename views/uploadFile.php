<!DOCTYPE html>
<html>
<body>

<form action="../Route.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="hidden" name="Controller" value="uploadFile">
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html>
