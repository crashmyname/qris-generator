
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login &mdash; Qris Generator</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?= asset('stisla-1-2.2.0/dist/assets/modules/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('stisla-1-2.2.0/dist/assets/modules/fontawesome/css/all.min.css') ?>">
    <link rel="shortcut icon" href="<?= asset('qris.jpg') ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?= asset('qris.jpg') ?>" type="image/png">

  <!-- CSS Libraries -->
    <link rel="stylesheet" href="<?= asset('stisla-1-2.2.0/dist/assets/modules/bootstrap-social/bootstrap-social.css')?>">

  <!-- Template CSS -->
    <link rel="stylesheet" href="<?= asset('stisla-1-2.2.0/dist/assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('stisla-1-2.2.0/dist/assets/css/components.css') ?>">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-94034622-3');
</script>
</head>
<style>
  body{
    /* background-image: url('public/images/newebudgeting.jpg'); */
    /* background-image: url('public/images/bgebudget.jpeg'); */
    background-size: cover;
  }
</style>
<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img src="<?= asset('qris.jpg')?>" alt="logo" width="180" class="">
            </div>
            <center><h4>QRIS Generator</h4></center>
            <div class="card card-primary">
              <div class="card-header"><h4>Register</h4></div>

              <div class="card-body">
                <form method="POST" action="" id="formregister" class="needs-validation" novalidate="">
                    <?= csrf()?>
                  <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" type="text" class="form-control" name="name" id="name" tabindex="1" required autofocus>
                    <div class="invalid-feedback">
                      Please fill in name
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" type="text" class="form-control" name="username" id="username" tabindex="1" required autofocus>
                    <div class="invalid-feedback">
                      Please fill in username
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control" name="password" id="password" tabindex="2" required>
                    <div class="invalid-feedback">
                      please fill in your password
                    </div>
                  </div>
                  <div class="form-group">
                    <img src="<?= url('captcha')?>" alt="captcha" style="cursor:pointer;" id="captchaImage">
                    <input type="text" name="captcha" class="form-control col-md-5" placeholder="Masukkan captcha">
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4" id="btnregister">
                      Register
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <div class="modal" id="loadingModal" tabindex="-1" aria-hidden="true" style="background: rgba(0, 0, 0, 0.5);">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-transparent border-0 text-center">
                        <div class="spinner-border text-primary" style="width: 5rem; height: 5rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5 class="mt-3 text-white">Processing...</h5>
                    </div>
                </div>
            </div>
              <div class="simple-footer color-white">
                Copyright &copy; Stisla 2018 | Develop By : Fadli Azka Prayogi
              </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <script>
    function regenerateCaptcha() {
        let captcha = document.getElementById("captchaImage");
        captcha.src = "<?= url('captcha')?>"
    }
    document.getElementById("captchaImage").addEventListener("click", regenerateCaptcha);
    $(document).ready(function(){
        $('#btnregister').on('click', function(e){
            e.preventDefault();
            var url = '<?= url('register')?>';
            var formdata = new FormData($('#formregister')[0]);
            $('#loadingModal').modal('show');
            $.ajax({
                type: 'POST',
                url: url,
                data:formdata,
                processData:false,
                contentType:false,
                dataType: 'json',
                success:function(response){
                    if (response.status == 201) {
                        let timerInterval;
                        Swal.fire({
                            icon: 'success',
                            title: "Register Berhasil",
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                                const timer = Swal.getPopup().querySelector("b");
                                timerInterval = setInterval(() => {
                                timer.textContent = `${Swal.getTimerLeft()}`;
                                }, 100);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                            }
                        }).then((result) => {
                            window.location.href = "<?= url('login') ?>";
                        });
                    } else if(response.status === 422){
                        var errmes = ''
                            if(response.status === 422 && typeof response.message === 'object'){
                                for(var field in response.message){
                                    if(response.message.hasOwnProperty(field)){
                                        response.message[field].forEach(function(message){
                                            errmes += message + '\n'
                                        })
                                    }
                                }
                            } else {
                                errmes = 'An unexcpected error occured.'
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errmes.trim()
                            }).then((result)=>{
                              regenerateCaptcha()
                            });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Register Gagal',
                            text: response.message
                        }).then((result)=>{
                          regenerateCaptcha()
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan pada server!'
                    });
                },
                complete: function () {
                    $('#loadingModal').modal('hide');
                }
            })
        })
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- General JS Scripts -->
  <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/jquery.min.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/popper.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/tooltip.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/nicescroll/jquery.nicescroll.min.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/moment.min.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/js/stisla.js') ?>"></script>
  
  <!-- JS Libraies -->

  <!-- Page Specific JS File -->
  
  <!-- Template JS File -->
  <script src="<?= asset('stisla-1-2.2.0/dist/assets/js/scripts.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/js/custom.js') ?>"></script>
</body>
</html>