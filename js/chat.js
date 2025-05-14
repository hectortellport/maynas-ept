document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const chatToggle = document.getElementById('chatToggle');
    const chatModal = document.getElementById('chatModal');
    const closeChat = document.getElementById('closeChat');
    const chatBody = document.getElementById('chatBody');
    const chatInput = document.getElementById('chatInput');
    const sendMessage = document.getElementById('sendMessage');
    
    // Mostrar/ocultar el chat modal
    chatToggle.addEventListener('click', function() {
        chatModal.style.display = chatModal.style.display === 'flex' ? 'none' : 'flex';
    });
    
    closeChat.addEventListener('click', function() {
        chatModal.style.display = 'none';
    });
    
    // Enviar mensaje al presionar Enter
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessageFunc();
        }
    });
    
    // Enviar mensaje al hacer clic en el botón
    sendMessage.addEventListener('click', sendMessageFunc);
    
    // Función para enviar mensaje
    function sendMessageFunc() {
        const messageText = chatInput.value.trim();
        if (messageText !== '') {
            // Agregar mensaje del usuario
            addMessage(messageText, 'user');
            chatInput.value = '';
            
            // Simular respuesta del bot después de un breve retraso
            setTimeout(function() {
                const botResponse = getBotResponse(messageText);
                addMessage(botResponse, 'bot');
            }, 1000);
        }
    }
    
    // Función para agregar mensaje al chat
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message');
        messageDiv.classList.add(sender + '-message');
        messageDiv.textContent = text;
        chatBody.appendChild(messageDiv);
        
        // Auto scroll al último mensaje
        chatBody.scrollTop = chatBody.scrollHeight;
    }
    
    // Función para generar respuestas del bot (simplificada)
    function getBotResponse(userMessage) {
        const lowerMessage = userMessage.toLowerCase();
        
        if (lowerMessage.includes('hola') || lowerMessage.includes('hi')) {
            return '¡Hola! ¿Cómo estás?';
        } else if (lowerMessage.includes('ayuda')) {
            return 'Claro, ¿en qué necesitas ayuda?';
        } else if (lowerMessage.includes('gracias')) {
            return '¡De nada! ¿Hay algo más en lo que pueda ayudarte?';
        } else if (lowerMessage.includes('adios') || lowerMessage.includes('chao')) {
            return '¡Hasta luego! No dudes en volver si necesitas más ayuda.';
        } else {
            return 'Entendido. ¿Hay algo específico sobre lo que te gustaría hablar?';
        }
    }
});