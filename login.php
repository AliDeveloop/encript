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
  </head>
  <body class="img js-fullheight" style="background-image: url(Assets/img/bg.jpg)">
    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6 col-lg-4">
            <div class="login-wrap p-0">
              <h3 class="mb-4 text-center">ورود یا ثبت نام</h3>
              <div class="form-control l1">
                <form method="Post" action="code.php" class="signin-form">
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
                    <input
                      type="text"
                      name="username"
                      class="form-control"
                      placeholder=" نام کاربری"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <input
                      id="password-field"
                      type="password"
                      name="password"
                      class="form-control"
                      placeholder="رمز عبور"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <button
                    name="btnlogin-admin"
                      type="submit"
                      class="form-control btn btn-primary submit px-3"
                    >
                      ورود
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
