
    <div class="container">
        <hr>
        <center>
            <div class="card">
                <div class="card-header">
                <h4>QRIS Decoder</h4>
                </div>
                <?php if($data):?>
                <p class="text-muted"><?= $data?></p>
                <?php endif;?>
                <form action="" id="formqris" method="post" enctype="multipart/form-data">
                    <?= csrf()?>
                    <div class="card-body">
                        <div class="form-group row">
                        <label for="inputImage" class="col-sm-3 col-form-label">Upload File Qris</label>
                        <input type="file" class="form-control" name="image" id="image">
                    </div>
                    <div class="card-footer">       
                        <button type="submit" class="btn btn-primary" id="submit">Generate</button>
                        <button class="btn disabled btn-primary" id="loading" disabled style="display:none">loading..</button>
                    </div>
                </form>
            </div>
        </center>
    </div>
    <script>
        $(document).ready(function(){
            $('#submit').on('click', function(e){
                e.preventDefault()
                var data = new FormData($('#formqris')[0])
                $('#loading').show()
                $('#submit').hide()
                $.ajax({
                    type: 'POST',
                    url: '<?= url('decoder')?>',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN' : '<?= csrfHeader()?>'
                    },
                    processData: false,
                    contentType: false,
                    success: function(res){
                        if(res.status === 200){
                            $('#loading').hide()
                            $('#submit').show()
                            Swal.fire({
                                icon: 'success',
                                title: 'success',
                                text: res.message
                            }).then((result)=>{
                                window.location.reload()
                            })
                        } else {
                            $('#loading').hide()
                            $('#submit').show()
                            Swal.fire({
                                icon: 'error',
                                title: 'error',
                                text: res.message
                            })
                        }
                    }
                })
            })
        })
    </script>