<?php
session_start();
$host = 'localhost';
$db_name = 'uni-project';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // دریافت نام کاربری و رمز عبور از فرم
    $user_input = $_POST['user-name'];
    $pass_input = $_POST['pass-word'];

    // هش کردن نام کاربری و پسورد ورودی برای مقایسه با دیتابیس
    $hashed_username_input = hash('sha256', $user_input);
    $hashed_password_input = hash('sha256', $pass_input);

    // جستجو در دیتابیس برای یافتن کاربری با این نام کاربری و رمز عبور
    $sql = "SELECT * FROM users WHERE username = ? AND Password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$hashed_username_input, $hashed_password_input]);
    
    if ($stmt->rowCount() > 0) {
        // اگر کاربر وجود داشت، انتقال به صفحه بعد
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $user['id']; // ذخیره شناسه کاربر در جلسه
        header("Location: dashboard.php"); // انتقال به داشبورد
        exit();
    } else {
        // اگر کاربر وجود نداشت، بازگشت به صفحه قبلی با پیغام خطا
        header("Location: success.php?error=true"); // خطا: نام کاربری یا رمز عبور اشتباه است
        exit();
    }
}
?>
