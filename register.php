<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Spica Admin - Register</title>
  <link rel="stylesheet" href="../assets/spica/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../assets/spica/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/spica/css/style.css">
  <link rel="shortcut icon" href="../assets/spica/images/favicon.png" />
</head>

<body>
  <div class="container-scroller d-flex">
    <div class="container-fluid page-body-wrapper full-page-wrapper d-flex">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="../assets/spica/images/logo-dark.svg" alt="logo">
              </div>
              <h4>New here?</h4>
              <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
              
              <form class="pt-3" action="proses.php" method="POST">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" id="exampleInputUsername1" placeholder="Username" name="username" required>
                </div>
                
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Email" name="email">
                </div>
                
                <div class="form-group">
                  <select class="form-control form-control-lg" id="exampleFormControlSelect2" name="country">
                    <option value="">Country</option>
                    <option value="United States of America">United States of America</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="India">India</option>
                    <option value="Germany">Germany</option>
                    <option value="Argentina">Argentina</option>
                    <option value="Dangdeur">Dangdeur</option>
                  </select>
                </div>

                <div class="form-group">
                  <select class="form-control form-control-lg" name="tipe_user" required>
                    <option value="">Pilih Tipe User</option>
                    <option value="administrator">Administrator</option>
                    <option value="customer">Customer</option>
                    <option value="suplier">Suplier</option>
                  </select>
                </div>
                
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password" name="password" required>
                </div>
                
                <div class="mb-4">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input" required>
                      I agree to all Terms & Conditions
                    </label>
                  </div>
                </div>
                
                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name="register">SIGN UP</button>
                </div>
                
                <div class="text-center mt-4 font-weight-light">
                  Already have an account? <a href="login.php" class="text-primary">Login</a>
                </div>
              </form>
              
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>
  <script src="../assets/spica/vendors/js/vendor.bundle.base.js"></script>
  <script src="../assets/spica/js/jquery.cookie.js" type="text/javascript"></script>
  <script src="../assets/spica/js/off-canvas.js"></script>
  <script src="../assets/spica/js/hoverable-collapse.js"></script>
  <script src="../assets/spica/js/template.js"></script>
  </body>

</html>