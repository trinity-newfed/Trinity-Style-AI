<style>
    .chat-bubble-user { background: #3b82f6; align-self: flex-end; border-radius: 12px 12px 0 12px; padding: 8px 12px; margin-bottom: 8px; color: white; width: fit-content; max-width: 80%; }
    .chat-bubble-bot { background: #2a2a2a; align-self: flex-start; border-radius: 12px 12px 12px 0; padding: 8px 12px; margin-bottom: 8px; color: #ddd; width: fit-content; max-width: 80%; }
</style> 

<section
    class="py-24 px-6 md:px-16 max-w-7xl mx-auto border-t border-neutral-100 grid grid-cols-1 md:grid-cols-3 gap-12 text-left">
    <div class="space-y-3 reveal-target">
        <h5 class="text-[11px] tracking-widest uppercase font-medium reveal-fade">01 / Limited Archive</h5>
        <p class="text-xs text-neutral-400 font-light leading-relaxed reveal-fade" style="transition-delay: 100ms;">Each
            piece is individually numbered to ensure its exclusivity and will not be reproduced, preserving its
            uniqueness.</p>
    </div>
    <div class="space-y-3 reveal-target">
        <h5 class="text-[11px] tracking-widest uppercase font-medium reveal-fade">02 / Minimal Packaging</h5>
        <p class="text-xs text-neutral-400 font-light leading-relaxed reveal-fade" style="transition-delay: 100ms;">
            Encased in raw, recycled artisanal paper, the packaging is finished in a timeless, original stone-gray
            tone—a testament to understated luxury.</p>
    </div>
    <div class="space-y-3 reveal-target">
        <h5 class="text-[11px] tracking-widest uppercase font-medium reveal-fade">03 / Global Courier</h5>
        <p class="text-xs text-neutral-400 font-light leading-relaxed reveal-fade" style="transition-delay: 100ms;">We
            offer expedited shipping and a transparent, 7-day door-to-door return policy.</p>
    </div>
</section>

<footer class="bg-black text-white px-6 md:px-16 py-24 grid grid-cols-1 md:grid-cols-12 gap-12 items-end">
    <div class="md:col-span-5 space-y-4">
        <h6 class="text-4xl font-extralight tracking-[0.2em] uppercase">TRINITY</h6>
        <p class="text-[9px] tracking-widest text-neutral-500 font-mono uppercase">ESSENCE OVER EXCESS. EST 2026</p>
    </div>
    <div
        class="md:col-span-7 grid grid-cols-2 md:grid-cols-3 gap-8 text-[10px] tracking-[0.2em] uppercase font-light text-neutral-400">
        <div class="flex flex-col space-y-3">
            <span class="text-neutral-600 font-medium">Quick Link</span>
            <a href="voucher.php" class="hover:text-white transition-colors">Exclusive Offers</a>
            <a href="userTier.php" class="hover:text-white transition-colors">Membership Status</a>
        </div>
        <div class="flex flex-col space-y-3">
            <span class="text-neutral-600 font-medium">Customer Service</span>
            <a href="../legal/delivery-policy.php" class="hover:text-white transition-colors">Shipping & Returns</a>
            <a href="../legal/privacy-policy.php" class="hover:text-white transition-colors">Privacy Policy</a>
            <a href="../legal/ai-usage-policy.php" class="hover:text-white transition-colors">AI Usage Policy</a>
            <a href="../legal/term-of-service.php" class="hover:text-white transition-colors">Terms of Service</a>
        </div>
        <div class="flex flex-col space-y-3 col-span-2 md:col-span-1">
            <span class="text-neutral-600 font-medium">Contact & Support</span>
            <p class="font-mono text-[9px] text-neutral-500">triple3tbusiness@gmail.com</p>
            <p class="font-mono text-[9px] text-neutral-500 cursor-pointer hover:text-white" id="chat"
                onclick="toggleChat()">Live
                Chat</p>
        </div>
    </div>
</footer>

<div class="fixed bottom-6 right-6 z-50">

    <div id="chatWindow"
        class="hidden fixed bottom-0 right-0 sm:bottom-4 sm:right-4 w-full h-[90%] sm:w-80 sm:h-96 bg-[#1a1a1a] border-t sm:border border-white/10 rounded-t-2xl sm:rounded-2xl shadow-2xl flex flex-col overflow-hidden transition-all duration-300 z-1001">

        <div
            class="p-4 border-b border-white/10 text-white font-light text-sm uppercase tracking-widest flex justify-between items-center">
            <span>Trinity Support</span>
            <button class="cursor-pointer" onclick="closeChat()">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffff"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
            </button>
        </div>

        <div class="flex-1 p-4 overflow-y-auto text-white/60 text-xs font-light custom-scrollbar">
            <p class="mb-2">How can we assist your journey today?</p>
        </div>

        <div class="p-3 border-t border-white/10 bg-[#1a1a1a] pb-safe">
            <input type="text" placeholder="Type a message..."
                class="w-full bg-transparent border-none text-white text-sm sm:text-xs placeholder-white/30 focus:ring-0 outline-none py-2">
        </div>
    </div>
</div>

<script>
    function toggleChat() {
        const window = document.getElementById('chatWindow');
        window.classList.toggle('hidden');
    }

    function closeChat(){
        const window = document.getElementById('chatWindow');
        window.classList.add('hidden');
    }

    window.addEventListener('click', function (e) {
        const chatWindow = document.getElementById('chatWindow');
        const liveChat = document.getElementById('chat');
        if (!chatWindow.contains(e.target) && e.target !== liveChat) chatWindow.classList.add('hidden');
    });

const chatWindow = document.getElementById('chatWindow');
const chatDisplay = chatWindow.querySelector('.overflow-y-auto');
const chatInput = chatWindow.querySelector('input');

function addMessage(text, sender) {
    const div = document.createElement('div');
    div.className = sender === 'user' ? 'chat-bubble-user ml-auto' : 'chat-bubble-bot';
    div.textContent = text;
    chatDisplay.appendChild(div);
    chatDisplay.scrollTop = chatDisplay.scrollHeight;
    return div;
}

chatInput.addEventListener('keypress', async (e) => {
    if (e.key === 'Enter') {
        const message = chatInput.value.trim();
        if (!message) return;

        addMessage(message, 'user');
        chatInput.value = '';
        chatInput.disabled = true;

        const botBubble = addMessage('', 'bot');

        try {
            const proxyResponse = await fetch('../network/llm-proxy.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: message })
            });

            if (!proxyResponse.ok) {
                const errData = await proxyResponse.json();
                throw new Error(errData.error || "Proxy rejected request");
            }

            const proxyData = await proxyResponse.json();
            const { task_id, fastapi_url } = proxyData;

            if (!task_id || !fastapi_url) {
                throw new Error("Invalid response from proxy");
            }

            const sseUrl = `${fastapi_url}/stream?task_id=${encodeURIComponent(task_id)}&message=${encodeURIComponent(message)}`;
            const eventSource = new EventSource(sseUrl);

            eventSource.onmessage = (event) => {
                const data = JSON.parse(event.data);

                if (data.token) {
                    botBubble.textContent += data.token;
                    chatDisplay.scrollTop = chatDisplay.scrollHeight;
                }

                if (data.status === 'completed' || data.status === 'error') {
                    eventSource.close();
                    chatInput.disabled = false;
                    chatInput.focus();
                }
            };

            eventSource.onerror = (error) => {
                console.error("SSE Error:", error);
                eventSource.close();
                if (!botBubble.textContent) {
                    botBubble.textContent = "Connect error (SSE).";
                }
                chatInput.disabled = false;
                chatInput.focus();
            };

        } catch (error) {
            console.error(error);
            botBubble.textContent = "System in busy, please try again later.";
            chatInput.disabled = false;
            chatInput.focus();
        }
    }
});

function closeChat() {
    document.getElementById('chatWindow').classList.add('hidden');
}
</script>