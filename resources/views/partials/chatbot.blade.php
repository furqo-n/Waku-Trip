<!-- Chatbot Floating Widget -->
<div id="chatbot-widget">
    <!-- Chat Toggle Button -->
    <button id="chatbot-toggle" onclick="toggleChat()" aria-label="Open chat assistant">
        <span class="chatbot-icon-open">
            <i class="material-icons">chat</i>
        </span>
        <span class="chatbot-icon-close" style="display: none;">
            <i class="material-icons">close</i>
        </span>
        <span class="chatbot-pulse"></span>
    </button>

    <!-- Chat Window -->
    <div id="chatbot-window" style="display: none;">
        <!-- Header -->
        <div class="chatbot-header">
            <div class="d-flex align-items-center gap-3">
                <div class="chatbot-avatar">
                    <i class="material-icons" style="font-size: 22px;">smart_toy</i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-white">Waku Assistant</h6>
                    <small class="text-white-50 d-flex align-items-center gap-1">
                        <span class="chatbot-status-dot"></span>
                        Online • AI Powered
                    </small>
                </div>
            </div>
            <button class="chatbot-close-btn" onclick="toggleChat()" aria-label="Close chat">
                <i class="material-icons">close</i>
            </button>
        </div>

        <!-- Messages Area -->
        <div id="chatbot-messages" class="chatbot-messages">
            <!-- Welcome message -->
            <div class="chat-message bot-message">
                <div class="message-avatar">
                    <i class="material-icons" style="font-size: 16px;">smart_toy</i>
                </div>
                <div class="message-content">
                    <p>Konnichiwa! 🌸 I'm <strong>Waku Assistant</strong>, your Japan travel concierge.</p>
                    <p>Ask me about our trips, destinations, booking process, or anything about traveling to Japan!</p>
                </div>
            </div>

            <!-- Quick Suggestions -->
            <div class="chat-suggestions" id="chat-suggestions">
                <button class="suggestion-chip" onclick="sendSuggestion('What trips are available?')">
                    🗾 Available Trips
                </button>
                <button class="suggestion-chip" onclick="sendSuggestion('How do I book a trip?')">
                    📋 How to Book
                </button>
                <button class="suggestion-chip" onclick="sendSuggestion('Tell me about Japan visa requirements')">
                    🛂 Visa Info
                </button>
                <button class="suggestion-chip" onclick="sendSuggestion('Recommend me a trip!')">
                    ✨ Recommend Trip
                </button>
            </div>
        </div>

        <!-- Input Area -->
        <div class="chatbot-input-area">
            <form id="chatbot-form" onsubmit="sendMessage(event)">
                <div class="chatbot-input-wrapper">
                    <input type="text" id="chatbot-input" placeholder="Ask me about Japan trips..." autocomplete="off"
                        maxlength="1000" />
                    <button type="submit" id="chatbot-send" aria-label="Send message">
                        <i class="material-icons">send</i>
                    </button>
                </div>
            </form>
            <small class="chatbot-disclaimer">Powered by AI • Responses may vary</small>
        </div>
    </div>
</div>

<style>
    /* ========== Chatbot Widget Styles ========== */
    #chatbot-widget {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    }

    /* Toggle Button */
    #chatbot-toggle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #BC002D 0%, #e6003a 100%);
        border: none;
        color: white;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(188, 0, 45, 0.4);
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #chatbot-toggle:hover {
        transform: scale(1.08);
        box-shadow: 0 8px 28px rgba(188, 0, 45, 0.5);
    }

    .chatbot-icon-open i,
    .chatbot-icon-close i {
        font-size: 28px;
    }

    .chatbot-pulse {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(188, 0, 45, 0.3);
        animation: chatbotPulse 2s ease-in-out infinite;
    }

    @keyframes chatbotPulse {

        0%,
        100% {
            transform: scale(1);
            opacity: 0.5;
        }

        50% {
            transform: scale(1.3);
            opacity: 0;
        }
    }

    /* Chat Window */
    #chatbot-window {
        position: absolute;
        bottom: 76px;
        right: 0;
        width: 380px;
        max-height: 580px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.18);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        animation: chatWindowSlideIn 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes chatWindowSlideIn {
        from {
            opacity: 0;
            transform: translateY(16px) scale(0.96);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Header */
    .chatbot-header {
        background: linear-gradient(135deg, #BC002D 0%, #8b0020 100%);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chatbot-avatar {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .chatbot-status-dot {
        width: 7px;
        height: 7px;
        background: #4ade80;
        border-radius: 50%;
        display: inline-block;
        animation: statusPulse 2s ease-in-out infinite;
    }

    @keyframes statusPulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.4;
        }
    }

    .chatbot-close-btn {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
    }

    .chatbot-close-btn:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Messages */
    .chatbot-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        max-height: 380px;
        scroll-behavior: smooth;
        background: #f9fafb;
    }

    .chatbot-messages::-webkit-scrollbar {
        width: 4px;
    }

    .chatbot-messages::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }

    .chat-message {
        display: flex;
        gap: 10px;
        margin-bottom: 16px;
        animation: fadeInMessage 0.3s ease;
    }

    @keyframes fadeInMessage {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-avatar {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .bot-message .message-avatar {
        background: linear-gradient(135deg, #BC002D, #e6003a);
        color: white;
    }

    .user-message {
        flex-direction: row-reverse;
    }

    .user-message .message-avatar {
        background: #e5e7eb;
        color: #6b7280;
    }

    .message-content {
        max-width: 80%;
        padding: 10px 14px;
        border-radius: 16px;
        font-size: 13.5px;
        line-height: 1.5;
    }

    .message-content p {
        margin: 0 0 6px 0;
    }

    .message-content p:last-child {
        margin-bottom: 0;
    }

    .bot-message .message-content {
        background: #ffffff;
        color: #1f2937;
        border: 1px solid #f0f0f0;
        border-radius: 4px 16px 16px 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .user-message .message-content {
        background: linear-gradient(135deg, #BC002D, #d4003a);
        color: white;
        border-radius: 16px 4px 16px 16px;
    }

    /* ========== Recommendation Cards (Light Mode) ========== */
    .chatbot-recommendations {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 10px;
        padding-left: 38px;
        animation: fadeInMessage 0.4s ease;
    }

    .rec-card {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #ffffff;
        border-radius: 16px;
        padding: 10px 12px;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
        position: relative;
        overflow: hidden;
        border: 1px solid #ebecef;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }

    .rec-card:hover {
        background: #ffffff;
        transform: translateY(-2px);
        border-color: #BC002D;
        box-shadow: 0 6px 16px rgba(188, 0, 45, 0.1);
    }

    .rec-card-img {
        width: 58px;
        height: 58px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .rec-card-info {
        flex: 1;
        min-width: 0;
    }

    .rec-card-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #111827;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 0 0 2px 0;
        line-height: 1.3;
    }

    .rec-card-price-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .rec-card-price {
        font-size: 14px;
        font-weight: 800;
        color: #BC002D;
        margin: 0;
    }

    .rec-card-duration {
        font-size: 10px;
        color: #6b7280;
        background: #f3f4f6;
        padding: 2px 6px;
        border-radius: 5px;
        font-weight: 600;
    }

    .rec-card-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        font-size: 8px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 2px 6px;
        border-radius: 6px;
        color: white;
        z-index: 2;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .rec-badge-hot {
        background: linear-gradient(135deg, #BC002D, #ff4d4d);
    }

    .rec-badge-limited {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        color: white;
    }

    .rec-badge-top-rated {
        background: linear-gradient(135deg, #059669, #10b981);
    }

    .rec-card-arrow {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #f9fafb;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.25s ease;
        border: 1px solid #f3f4f6;
    }

    .rec-card-arrow i {
        font-size: 18px;
        color: #9ca3af;
    }

    .rec-card:hover .rec-card-arrow {
        background: #BC002D;
        border-color: #BC002D;
    }

    .rec-card:hover .rec-card-arrow i {
        color: white;
    }

    .rec-card-location {
        font-size: 11px;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 3px;
    }

    .rec-card-location i {
        font-size: 13px;
        color: #9ca3af;
    }

    .rec-card-rating {
        display: flex;
        align-items: center;
        gap: 2px;
        font-size: 11px;
        color: #f59e0b;
        margin-left: auto;
        font-weight: 600;
    }

    .rec-card-rating i {
        font-size: 13px;
    }

    .rec-card-rating span {
        color: #9ca3af;
        font-size: 10px;
        font-weight: 400;
    }

    /* Suggestions */
    .chat-suggestions {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        padding: 4px 0 8px;
    }

    .suggestion-chip {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 6px 14px;
        font-size: 12px;
        color: #374151;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
        white-space: nowrap;
    }

    .suggestion-chip:hover {
        border-color: #BC002D;
        color: #BC002D;
        background: #fff5f5;
    }

    /* Input Area */
    .chatbot-input-area {
        padding: 12px 16px 10px;
        border-top: 1px solid #f0f0f0;
        background: white;
    }

    .chatbot-input-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f3f4f6;
        border-radius: 14px;
        padding: 4px 4px 4px 16px;
        transition: all 0.2s;
        border: 2px solid transparent;
    }

    .chatbot-input-wrapper:focus-within {
        border-color: #BC002D;
        background: white;
        box-shadow: 0 0 0 3px rgba(188, 0, 45, 0.1);
    }

    #chatbot-input {
        flex: 1;
        border: none;
        outline: none;
        background: transparent;
        font-size: 13.5px;
        font-family: inherit;
        color: #1f2937;
        padding: 8px 0;
    }

    #chatbot-input::placeholder {
        color: #9ca3af;
    }

    #chatbot-send {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #BC002D, #e6003a);
        border: none;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    #chatbot-send:hover {
        transform: scale(1.05);
    }

    #chatbot-send:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    #chatbot-send i {
        font-size: 18px;
    }

    .chatbot-disclaimer {
        display: block;
        text-align: center;
        color: #9ca3af;
        font-size: 10px;
        margin-top: 6px;
    }

    /* Typing Indicator */
    .typing-indicator {
        display: flex;
        gap: 4px;
        padding: 12px 16px;
    }

    .typing-indicator span {
        width: 7px;
        height: 7px;
        background: #9ca3af;
        border-radius: 50%;
        animation: typingBounce 1.4s ease-in-out infinite;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typingBounce {

        0%,
        60%,
        100% {
            transform: translateY(0);
        }

        30% {
            transform: translateY(-8px);
        }
    }

    /* Mobile Responsive */
    @media (max-width: 480px) {
        #chatbot-window {
            width: calc(100vw - 32px);
            right: -8px;
            bottom: 70px;
            max-height: 480px;
        }

        #chatbot-widget {
            bottom: 16px;
            right: 16px;
        }

        .chatbot-recommendations {
            padding-left: 0;
        }
    }
</style>

<script>
    // Chat state
    let chatHistory = [];
    let isChatOpen = false;
    let isWaiting = false;

    function toggleChat() {
        const window_el = document.getElementById('chatbot-window');
        const iconOpen = document.querySelector('.chatbot-icon-open');
        const iconClose = document.querySelector('.chatbot-icon-close');
        const pulse = document.querySelector('.chatbot-pulse');

        isChatOpen = !isChatOpen;

        if (isChatOpen) {
            window_el.style.display = 'flex';
            iconOpen.style.display = 'none';
            iconClose.style.display = 'flex';
            pulse.style.display = 'none';
            document.getElementById('chatbot-input').focus();
        } else {
            window_el.style.display = 'none';
            iconOpen.style.display = 'flex';
            iconClose.style.display = 'none';
            pulse.style.display = 'block';
        }
    }

    function sendSuggestion(text) {
        document.getElementById('chat-suggestions').style.display = 'none';
        document.getElementById('chatbot-input').value = text;
        sendMessage(new Event('submit'));
    }

    function addMessage(content, role) {
        const messagesDiv = document.getElementById('chatbot-messages');
        const isBot = role === 'assistant';

        const messageHtml = `
            <div class="chat-message ${isBot ? 'bot-message' : 'user-message'}">
                <div class="message-avatar">
                    <i class="material-icons" style="font-size: 16px;">${isBot ? 'smart_toy' : 'person'}</i>
                </div>
                <div class="message-content">
                    <p>${formatMessage(content)}</p>
                </div>
            </div>
        `;

        messagesDiv.insertAdjacentHTML('beforeend', messageHtml);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    /**
     * Render recommendation product cards below the bot message.
     */
    function addRecommendations(recommendations) {
        if (!recommendations || recommendations.length === 0) return;

        const messagesDiv = document.getElementById('chatbot-messages');

        let cardsHtml = '<div class="chatbot-recommendations">';

        recommendations.forEach(rec => {
            // Badge HTML
            let badgeHtml = '';
            if (rec.badge) {
                let badgeClass = 'rec-badge-hot';
                if (rec.badge === 'LIMITED') badgeClass = 'rec-badge-limited';
                if (rec.badge === 'TOP RATED') badgeClass = 'rec-badge-top-rated';
                badgeHtml = `<span class="rec-card-badge ${badgeClass}">${rec.badge}</span>`;
            }

            // Rating HTML
            let ratingHtml = '';
            if (rec.rating) {
                ratingHtml = `
                    <div class="rec-card-rating">
                        <i class="material-icons">star</i>
                        ${rec.rating}
                        <span>(${rec.review_count})</span>
                    </div>
                `;
            }

            // Location HTML
            let locationHtml = '';
            if (rec.location) {
                locationHtml = `
                    <div class="rec-card-location">
                        <i class="material-icons">location_on</i>
                        ${rec.location}
                        ${ratingHtml}
                    </div>
                `;
            }

            // Duration
            let durationHtml = '';
            if (rec.duration) {
                durationHtml = `<span class="rec-card-duration">${rec.duration}</span>`;
            }

            cardsHtml += `
                <a href="${rec.url}" class="rec-card" target="_blank" rel="noopener">
                    ${badgeHtml}
                    <img src="${rec.image}" alt="${rec.title}" class="rec-card-img"
                         onerror="this.src='https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=400'">
                    <div class="rec-card-info">
                        <p class="rec-card-title">${rec.title}</p>
                        <div class="rec-card-price-row">
                            <p class="rec-card-price">${rec.price_formatted}</p>
                            ${durationHtml}
                        </div>
                        ${locationHtml}
                    </div>
                    <div class="rec-card-arrow">
                        <i class="material-icons">chevron_right</i>
                    </div>
                </a>
            `;
        });

        cardsHtml += '</div>';

        messagesDiv.insertAdjacentHTML('beforeend', cardsHtml);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    function formatMessage(text) {
        // Remove <think> tags (reasoning) from R1 models
        text = text.replace(/<think>[\s\S]*?<\/think>/g, '');
        // Convert markdown bold **text** to <strong>
        text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // Convert newlines to <br>
        text = text.replace(/\n/g, '<br>');
        // Convert bullet points
        text = text.replace(/^• /gm, '&bull; ');
        return text.trim();
    }

    function showTyping() {
        const messagesDiv = document.getElementById('chatbot-messages');
        const typingHtml = `
            <div class="chat-message bot-message" id="typing-indicator">
                <div class="message-avatar">
                    <i class="material-icons" style="font-size: 16px;">smart_toy</i>
                </div>
                <div class="message-content">
                    <div class="typing-indicator">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            </div>
        `;
        messagesDiv.insertAdjacentHTML('beforeend', typingHtml);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    function hideTyping() {
        const typing = document.getElementById('typing-indicator');
        if (typing) typing.remove();
    }

    async function sendMessage(e) {
        e.preventDefault();

        const input = document.getElementById('chatbot-input');
        const message = input.value.trim();

        if (!message || isWaiting) return;

        // Add user message
        addMessage(message, 'user');
        chatHistory.push({ role: 'user', content: message });
        input.value = '';
        isWaiting = true;
        document.getElementById('chatbot-send').disabled = true;

        // Show typing indicator
        showTyping();

        try {
            const response = await fetch('{{ route("chatbot.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    message: message,
                    history: chatHistory.slice(-10),
                }),
            });

            hideTyping();

            const data = await response.json();

            if (data.success) {
                addMessage(data.message, 'assistant');
                chatHistory.push({ role: 'assistant', content: data.message });

                // Show recommendation cards if present
                if (data.recommendations && data.recommendations.length > 0) {
                    addRecommendations(data.recommendations);
                }
            } else {
                addMessage(data.message || 'Sorry, something went wrong. Please try again.', 'assistant');
            }
        } catch (error) {
            hideTyping();
            addMessage('Gomen nasai! 🙇 Connection issue. Please check your internet and try again.', 'assistant');
        }

        isWaiting = false;
        document.getElementById('chatbot-send').disabled = false;
        input.focus();
    }
</script>