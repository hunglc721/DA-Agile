<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header py-3 bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-envelope"></i> Trao Đổi Với Admin
                    </h5>
                </div>
                <div class="card-body">
                    <div class="messages-container mb-3" style="height: 450px; overflow-y: auto; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 20px; border-radius: 8px; border: 1px solid #e0e0e0;" id="messagesContainer">
                        <?php if (!empty($messages)): ?>
                            <?php foreach ($messages as $msg): ?>
                                <div class="chat-message mb-3" style="display: flex; justify-content: <?= $msg['sender_id'] == $_SESSION['user']['id'] ? 'flex-end' : 'flex-start' ?>; animation: slideInChat 0.3s ease-in-out;">
                                    <div class="message-bubble" style="max-width: 75%; background: <?= $msg['sender_id'] == $_SESSION['user']['id'] ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : '#ffffff' ?>; color: <?= $msg['sender_id'] == $_SESSION['user']['id'] ? 'white' : '#333' ?>; padding: 12px 18px; border-radius: 18px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); word-break: break-word;">
                                        <p class="mb-1" style="line-height: 1.4; font-size: 15px;">
                                            <?= htmlspecialchars($msg['content']) ?>
                                        </p>
                                        <small style="opacity: 0.8; display: block; font-size: 12px; margin-top: 5px;">
                                            <i class="fas fa-clock"></i> <?= date('H:i - d/m/Y', strtotime($msg['created_at'])) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-info text-center" style="margin-top: 150px;">
                                <i class="fas fa-info-circle"></i> Chưa có cuộc trao đổi nào. Hãy gửi tin nhắn đầu tiên!
                            </div>
                        <?php endif; ?>
                    </div>

                    <script>
                    // Auto scroll xuống dưới khi load
                    document.addEventListener('DOMContentLoaded', function() {
                        var container = document.getElementById('messagesContainer');
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    });
                    </script>

                    <form method="POST" action="?action=guide_send_message" class="mt-3">
                        <div class="input-group input-group-lg">
                            <textarea class="form-control" name="message" placeholder="Nhập tin nhắn của bạn..." rows="3" required style="resize: vertical; border-radius: 8px 0 0 8px; border: 2px solid #e0e0e0;"></textarea>
                            <button class="btn btn-primary" type="submit" style="border-radius: 0 8px 8px 0;">
                                <i class="fas fa-paper-plane"></i> Gửi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

