<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healminds Chatbot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 chat-sidebar d-none d-md-block">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Riwayat Chat</h5>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- <form action="buat_topik_baru.php" method="POST">
                            <button type="submit" class="btn btn-sm btn-outline-primary" title="New Chat">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </form> -->
                        <a href="chatbot.php">
                            <button type="button" class="btn btn-sm btn-outline-primary" title="New Chat">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </a>
                    <?php endif; ?>
                </div>
                <?php
                if (isset($_SESSION['user_id'])) {
                ?>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Cari chat...">
                    </div>
                <?php
                } ?>

                <div class="chat-history-wrapper">
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        include 'database/connect.php';
                        $user_id = $_SESSION['user_id'];
                        $stmt = $pdo->prepare("SELECT id, title FROM chat_topics WHERE user_id = ? ORDER BY created_at DESC");
                        $stmt->execute([$user_id]);
                        foreach ($stmt as $topic) {
                            echo '
                                    <div class="chat-item d-flex align-items-center justify-content-between px-3 py-2 mb-2 rounded bg-light shadow-sm">
                                        <a href="chatbot.php?topic_id=' . $topic['id'] . '" class="text-decoration-none text-primary fw-semibold">' . htmlspecialchars($topic['title']) . '</a>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border dropdown-toggle" type="button" id="dropdownMenuButton' . $topic['id'] . '" data-bs-toggle="dropdown" aria-expanded="false" title="Opsi">
                                                &#x22EE;
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton' . $topic['id'] . '">
                                                <li><button class="dropdown-item btn-edit" data-id="' . $topic['id'] . '"><i class="bi bi-pencil me-2"></i>Edit</button></li>
                                                <li><button class="dropdown-item btn-delete" data-id="' . $topic['id'] . '"><i class="bi bi-trash me-2"></i>Delete</button></li>
                                            </ul>
                                        </div>
                                    </div>';
                        }
                    } else {
                        echo '<div class="text-muted">Silakan login untuk melihat riwayat chat.</div>';
                    }
                    ?>
                </div>
            </div>

            <div class="col-md-9 chat-area">
                <header class="py-3 px-4 border-bottom bg-white shadow-sm d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-outline-secondary d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#chatSidebar">☰</button>
                        <h4 class="mb-0">💬 Healminds</h4>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="dropdown">
                                <img src="assets/user.png" class="avatar-top dropdown-toggle" data-bs-toggle="dropdown" alt="User">
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">Profil Saya</a></li>
                                    <li><a class="dropdown-item" href="database/logout.php">Keluar</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-outline-primary">Login</a>
                            <a href="registrasi.php" class="btn btn-primary">Daftar</a>
                        <?php endif; ?>
                    </div>
                </header>

                <main id="chat-container" class="p-3"></main>

                <footer class="chat-footer p-3 border-top">
                    <form id="chat-form" class="d-flex gap-2">
                        <input type="text" id="question" class="form-control" placeholder="Tulis perasaanmu di sini..." required>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </form>
                </footer>
            </div>
        </div>
    </div>

    <script>
        const isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
        const topicId = <?= isset($_GET['topic_id']) ? intval($_GET['topic_id']) : 'null' ?>;
        let guestChats = JSON.parse(localStorage.getItem('guestChats') || '[]');

        function renderMessages(messages) {
            const container = document.getElementById('chat-container');
            container.innerHTML = '';
            messages.forEach(msg => {
                container.innerHTML += `
        <div class="message-row ${msg.sender}">
            <div class="message-role">${msg.sender === 'user' ? 'You' : 'Bot'}</div>
            <div class="message-bubble">${msg.message}</div>
        </div>`;
            });
        }

        if (!isLoggedIn) {
            renderMessages(guestChats);
        }
        if (isLoggedIn && topicId) {
            fetch(`database/get_chat_history.php?topic_id=${topicId}`)
                .then(res => res.json())
                .then(data => {
                    renderMessages(data.messages);
                });
        }

        function storeGuestChat(question, answer) {
            const guestMessages = guestChats.filter(chat => chat.sender === 'user');

            if (guestMessages.length >= 5) {
                alert("Kamu sudah mencapai batas 5 pertanyaan sebagai guest. Silakan login untuk melanjutkan.");
                return;
            }
            guestChats.push({
                sender: 'user',
                message: question
            });
            guestChats.push({
                sender: 'bot',
                message: answer
            });
            localStorage.setItem('guestChats', JSON.stringify(guestChats));
        }

        const chatForm = document.getElementById('chat-form');
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const guestMessages = guestChats.filter(chat => chat.sender === 'user');
            if (!isLoggedIn && guestMessages.length >= 5) {
                alert("Kamu sudah mencapai batas 5 pertanyaan sebagai guest. Silakan login untuk melanjutkan.");
                return;
            }

            const input = document.getElementById('question');
            const question = input.value.trim();
            if (!question) return;

            const container = document.getElementById('chat-container');

            // Kosongkan input segera
            input.value = '';

            // Tampilkan pesan user langsung
            container.innerHTML += `
                                    <div class='message-row user'>
                                        <div class='message-role'>You</div>
                                        <div class='message-bubble'>${question}</div>
                                    </div>`;

            // Scroll ke bawah otomatis
            container.scrollTop = container.scrollHeight;

            fetch('database/proses_chatbot.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `question=${encodeURIComponent(question)}&topic_id=${topicId || ''}`
                })
                .then(res => res.json())
                .then(data => {
                    container.innerHTML += `
        <div class='message-row bot'>
            <div class='message-role'>Bot</div>
            <div class='message-bubble'>${data.response}</div>
        </div>`;

                    if (!isLoggedIn) {
                        storeGuestChat(question, data.response);
                    } else {
                        // Simpan topicId baru jika sebelumnya null
                        if (!topicId && data.topic_id) {
                            window.location.href = `chatbot.php?topic_id=${data.topic_id}`;
                        }
                    }

                    container.scrollTop = container.scrollHeight;
                })
                .catch(err => {
                    container.innerHTML += `
            <div class='message-row bot'>
                <div class='message-role'>Bot</div>
                <div class='message-bubble'>Terjadi kesalahan saat memproses pertanyaan.</div>
            </div>`;
                    console.error('Error:', err);
                });
        });

        document.addEventListener('click', function(e) {
            // DELETE TOPIC
            if (e.target.closest('.btn-delete')) {
                const topicId = e.target.closest('.btn-delete').dataset.id;
                if (confirm("Yakin ingin menghapus topik ini?")) {
                    fetch('database/delete_topic.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            topic_id: topicId
                        })
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            window.location.href = "chatbot.php"; // kembali ke halaman kosong
                        }
                    });
                }
            }

            // EDIT TOPIC TITLE
            if (e.target.closest('.btn-edit')) {
                const topicId = e.target.closest('.btn-edit').dataset.id;
                const newTitle = prompt("Masukkan judul baru:");
                if (newTitle) {
                    fetch('database/update_topic_title.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            topic_id: topicId,
                            title: newTitle
                        })
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
                }
            }
        });


        // Migrate after login
        <?php if (isset($_SESSION['user_id'])): ?>
            fetch('database/migrate_guest_chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    chats: guestChats
                })
            }).then(res => res.json()).then(data => {
                console.log('Migrasi:', data);
                localStorage.removeItem('guestChats');
                if (data.topic_id) {
                    window.location.href = `chatbot.php?topic_id=${data.topic_id}`;
                }
            });
        <?php endif; ?>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>