
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