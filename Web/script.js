const form = document.getElementById('chat-form');
const input = document.getElementById('user-input');
const container = document.getElementById('chat-container');

form.addEventListener('submit', function (e) {
    e.preventDefault();
    const userText = input.value.trim();
    if (userText === '') return;

    appendMessage('user', userText);
    input.value = '';

    setTimeout(() => {
        const botReply = getBotReply(userText);
        appendMessage('bot', botReply);
    }, 800);
});

function appendMessage(sender, text) {
    const row = document.createElement('div');
    row.classList.add('message-row', sender);

    const role = document.createElement('div');
    role.classList.add('message-role');
    role.textContent = sender === 'user' ? 'You' : 'Bot';

    const bubble = document.createElement('div');
    bubble.classList.add('message-bubble');
    bubble.textContent = text;

    row.appendChild(role);
    row.appendChild(bubble);
    container.appendChild(row);
    container.scrollTop = container.scrollHeight;
}

function getBotReply(text) {
    return "Aku memahami perasaanmu. Coba ceritakan lebih lanjut ya.";
}
