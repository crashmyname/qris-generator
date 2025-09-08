<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>QRIS Payment Card</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 20px;
      width: 300px;
      text-align: center;
    }
    .amount {
      font-size: 22px;
      font-weight: bold;
      margin-bottom: 15px;
      color: #333;
    }
    .qrcode {
      margin: 10px 0;
    }
    .btn-download {
      display: inline-block;
      margin-top: 15px;
      padding: 10px 15px;
      background: #007bff;
      color: #fff;
      border-radius: 8px;
      text-decoration: none;
      font-size: 14px;
    }
    .btn-download:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>
  <div class="card" id="qrisCard">
    <div class="amount">Rp <?= number_format($amount,2,',','.')?></div>
    <div class="qrcode">
      <!-- Ganti src dengan hasil generate php-qrcode -->
      <img src="<?= asset('barcode/').$data?>.png" alt="QRIS Code" width="200">
    </div>
    <a href="#" class="btn-download" id="downloadCard">Download</a>
  </div>

  <script>
    // Download card sebagai gambar
    document.getElementById("downloadCard").addEventListener("click", function(e){
      e.preventDefault();
      var qris = '<?= asset('barcode/').$data?>.png'
      var filename = qris.split('/').pop();
      html2canvas(document.getElementById("qrisCard")).then(function(canvas){
        let link = document.createElement("a");
        link.download = filename;
        link.href = canvas.toDataURL("image/png");
        link.click();
      });
    });
  </script>

  <!-- Tambahkan library html2canvas -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</body>
</html>
