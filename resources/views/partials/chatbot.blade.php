<div id="pc-chatbot" aria-live="polite">
  <button id="pc-chatbot-toggle" aria-label="Open chat">
    <i class="bi bi-chat-dots-fill"></i>
  </button>

  <div id="pc-chatbot-window" role="dialog" aria-hidden="true">
    <div class="pc-chatbot-header">
      <span>Hỗ trợ trực tuyến</span>
      <button id="pc-chatbot-close" aria-label="Close chat">×</button>
    </div>
    <div id="pc-chatbot-messages" class="pc-chatbot-messages" aria-live="polite"></div>
    <form id="pc-chatbot-form" class="pc-chatbot-form" action="#" onsubmit="return false;">
      <input id="pc-chatbot-input" type="text" placeholder="Gõ tin nhắn..." autocomplete="off">
      <button id="pc-chatbot-send" type="submit">Gửi</button>
    </form>
  </div>

  <style>
    /* Chatbot widget styles (simple, scoped) */
    #pc-chatbot { position: fixed; right: 20px; bottom: 24px; z-index: 1200; font-family: Inter, sans-serif; }
    #pc-chatbot-toggle { background:#0d6efd; color:#fff; border:none; width:56px; height:56px; border-radius:50%; box-shadow:0 6px 18px rgba(13,110,253,0.35); cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:20px; }
    #pc-chatbot-toggle:focus{outline:2px solid rgba(13,110,253,0.25)}
    #pc-chatbot-window{ width:320px; max-width:calc(100vw - 40px); position:fixed; right:20px; bottom:92px; background:#fff; border-radius:12px; box-shadow:0 8px 30px rgba(0,0,0,0.15); overflow:hidden; display:none; }
    #pc-chatbot-window[aria-hidden="false"]{display:block}
    .pc-chatbot-header{ background:#0d6efd; color:#fff; padding:12px 14px; display:flex; justify-content:space-between; align-items:center; font-weight:600 }
    .pc-chatbot-header button{ background:transparent; border:0; color:#fff; font-size:20px; cursor:pointer }
    .pc-chatbot-messages{ height:320px; padding:12px; overflow:auto; background:linear-gradient(180deg,#f8f9ff, #fff); }
    .pc-chatbot-msg{ margin-bottom:10px; display:flex }
    .pc-chatbot-msg.user{ justify-content:flex-end }
    .pc-chatbot-bubble{ max-width:75%; padding:8px 12px; border-radius:12px; background:#eee; color:#111; line-height:1.5; font-size:14px; word-wrap:break-word; }
    .pc-chatbot-msg.user .pc-chatbot-bubble{ background:#0d6efd; color:#fff }
    .pc-chatbot-bubble strong { font-weight:600; }
    .pc-chatbot-bubble p { margin:6px 0; }
    .pc-chatbot-bubble p:first-child { margin-top:0; }
    .pc-chatbot-bubble p:last-child { margin-bottom:0; }
    .pc-chatbot-bubble ul { margin:6px 0; padding-left:18px; }
    .pc-chatbot-bubble li { margin:4px 0; list-style:disc; }
    .pc-chatbot-form{ display:flex; gap:8px; padding:10px; border-top:1px solid #eee }
    .pc-chatbot-form input{ flex:1; padding:8px 10px; border-radius:8px; border:1px solid #ddd }
    .pc-chatbot-form button{ background:#0d6efd; color:#fff; border:0; padding:8px 12px; border-radius:8px; cursor:pointer }
  </style>

  <script>
    (function(){
      const toggle = document.getElementById('pc-chatbot-toggle');
      const win = document.getElementById('pc-chatbot-window');
      const closeBtn = document.getElementById('pc-chatbot-close');
      const form = document.getElementById('pc-chatbot-form');
      const input = document.getElementById('pc-chatbot-input');
      const messages = document.getElementById('pc-chatbot-messages');

      function openChat(){ win.setAttribute('aria-hidden','false'); input.focus(); }
      function closeChat(){ win.setAttribute('aria-hidden','true'); toggle.focus(); }

      toggle.addEventListener('click', function(){
        const hidden = win.getAttribute('aria-hidden') === 'false';
        if(hidden) closeChat(); else openChat();
      });
      closeBtn.addEventListener('click', closeChat);

      function appendMessage(text, who='bot'){
        const el = document.createElement('div'); el.className = 'pc-chatbot-msg ' + (who==='user' ? 'user' : 'bot');
        const bubble = document.createElement('div'); bubble.className = 'pc-chatbot-bubble';
        
        // Parse Markdown for bot messages
        if(who === 'bot'){
          bubble.innerHTML = parseMarkdown(text);
        } else {
          bubble.textContent = text;
        }
        
        el.appendChild(bubble); messages.appendChild(el); messages.scrollTop = messages.scrollHeight;
      }

      // Simple Markdown parser for bot responses
      function parseMarkdown(text){
        // Escape HTML first
        let html = text
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;');
        
        // Convert bold: **text** → <strong>text</strong>
        html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
        
        // Convert bullet points: * item → <li>item</li> wrapped in <ul>
        html = html.replace(/^\* (.+)$/gm, '<li>$1</li>');
        html = html.replace(/(<li>.*<\/li>)/s, '<ul>$1</ul>');
        
        // Convert line breaks
        html = html.replace(/\n\n/g, '</p><p>');
        html = '<p>' + html + '</p>';
        
        // Clean up empty paragraphs
        html = html.replace(/<p><\/p>/g, '');
        
        return html;
      }

      // Call to backend API for bot replies
      async function botReply(userText){
        try {
          const response = await fetch('http://127.0.0.1:8001/api/chat', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: userText })
          });
          if(!response.ok) throw new Error('API error: ' + response.status);
          const data = await response.json();
          return data.response || 'Không thể xử lý yêu cầu của bạn.';
        } catch(err){
          console.error('Chatbot API error:', err);
          return 'Xin lỗi, không thể kết nối tới máy chủ. Vui lòng thử lại sau.';
        }
      }

      form.addEventListener('submit', async function(e){
        e.preventDefault();
        const text = input.value.trim(); if(!text) return;
        appendMessage(text, 'user'); input.value = '';
        // Show typing indicator
        appendMessage('Đang xử lý...', 'bot');
        const reply = await botReply(text);
        // Remove typing indicator and append real reply
        messages.removeChild(messages.lastChild);
        appendMessage(reply, 'bot');
      });

      // Persist conversation in sessionStorage for demo
      window.addEventListener('beforeunload', function(){ /* nothing for now */ });

    })();
  </script>

</div>
