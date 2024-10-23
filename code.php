<?php 
include('admin/config.php');
// **************
// ! ADMIN LOGIN
// **************
if(isset($_POST["btnlogin-admin"])){
    if(empty($_POST["username"]) || empty($_POST["password"]))
    {
       header("location:login.php?empty=1000");
       exit; 
    }
    $user=$_POST['username'];
    $pass=$_POST['password'];
    $query="SELECT * FROM `admin` WHERE username = '$user' AND password = '$pass'";
    $result=mysqli_query($link,$query);
    $row=mysqli_fetch_assoc($result);
    $rowcount=mysqli_num_rows($result);
    if($rowcount==1){
        setcookie("admin",$row['name'],time()+(86400));
        header("location:admin/result.php");
        exit;
    }
    else{
        header("location:login.php?error=1001");
        
        exit;
    }
}
// ***********************************
// signup user
// ************************************
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

// کلید و IV ثابت
$encryption_key = base64_decode('QkF4cGhJdXNtY0RhbHluVFlhN1pXQml2UG0='); // 32 بایت
$iv = base64_decode('2l4M1hD1yV+4YpH4N5bY8w=='); // 16 بایت

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // دریافت اطلاعات فرم
    $username = $_POST['username'];
    $hashed_username = hash('sha256', $username); // هش کردن نام کاربری با SHA-256
    $password = hash('sha256', $_POST['password']); // هش کردن پسورد با SHA-256
    $n_code = openssl_encrypt($_POST['n_code'], 'aes-256-cbc', $encryption_key, 0, $iv);
    $f_name = openssl_encrypt($_POST['f_name'], 'aes-256-cbc', $encryption_key, 0, $iv);
    $l_name = openssl_encrypt($_POST['l_name'], 'aes-256-cbc', $encryption_key, 0, $iv);
    
    // پردازش عکس
    $pic = $_FILES['profile-picture'];
    if ($pic['error'] === UPLOAD_ERR_OK) {
        $pic_name = uniqid() . '.' . pathinfo($pic['name'], PATHINFO_EXTENSION); // نام تصادفی
        $target_dir = 'uploads/';
        move_uploaded_file($pic['tmp_name'], $target_dir . $pic_name); // ذخیره عکس در پوشه uploads
    } else {
        die("خطا در بارگذاری تصویر: " . $pic['error']);
    }

    // ذخیره در دیتابیس
    $sql = "INSERT INTO users (username, Password, n_code, f_name, l_name, pic, iv) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    try {
        $stmt->execute([
            $hashed_username, // نام کاربری هش شده
            $password,
            base64_encode($n_code),
            base64_encode($f_name),
            base64_encode($l_name),
            $pic_name,
            base64_encode($iv)
        ]);
        header("Location: success.php");
        exit(); // برای جلوگیری از ادامه اجرای اسکریپت
    } catch (PDOException $e) {
        echo "خطا در ذخیره اطلاعات: " . $e->getMessage();
    }
}
?>




iv = 2l4M1hD1yV+4YpH4N5bY8w==
key = QkF4cGhJdXNtY0RhbHluVFlhN1pXQml2UG0=
