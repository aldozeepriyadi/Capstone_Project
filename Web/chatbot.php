<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Healminds Chatbot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar Desktop -->
            <div class="col-md-3 chat-sidebar d-none d-md-block">
                <!-- Header Sidebar -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Riwayat Chat</h5>
                    <button class="btn btn-sm btn-outline-primary" title="New Chat">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" placeholder="Cari chat...">
                </div>
                <div class="chat-item">Curhat Malam</div>
                <div class="chat-item">Sesi Konseling #1</div>
                <div class="chat-item">Cemas Berlebih</div>
                <div class="chat-item">Stres Kerja</div>
                <div class="chat-item">Kehilangan Motivasi</div>
            </div>

            <!-- Chat Area -->
            <div class="col-md-9 chat-area">
                <!-- Header -->
                <header
                    class="py-3 px-4 border-bottom bg-white shadow-sm d-flex justify-content-between align-items-center">
                    <!-- Tombol menu sidebar mobile -->
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-outline-secondary d-md-none" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#chatSidebar">
                            ☰
                        </button>
                        <h4 class="mb-0">💬 Healminds</h4>
                    </div>

                    <!-- Ikon kanan atas -->
                    <div class="d-flex align-items-center gap-3">

                        <div class="dropdown">
                            <img src="assets/user.png" class="avatar-top dropdown-toggle" data-bs-toggle="dropdown"
                                alt="User">
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Profil Saya</a></li>
                                <li><a class="dropdown-item" href="#">Keluar</a></li>
                            </ul>
                        </div>
                    </div>
                </header>


                <!-- Chat Container -->
                <main id="chat-container">
                    <!-- Riwayat statis -->
                    <div class="message-row bot">
                        <div class="message-role">Bot</div>
                        <div class="message-bubble">Hai! Aku di sini untuk mendengarkanmu. Apa yang kamu rasakan hari
                            ini?</div>
                    </div>

                    <div class="message-row user">
                        <div class="message-role">You</div>
                        <div class="message-bubble">Aku merasa cemas terus-menerus tanpa sebab.</div>
                    </div>

                    <div class="message-row bot">
                        <div class="message-role">Bot</div>
                        <div class="message-bubble">Perasaan cemas bisa muncul kapan saja. Apa kamu tahu kapan terakhir
                            kamu merasa tenang?</div>
                    </div>

                    <div class="message-row user">
                        <div class="message-role">You</div>
                        <div class="message-bubble">Kayaknya udah lama banget... semenjak masalah keluarga muncul.</div>
                    </div>

                    <div class="message-row bot">
                        <div class="message-role">Bot</div>
                        <div class="message-bubble">Terima kasih telah membagikan hal itu. Aku tahu itu tidak mudah.
                            Kita bisa bicara lebih lanjut kapan pun kamu siap.</div>
                    </div>
                </main>

                <!-- Footer -->
                <footer class="chat-footer">
                    <form id="chat-form" class="d-flex gap-2">
                        <input type="text" id="user-input" class="form-control"
                            placeholder="Tulis perasaanmu di sini..." autocomplete="off" required />
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </form>
                </footer>
            </div>

        </div>
    </div>

    <!-- Offcanvas Sidebar (Mobile) -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="chatSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Riwayat Chat</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div class="chat-item">Curhat Malam</div>
            <div class="chat-item">Sesi Konseling #1</div>
            <div class="chat-item">Cemas Berlebih</div>
            <div class="chat-item">Stres Kerja</div>
            <div class="chat-item">Kehilangan Motivasi</div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>

</html>