/* Chatbot removed: original JS has been deleted. */
// This file has been intentionally left blank to remove chatbot functionality.
    var lessonId = widget.dataset.lessonId || null;
    // Use the floating button as the single toggle control (legacy fallback removed)
    var floatingBtn = widget.querySelector('.chat-floating-btn');
    var closeHeaderBtn = widget.querySelector('.chat-close');
    var panel = widget.querySelector('.chat-panel');
    var messagesEl = widget.querySelector('.chat-messages');
    var form = widget.querySelector('.chat-form');
    var input = widget.querySelector('.chat-input');

    function scrollToBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function appendMessage(text, who) {
        var bubble = document.createElement('div');
        bubble.className = 'chat-bubble ' + (who === 'user' ? 'chat-user' : 'chat-ai');
        bubble.textContent = text;
        messagesEl.appendChild(bubble);
        scrollToBottom();
    }

    function showLoader() {
        var loader = document.createElement('div');
        loader.className = 'chat-bubble chat-ai typing';
        loader.textContent = 'AI is typing...';
        messagesEl.appendChild(loader);
        scrollToBottom();
        return loader;
    }

    function togglePanel() {
        if (!panel) return false;
        var isOpen = panel.classList.toggle('open');
        // Keep the floating button visible; change its icon to represent close/open
        if (floatingBtn) {
            var icon = floatingBtn.querySelector('i');
            if (isOpen) {
                if (icon) { icon.classList.remove('fa-comments'); icon.classList.add('fa-times'); }
                floatingBtn.classList.add('open');
            } else {
                if (icon) { icon.classList.remove('fa-times'); icon.classList.add('fa-comments'); }
                floatingBtn.classList.remove('open');
            }
        }
        if (isOpen) {
            setTimeout(function () { input.focus(); }, 200);
        }
        return isOpen;
    }

    // Single listener on the floating button
    if (floatingBtn) {
        floatingBtn.addEventListener('click', togglePanel);
    }

    // header close button
    if (closeHeaderBtn) {
        closeHeaderBtn.addEventListener('click', function (ev) {
            ev.preventDefault();
            if (panel && panel.classList.contains('open')) togglePanel();
        });
    }

    // Close when pressing Escape
    document.addEventListener('keydown', function (ev) {
        if (ev.key === 'Escape') {
            if (panel && panel.classList.contains('open')) togglePanel();
        }
    });

    // Close when clicking outside the widget
    document.addEventListener('click', function (ev) {
        if (!panel || !panel.classList.contains('open')) return;
        var target = ev.target;
        if (!widget.contains(target)) {
            togglePanel();
        }
    });

    // Ensure initial icon state matches panel
    (function initIcon() {
        if (!floatingBtn) return;
        var icon = floatingBtn.querySelector('i');
        if (panel && panel.classList.contains('open')) {
            if (icon) { icon.classList.remove('fa-comments'); icon.classList.add('fa-times'); }
            floatingBtn.classList.add('open');
        } else {
            if (icon) { icon.classList.remove('fa-times'); icon.classList.add('fa-comments'); }
            floatingBtn.classList.remove('open');
        }
    })();

    form.addEventListener('submit', function (ev) {
        ev.preventDefault();
        var text = input.value.trim();
        if (!text) return;

        appendMessage(text, 'user');
        input.value = '';

        var loader = showLoader();

        var body = { message: text };
        if (lessonId) body.lesson_id = parseInt(lessonId, 10);
        // If the page URL contains debug=1, set demo flag so backend can return a canned reply (useful for local testing without OpenAI key)
        try {
            var params = new URLSearchParams(window.location.search);
            if (params.get('debug') === '1') body.demo = true;
        } catch (e) {}

        console.log('Chatbot request', { endpoint: endpoint, body: body });
        fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        }).then(function (res) {
            if (!res.ok) {
                // try to read response text/JSON for better error message
                return res.text().then(function (text) {
                    var msg = 'HTTP ' + res.status + ' ' + res.statusText + ' - ' + (text || 'no body');
                    console.error('Chatbot endpoint error:', msg);
                    throw new Error(msg);
                });
            }
            return res.json();
        }).then(function (data) {
            // Remove loader
            if (loader && loader.parentNode) loader.parentNode.removeChild(loader);

            if (data.answer) {
                appendMessage(data.answer, 'ai');
            } else if (data.error) {
                appendMessage('Error: ' + data.error, 'ai');
            } else {
                appendMessage('No response from AI.', 'ai');
            }
        }).catch(function (err) {
            if (loader && loader.parentNode) loader.parentNode.removeChild(loader);
            console.error('Chatbot fetch error:', err);
            appendMessage('Error: ' + (err.message || err), 'ai');
        });
    });

    // allow pressing Enter to send
    input.addEventListener('keydown', function (ev) {
        if (ev.key === 'Enter' && !ev.shiftKey) {
            ev.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
    });
});
