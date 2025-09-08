# BPJS Framework ✨
- Update Versi 0.1.0

## Documentation ✨✨
- Untuk Dokumentasi jelas kunjungi install langsung saja lewat composer di get started ada guidenya kok
```

- Insalasi
composer create-project bpjs/bpjs nama_proyek_kamu
```

## Struktur Folder
- [app]
    - [Controllers]
    - [Exports]
    - [Handle]
        - [errors]
    - [helpers]
    - [Imports]
    - [Middleware]
    - [Models]
    - [Services]
- [bootstrap]
    - app.php
- [database]
    - [migrations]
        - .migrated.json
- [logs]
- [public]
    - [assets]
    - [css]
    - [js]
    - [stisla]
- [resources]
    - [views]
- [routes]
    - api.php
    - web.php
- [src]
    - [Core]
        - App.php
        - Kernel.php
        - Request.php
        - Response.php
- [storage]
    - [session]
- [vendor]
- .env
- .env.example
- .htacces
- bpjs
- index.php

## .env ✨
- File ini untuk mengkoneksikan ke database ya adik adik jadi untuk mengisi database nya ada disini
## Model
- Model untuk database karena basisnya seperti laravel jadi buat modelnya dulu
## public
- Didalam folder public ini diisikan asset template, gambar dan lainnya.
  cara penggunaannya simple.
  ```php
  untuk mengakses image
  <img src="<?= asset('yourasset.jpg') ?>" alt="">

  untuk menagkses template
  <link rel="stylesheet" href="<?= asset('adminlte/bootstrap.min.css') ?>">
  <script src="<?= asset('adminlte/js/bootstrap.min.js') ?>"></script>

  Menambahkan Pretty_print kalian bisa mengganti var_dump dengan menggunakan pretty_print
  vd($data);
  
  ```
  atau lainnya bisa diakses di menu public.
## Controller
- Controller Action untuk melakukan action misalkan ada kondisi dan lain sebagainya
## route
- Route adalah tujuan url yang mengarahkan ke suatu module atau view, jadi semua diarahkan melalui route bukan melalui filetujuan.php
## View
- Basic View, disini view menggunakan support View.php jadi user bisa mengembalikan atau mengarahkan ke halaman mana ajah dengan support ini misalnya.
```php
View::render('home',[],'layout'); <-- maksud dari code ini adalah kita mengarahkan kehalaman home,
[] <-- tidak membawa parameter, 'layout' <-- jika memisahkan navbar dengan content

return view('home',[],'layout'); <-- maksud dari code ini adalah kita mengarahkan kehalaman home,
[] <-- tidak membawa parameter, 'layout' <-- jika memisahkan navbar dengan content

bisa juga menggunakan
View::redirectTo('/user'); <-- fungsi ini mengarahkan ke route misalkan /mvc/product <-- akan 
mengarahkan ke route product
return redirect('/user'); <-- fungsi ini mengarahkan ke route misalkan /mvc/product <-- akan 
mengarahkan ke route product
```

Terima gajih
## Contact

- [Email](mailto:fadliazkaprayogi1@gmail.com)
- [LinkedIn](https://www.linkedin.com/in/fadli-azka-prayogi-523879176/)
