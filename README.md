Buku Tamu Digital
Deskripsi

Buku Tamu Digital merupakan aplikasi berbasis web yang digunakan untuk mencatat data tamu secara digital sehingga proses pencatatan menjadi lebih cepat, aman, dan mudah dikelola. Aplikasi ini dikembangkan sebagai implementasi mata kuliah DevOps dengan menerapkan kolaborasi menggunakan Git dan GitHub.

Fitur:
1.Login Admin
2.Input Data Tamu
3.Edit Data Tamu
4.Hapus Data Tamu
5.Cetak Laporan PDF
6.Export Excel
7.Log Activity
8.Profil Admin
9.Ganti Password

Teknologi:
1.PHP Native
2.MySQL
3.HTML
4.CSS
5.Bootstrap
6.JavaScript
7.Git
8.GitHub
9.GitHub Actions (CI)

Struktur Branch:
1.main
2.develop
3.feature/raja
4.feature/hotman

Workflow DevOps:
1.Planning
2.Membuat Repository GitHub
3.Membuat Branch (main, develop, feature/*)
4.Pengembangan fitur
5.Commit
6.Push
7.Pull Request
8.Code Review
9.Merge
10.Continuous Integration menggunakan GitHub Actions
11.Deployment
12.Monitoring
13.Rollback jika diperlukan

Cara Menjalankan Project:
1.Clone repository
 git clone https://github.com/USERNAME/SofDevUAS.git
2.Simpan project pada folder
 xampp/htdocs/
3.Import database ke phpMyAdmin.
4.Jalankan Apache dan MySQL melalui XAMPP.
5.Buka browser.
 http://localhost/SofDevUAS

Anggota Kelompok:
Raja Ali Haji	
Hotman Parmonangan M.

Repository:
https://github.com/rajaalihaji01/SofDevUAS

Continuous Integration:
Project ini menggunakan GitHub Actions untuk melakukan pengecekan syntax PHP secara otomatis setiap terjadi push maupun pull request ke repository.

Monitoring:
Monitoring dilakukan menggunakan fitur Log Activity yang mencatat aktivitas pengguna pada aplikasi.

Rollback:
Rollback dilakukan menggunakan Git apabila terjadi kesalahan setelah proses merge, sehingga perubahan dapat dikembalikan ke commit sebelumnya.

Lisensi:
Digunakan untuk keperluan pembelajaran pada mata kuliah DevOps.