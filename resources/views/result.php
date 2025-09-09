<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>QRIS Payment Card</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #f0f2f5, #d9e4f5);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .card {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      padding: 25px 20px;
      width: 330px;
      text-align: center;
      transition: transform 0.2s ease-in-out;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .header-logo {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 15px;
    }
    .header-logo img {
      width: 120px;
    }
    .title {
      font-size: 18px;
      font-weight: bold;
      margin: 10px 0 5px;
      color: #444;
      letter-spacing: 0.5px;
    }
    .merchant-name {
      font-size: 14px;
      color: #777;
      margin-bottom: 15px;
    }
    hr {
      border: none;
      border-top: 1px solid #eee;
      margin: 15px 0;
    }
    .amount {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 20px;
      color: #e63946;
    }
    .qrcode {
      margin: 15px 0;
    }
    .qrcode img {
      border: 8px solid #f8f9fa;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    .btn-download {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 18px;
      background: linear-gradient(135deg, #007bff, #0056b3);
      color: #fff;
      border-radius: 10px;
      text-decoration: none;
      font-size: 14px;
      font-weight: bold;
      transition: background 0.3s ease;
    }
    .btn-download:hover {
      background: linear-gradient(135deg, #0056b3, #004080);
    }
    .btn-danger {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 18px;
      background: linear-gradient(135deg, #f20f0fff, #f51b2dff);
      color: #fff;
      border-radius: 10px;
      text-decoration: none;
      font-size: 14px;
      font-weight: bold;
      transition: background 0.3s ease;
    }
    .btn-danger:hover {
      background: linear-gradient(135deg, #f42424ff, #920114ff);
    }
  </style>
</head>
<body>
  <div class="card" id="qrisCard">
    <div class="header-logo">
      <img src="<?= asset('qris.jpg')?>" alt="Logo QRIS">
    </div>
    <div class="title">Pembayaran QRIS</div>
    <div class="merchant-name"><?= $name?></div>
    <hr>
    <div class="amount">Rp <?= number_format($amount,2,',','.')?></div>
    <div class="qrcode">
      <img src="<?= asset('barcode/').$data?>.png" alt="QRIS Code" width="200">
    </div>
    <a href="<?= url('home')?>" class="btn btn-danger">Back</a> <a href="#" class="btn-download" id="downloadCard">⬇ Download QRIS</a>
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

  <!-- Library html2canvas -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</body>
</html>
