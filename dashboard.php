<!DOCTYPE html>
<html lang="en">
  <head>
    <title>ورود یا ثبت نام</title>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link rel="stylesheet" href="assets/Style/Style.css" />
    <link
      rel="stylesheet"
      href="Assets/Style/font-awesome.min.css"
    />
  <style>
   div p{
        align:center;
    }
    img{
        border-radius: 50%;
    }
  </style>
  </head>
  <body class="img js-fullheight" style="background-image: url(Assets/img/bg.jpg)">
    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6 col-lg-4">
            <div class="login-wrap p-0">
              <h3 class="mb-4 text-center">داشبورد</h3>
              <div class="form-control l1">
                <form method="Post" action="index.php" class="signin-form">
                  <div class="form-group">
                  <P><?php
        if(isset($_GET["error"])){
            echo"<center><font color=red>نام کاربری یا رمز عبور اشتباه است</font></center>";
        }
        ?></P>
                  <div class="form-group d-md-flex">
                    <div class="w-50 text-md-left">
                      <div style="background-color: #fff;"></div>
                    </div>
                    <div class="w-50 text-md-right">
                      <a href="index.php" style="color: #fff">
                      </a>
                    </div>
                  </div>
                  <?php
session_start();

// بررسی اینکه آیا کاربر وارد شده است یا خیر
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // اگر وارد نشده باشد، به صفحه لاگین برگردد
    exit();
}

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

// گرفتن شناسه کاربر از سشن
$user_id = $_SESSION['user_id'];

// بازیابی اطلاعات کاربر از دیتابیس
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);

if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // کلید و IV ثابت (همان کلید و IV که در زمان رمزنگاری استفاده شده)
    $encryption_key = base64_decode('QkF4cGhJdXNtY0RhbHluVFlhN1pXQml2UG0='); // 32 بایت
    $iv = base64_decode('2l4M1hD1yV+4YpH4N5bY8w=='); // 16 بایت

    // رمزگشایی اطلاعات
    $n_code = openssl_decrypt(base64_decode($user['n_code']), 'aes-256-cbc', $encryption_key, 0, $iv);
    $f_name = openssl_decrypt(base64_decode($user['f_name']), 'aes-256-cbc', $encryption_key, 0, $iv);
    $l_name = openssl_decrypt(base64_decode($user['l_name']), 'aes-256-cbc', $encryption_key, 0, $iv);
    ?>
 
                  <div class="form-group">
                    <P><?php     echo "کد ملی: " . htmlspecialchars($n_code) . "<br>"; ?></P>
                  </div>
                  <div class="form-group">
                    <P><?php     echo "نام : " . htmlspecialchars($f_name) . "<br>"; ?></P>
                  </div>
                  <div class="form-group">
                    <P><?php     echo "نام خانوادگی : " . htmlspecialchars($l_name) . "<br>"; ?></P>
                  </div>
                  <div class="form-group">
                    <?php
                  $pic_path = 'uploads/' . htmlspecialchars($user['pic']);
                    echo '<p>عکس پروفایل:</p>';
                    echo "<img src='$pic_path' alt='Profile Picture' width='150' height='150' ><br>";
                   }
                   ?>
                  </div>
                  <div class="form-group">
                    <button
                    name="btnlogin-admin"
                      type="submit"
                      class="form-control btn btn-primary submit px-3"
                    >
                      خروج
                    </button>
                  </div>
                  <div class="form-group d-md-flex">
                    <div class="w-50 text-md-left">
                      <a href="index.php" style="color: #fff">
                      </a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <script src="assets/script/jquery.min.js"></script>
    <script src="assets/script/popper.js"></script>
    <script src="assets/script/bootstrap.min.js"></script>
    <script src="assets/script/main.js"></script>
  </body>
</html>
