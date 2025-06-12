
# HealMinds – Aplikasi Chatbot untuk Dukungan Kesehatan Mental

## Informasi mengenai aplikasi
### Deskripsi Aplikasi
HealMinds merupakan aplikasi chatbot berbasis Artificial Intelligence yang dirancang untuk memberikan dukungan psikologis awal bagi individu yang mengalami stres, kecemasan, atau masalah emosional lainnya. Aplikasi ini bertujuan memberikan akses cepat, nyaman, dan empatik dalam bentuk percakapan dua arah tanpa menyudutkan pengguna.

### Tujuan Aplikasi
Aplikasi HealMinds ini memiliki beberapa tujuan antara lain : 
- Memberikan tempat bagi pengguna untuk mengekspresikan perasaan mereka sehingga pengguna merasa tidak takut untuk dijugde
- Mengurangi masalah akibat stigma masyarakat. 
- Menyediakan layanan terkait kesehatan mental melalui teknologi yang dapat diakses 24/7.
- Menyediakan aplikasi chatbot berbasis AI yang responsif dan tentunya empatik serta tidak menyudutkan pengguna atas pesan yang dikirimkan

### Tim Pengembang
Dalam pembuatan aplikasi HealMinds tentu terdapat tim pengembangnya. Tim pengembang dari aplikasi ini antara lain : 
- Aldo Fernando Supriyadi – Politeknik Astra
- Putri Oktavianti – Universitas Lambung Mangkurat
- Agustinus Alvin Wicaksono – Universitas Semarang
- Rizki Wahyu Nurcahyani Fajarwati – Politeknik Elektronika Negeri Surabaya

### Teknologi yang Digunakan
- Machine Learning: LSTM (Long Short-Term Memory), Gemini API  
- Bahasa Pemrograman: Python, PHP.
- Library: Pandas, Numpy, Os, Matplotlib, TensorFlow Keras, pickle, BaseModel, nltk, deep_translator, ChatGoogleGenerativeAI, PromptTemplate, retreiver, FastAPI. 
- Web Tools: HTML, CSS, JavaScript, Bootstrap, XAMPP, Postman. 
- Database: MySQL.
- Lainnya: Visual Studio Code, Google Colab, Git/Github, Google Cloud Platform(Cloud Storage, Cloud Run / App Engine).

### Arsitektur Chatbot
- Menggunakan LSTM untuk memproses konteks percakapan pengguna.
- Menggunakan campuran API dari GeminiAPI untuk mendapatkan respon yang lebih empatik dan tidak memberikan respon yang menyudutkan pengguna. 
- Model belajar dari dataset percakapan seputar isu kesehatan mental.
- Chatbot memberikan respon berbasis teks yang empatik dan tidak menghakimi.

### Batasan dan Risiko
- Akurasi model di bawah 80% bisa mempengaruhi kualitas respon.
- Model mengalami kesulitan mengenali bahasa gaul atau singkatan.
- Potensi keterlambatan respons pada integrasi website.
- Pembuatan dan hasil model bergantung pada kualitas dataset.

### Cara mengakses website
untuk mengakses website HealMinds dapat menggunakan 2 cara yaitu menggunakan server yang sudah dideploy atau menggunakan lokal

Menggunakan server yang sudah dideploy
- masuk ke halaman landing page
    ```
    http://103.82.92.213/Capstone_Project/Web/landing_page.php
    ```
- klik button mulai ngobrol dan mulai konsultasi gratis nanti akan diarahkan ke halaman chatbot dengan mode guest. 
- klik button login/register kemudian berikan informasi yang diminta lalu nanti akan diarahkan ke halaman chatbot yang berisi riwayat obrolan yang pernah digunakan. 

Menggunakan cara lokal
- jalankan perintah dibawah ini untuk membuat endpoint yang nantinya akan digunakan disisi frontend. 
    ```
    python main.py
    ```
- dari sisi website, jalankan XAMPP bagian apache dan MySQL
- kemudian buka di localhost sesuai folder yang ada di htdocs tempat menyimpan. 



### Dokumentasi website
#### Halaman Landing

![Image](https://github.com/user-attachments/assets/41d4ac9f-5a46-480d-a8c2-b7548a2d13ed)

#### Halaman Login

![Image](https://github.com/user-attachments/assets/57ee9ca5-8cb6-482a-bc14-083d63b42c71)

#### Halaman Register

![Image](https://github.com/user-attachments/assets/929cd61a-557e-4323-9989-0da549ce80b7)

#### Halaman Chatbot (pengguna belum login)

![Image](https://github.com/user-attachments/assets/e22c6ac5-8737-478a-afec-ddb9c38454b6)

#### Halaman Chatbot (pengguna sudah login)
![Image](https://github.com/user-attachments/assets/12efaa45-282c-42f0-b323-6c34a86f9db4)

#### Halaman Chatbot (Percakapan pengguna)

![Image](https://github.com/user-attachments/assets/da126ced-9b91-433f-a66e-46629151ad1b)
## Struktur Proyek
- **Folder Deep Learning**

    Sebelum menjalankan proyek dengan folder ini, dapat menjalankan perintah untuk install requirements yang nantinya akan digunakan untuk file yang ada difolder ini untuk mencegah terjadinya error dan versi yang berbeda. Kode untuk menginstall antara lain:
    ```
    pip install -r requirements.txt

    ```
    namun sebelumnya pastikan bahwa sudah berada difolder yang terdapat file requirements.txt

    - **app.py** ➜ File ini merupakan bagian backend API untuk membuat chatbot kesehatan mental yang menggunakan FastAPI dan model LSTM untuk mengklasifikasikan isi pesan pengguna berdasarkan label yang didapat dari dataset.  Selain itu pada file ini digunakan untuk preprocessing teks lengkap dengan penerjemahan otomatis seperti google translator dan lemmatization. kemudian terdapat klasifikasi menggunakan model LSTM. Terakhir, API endpoint POST untuk menerima input teks dan mengembalikan label klasifikasi.
        
        untuk menjalankan kode ini dapat menggunakan perintah : 
        ```
        python app.py
        ```
    - **chatbot.py** ➜ File ini merupakan bagian backend FastAPI yang menyediakan endpoint chatbot berbasis Gemini 2.0 Flash dari Google, dengan mengambil model gemini dan menghubungkan dengan google api key dan dilengkapi dengan fitur seperti respon yang lebih empatik dan memiliki template khusus untuk topik kesehatan mental sehingga ketika pengguna mengisikan diluar topik kesehatan mental maka sistem akan secara otomatis mengeluarkan respon jika tidak bisa memproses percakapan tersebut. kemudian ada fitur yang menggunakan context-aware prompt chaining yang diperkuat dengan basis pengetahuan dari vector search (retreiver). Ada fitur utamanya yaitu file ini dapat mendeteksi emosi/status dominan dari konteks yang relevan
     
        untuk menjalankan kode ini dapat menggunakan perintah : 
        ```
        python chatbot.py
        ```
    - **main.py** ➜ File ini berfungsi sebagai router utama untuk API HealMinds. File ini  menggabungkan app.py dan chatbot.py kemudian memberikan endpoint untuk nantinya dapat digunakan disisi frontendnya atau sisi websitenya. 
         
        untuk menjalankan kode ini dapat menggunakan perintah : 
        ```
        python main.py
        ```
    - **vector.py** ➜ Kode ini berfungsi untuk membangun sistem vector retriever untuk chatbot kesehatan mental dengan cara memuat dataset berisi pernyataan dan status emosional pengguna, menghapus data kosong, dan mengubah 500 sampel menjadi dokumen embedding menggunakan model multilingual dari sentence-transformers. Selanjutnya, dokumen-dokumen tersebut disimpan ke dalam vectorstore Chroma, dan diinisialisasi menjadi retriever yang dapat digunakan untuk mengambil konteks relevan saat chatbot menjawab pertanyaan pengguna secara empatik dan kontekstual.
         
        untuk menjalankan kode ini dapat menggunakan perintah : 
        ```
        python vector.py
        ```
- **Folder Web**
    - **Folder Database**
        - **connect.php** ➜ Kode PHP pada file ini digunakan untuk melakukan koneksi ke database MySQL bernama `projek_capstonelaskarai` di server lokal menggunakan PDO (PHP Data Objects). Koneksi ini menggunakan username `root` tanpa password. Jika koneksi berhasil, objek `$pdo` akan tersedia untuk menjalankan query ke database. Jika gagal, program akan dihentikan dan menampilkan pesan kesalahan koneksi. 
        - **proses_login.php** ➜ File ini berfungsi untuk memproses login pengguna menggunakan username atau email sebagai identitas yang dibutuhkan dan juga mengisikan password sesuai dengan yang telah diregistrasi sebelumnya. Sistem memeriksa apakah pengguna ada di database. Jika login berhasil, sesi pengguna dibuat dan diarahkan ke halaman `chatbot.php`. Jika gagal (password salah atau pengguna tidak ditemukan), pengguna diarahkan kembali ke halaman login dengan pesan kesalahan. 
        - **proses_register.php** ➜ File ini berfungsi untuk pendaftaran (registrasi) pengguna baru. Sistem akan memeriksa terlebih dahulu apakah email atau username sudah digunakan, lalu jika belum, akan menyimpan data pengguna ke tabel `users`. Jika berhasil, pengguna diarahkan ke halaman login dengan pesan sukses.
        - **proses_chatbot.php** ➜ File ini berfungsi sebagai jembatan antara frontend dan backend chatbot FastAPI. Saat pengguna mengirim pertanyaan, file ini akan meneruskan pertanyaan tersebut ke endpoint chatbot lokal (`http://127.0.0.1:8080/chat/`), lalu menerima dan memproses respons dari chatbot. Sistem mengembalikan jawaban chatbot ke frontend dalam format JSON.
        - **get_chat_history.php** ➜ File PHP ini digunakan untuk mengambil dan menampilkan daftar pesan dari suatu topik chat tertentu milik pengguna yang sedang login sesuai dengan ID yang diambil. Prosesnya dimulai dengan memeriksa apakah sesi `user_id` dan parameter `topic_id` tersedia. Jika ya, maka sistem akan melakukan query ke database untuk mengambil semua pesan (`sender` dan `message`) dari tabel `messages` berdasarkan `topic_id` dan `user_id`. Hasilnya dikembalikan dalam format JSON. 
        - **migrate_guest_chat.php** ➜ File ini berfungsi untuk memigrasikan riwayat chat guest(user yang belum login) ke akun pengguna setelah login, dengan cara menyimpan seluruh percakapan guest ke dalam database sebagai topik baru di tabel `chat_topics` dan `messages`. Setiap chat disimpan satu per satu ke dalam database dengan mencatat pengirimnya (user atau bot). Jika berhasil, sistem akan mengembalikan respons JSON berisi pesan sukses dan ID topik baru.
        - **logout.php** ➜ File PHP ini berfungsi untuk logout pengguna dari aplikasi. Dengan menggunakan session_destroy() berarti menghapus semua data sesi (mengeluarkan pengguna) sehingga pengguna tidak akan lagi dianggap login di sistem dan harus masuk kembali untuk mengakses fitur yang memerlukan autentikasi.
        - **update_topic_title.php** ➜ File ini berfungsi untuk mengubah judul topik chat milik pengguna yang sedang login. Sistem memeriksa dan menerima data `topic_id` dan `title` dari input JSON. Jika data valid, judul topik yang sesuai `topic_id` dan milik `user_id` tersebut akan diperbarui di database (`chat_topics`). Jika data tidak lengkap atau tidak ada sesi login, akan dikembalikan respons error.
        - **delete_topic.php** ➜ File PHP ini berfungsi untuk menghapus topik chat beserta seluruh pesan dengan id yang terkait dari database, tetapi hanya jika pengguna sudah login. Menggunakan pengecekan sesi untuk memastikan pengguna sudah login. 
    - **login.php** ➜ Halaman ini adalah tampilan frontend website aplikasi HealMinds bagian login, yang dirancang dengan Bootstrap. Halaman ini terdiri dari form login yang meminta email/username dan password. Formulir ini akan mengirim data ke `database/proses_login.php` untuk diproses dan jika berhasil akan dialihkan ke halaman chatbot. 
    - **registrasi.php** ➜ Halaman ini adalah tampilan frontend website aplikasi HealMinds bagian registrasi, yang dirancang dengan Bootstrap. Halaman ini terdiri dari form registrasi yang meminta email/username dan password. Formulir ini akan mengirim data ke `database/proses_register.php` untuk diproses dan jika berhasil akan dialihkan ke halaman login.php.  
    - **landing_page.php** ➜ Halaman HTML ini adalah landing page utama untuk aplikasi Healminds. Halaman ini menyajikan informasi umum seperti fitur utama (privasi, AI empatik, akses 24/7), ajakan untuk memulai percakapan, dan pengenalan tim developer. Halaman ini juga menyediakan navigasi ke halaman login dan registrasi, serta tombol aksi untuk memulai konsultasi secara langsung melalui chatbot.
    - **chatbot.php** ➜ Halaman ini adalah frontend website utama chatbot Healminds, tempat pengguna berinteraksi langsung dengan chatbot AI. Jika pengguna sudah login, mereka bisa melihat dan mengelola riwayat percakapan (melihat, membuat, mengedit, menghapus topik baru), serta semua chat disimpan ke database. Jika pengguna belum login (guest), mereka tetap bisa chat maksimal 5 kali, dan riwayatnya disimpan di `localStorage`. Setelah login, riwayat guest akan dimigrasikan secara otomatis ke akun.
    - **404.php** ➜ Halaman ini adalah tampilan kustom untuk error 404 (Halaman Tidak Ditemukan) pada aplikasi Healminds. Menyediakan tombol untuk kembali ke halaman beranda (`landing_page.html`). 
