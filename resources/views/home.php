<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title?></title>
</head>
    <link rel="stylesheet" href="<?= asset('stisla-1-2.2.0/dist/assets/modules/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('stisla-1-2.2.0/dist/assets/modules/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('stisla-1-2.2.0/dist/assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('stisla-1-2.2.0/dist/assets/css/components.css') ?>">
    <link rel="stylesheet" href="<?= asset('stisla-1-2.2.0/dist/assets/modules/chocolat/dist/css/chocolat.css') ?>">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<body>
    <div class="container">
        <hr>
        <center>
            <div class="card">
                <div class="card-header">
                <h4>QRIS Generator</h4>
                </div>
                <form action="<?= url('generate')?>" method="post" enctype="multipart/form-data">
                    <?= csrf()?>
                    <div class="card-body">
                        <div class="form-group row">
                        <label for="inputAmount" class="col-sm-3 col-form-label">Amount</label>
                        <div class="col-sm-9">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text">Rp.</span>
                                </div>
                                <input type="text" pattern="[0-9,.]*" oninput="validateNumberInput(this)" class="form-control" placeholder="jumlah" name="vamount" id="vamount">
                            </div>
                        <input type="hidden" class="form-control" placeholder="jumlah" name="amount" id="amount">
                        </div>
                    </div>
                    <div class="card-footer">       
                        <button type="submit" class="btn btn-primary" id="submit">Generate</button>
                    </div>
                </form>
            </div>
        </center>
    </div>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/popper.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/tooltip.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/nicescroll/jquery.nicescroll.min.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/moment.min.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/js/stisla.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/prism/prism.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/modules/chocolat/dist/js/jquery.chocolat.min.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/js/page/bootstrap-modal.js') ?>"></script>

    <!-- Template JS File -->
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/js/scripts.js') ?>"></script>
    <script src="<?= asset('stisla-1-2.2.0/dist/assets/js/custom.js') ?>"></script>
    <script>
        $(document).ready(function(){
            $('#vamount').on('input', function(e){
                let value = $(this).val().replace(/\D/g, '');
                    if (value) {
                        $(this).val(parseInt(value, 10).toLocaleString('id-ID'))
                    } else {
                        $(this).val('');
                    }
                return $('#amount').val(value)
            })
        })
        function validateNumberInput(input){
            input.value = input.value.replace(/[^0-9.,]/g, '');
        }
    </script>
</body>
</html>