<?php
// اتصال به دیتابیس
$host = 'localhost'; // یا آدرس سرور شما
$db_name = 'uni-project';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// کلید رمزنگاری و IV ثابت
$encryption_key = base64_decode('QkF4cGhJdXNtY0RhbHluVFlhN1pXQml2UG0='); // 32 بایت
$iv = base64_decode('2l4M1hD1yV+4YpH4N5bY8w=='); // 16 بایت

// خواندن داده‌ها از دیتابیس
$sql = "SELECT username, Password, n_code, f_name, l_name, pic FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();

// نمایش اطلاعات کاربران
echo '<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نمایش اطلاعات کاربران</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        table thead {
            background-color: #007bff;
            color: #fff;
        }
        table th, table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        table th {
            font-size: 18px;
        }
        table td img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        
        
    </style>
     <script>
        setInterval(function() {
            window.location.reload()
        }, 1000);
    </script>
</head>
<body>
    <h2>اطلاعات کاربران</h2>
    
    <table>
        <thead>
            <tr>
                <th>نام کاربری</th>
                <th>رمزعبور </th>
                <th>کد ملی</th>
                <th>نام </th>
                <th>نام خانوادگی </th>
                <th>عکس پروفایل</th>
            </tr>
        </thead>
        <tbody>';

// خواندن و نمایش اطلاعات از دیتابیس
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // رمزگشایی اطلاعات
    $n_code = openssl_decrypt(base64_decode($row['n_code']), 'aes-256-cbc', $encryption_key, 0, $iv);
    $f_name = openssl_decrypt(base64_decode($row['f_name']), 'aes-256-cbc', $encryption_key, 0, $iv);
    $l_name = openssl_decrypt(base64_decode($row['l_name']), 'aes-256-cbc', $encryption_key, 0, $iv);

    // بررسی اینکه آیا رمزگشایی موفق بوده یا خیر
    if ($n_code === false || $f_name === false || $l_name === false) {
        echo '<tr><td>' . htmlspecialchars($row['username']) . '</td><td>' . htmlspecialchars($row['Password']) . '</td><td colspan="3">خطا در رمزگشایی داده‌ها</td><td><img src="default.png" alt="Profile Picture"></td></tr>';
        continue;
    }

    // مسیر کامل تصویر
    $image_path = '../uploads/' . htmlspecialchars($row['pic']);
    
    // بررسی وجود تصویر
    if (!file_exists($image_path)) {
        $image_path = 'default.png'; // اگر تصویر وجود نداشته باشد، تصویر پیش‌فرض را بارگذاری کنید
    }
    
    // نمایش اطلاعات
    echo '<tr>
            <td>' . htmlspecialchars($row['username']) . '</td>
            <td>' . htmlspecialchars($row['Password']) . '</td>
            <td>' . htmlspecialchars($n_code) . '</td>
            <td>' . htmlspecialchars($f_name) . '</td>
            <td>' . htmlspecialchars($l_name) . '</td>
            <td><img src="' . $image_path . '" alt="Profile Picture" onerror="this.onerror=null; this.src=\'default.png\'"></td>
          </tr>';
}

echo '</tbody>
    </table>
</body>
</html>';
?>
