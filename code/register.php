<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Fews-Blogs</title>
</head>
<body>
    <h1>Fews-Blogs Registration</h1>
    <p>Please fill the registration form</p> </br>
    <form action="register.php" method="post">
    Username <input type="text" name="username" required> </br>
    Password <input type="password" name="password" required> </br>
    Re-enter password <input type="password" name="password" required> </br>
    Gender
        <input type="radio" name="gender" value="female">Female
        <input type="radio" name="gender" value="male">Male
        <input type="radio" name="gender" value="cat">Cat
        <input type="radio" name="gender" value="other">Other </br>
    Select your avatar <input type="file" name="avatar" accept="image/png, image/jpeg"> </br>
    <input type="submit" value="Register">
    </form>
</body>
</html>