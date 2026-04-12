<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-envelope"></i> Trao Đổi Với Hướng Dẫn Viên
        </h1>
    </div>

    <!-- Messages Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-comments"></i> Cuộc Trao Đổi
                <span class="badge badge-light"><?= count($messages ?? []) ?> tin nhắn</span>
            </h6>
        </div>
        <div class="card-body">
            <?php if (empty($messages)): ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle"></i> Chưa có cuộc trao đổi nào
                </div>
            <?php else: ?>
                <?php 
                // Group messages by conversation partner (HDV)
                $groupedByUser = [];
                foreach ($messages as $msg):
                    // Xác định HDV ID (người không phải admin)
                    $hdvId = ($msg['sender_id'] == $_SESSION['user']['id']) ? $msg['receiver_id'] : $msg['sender_id'];
                    
                    if (!isset($groupedByUser[$hdvId])) {
                        $groupedByUser[$hdvId] = [
                            'hdv_name' => $msg['sender_name'], // Tên HDV
                            'messages' => []
                        ];
                    }
                    $groupedByUser[$hdvId]['messages'][] = $msg;
                endforeach;
                
                foreach ($groupedByUser as $hdvId => $userGroup):
                ?>
                    <div class="message-group-container mb-4">
                        <!-- User Header with Collapse Button -->
                        <div class="bg-light p-3 rounded mb-3 d-flex justify-content-between align-items-center" style="border-left: 4px solid #007bff;">
                            <div>
                                <h6 class="mb-0">
                                    <i class="fas fa-user-circle text-primary"></i>
                                    <strong><?= htmlspecialchars($userGroup['hdv_name']) ?></strong>
                                </h6>
                                <small class="text-muted"><?= count($userGroup['messages']) ?> tin nhắn</small>
                            </div>
                            <button class="btn btn-sm btn-outline-primary" type="button" data-toggle="collapse" data-target="#messages-<?= $hdvId ?>" aria-expanded="true">
                                <i class="fas fa-chevron-down"></i> Chi tiết
                            </button>
                        </div>

                        <!-- Messages List with Chat Bubble Style -->
                        <div class="collapse show" id="messages-<?= $hdvId ?>">
                            <div class="chat-container mb-3" style="height: 350px; overflow-y: auto; background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e0e0e0;">
                                <?php foreach ($userGroup['messages'] as $msg): ?>
                                    <div class="chat-message mb-3" style="display: flex; justify-content: <?= $msg['sender_id'] == $_SESSION['user']['id'] ? 'flex-end' : 'flex-start' ?>;">
                                        <div class="message-bubble" style="max-width: 70%; background: <?= $msg['sender_id'] == $_SESSION['user']['id'] ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : '#ffffff' ?>; color: <?= $msg['sender_id'] == $_SESSION['user']['id'] ? 'white' : '#333' ?>; border: <?= $msg['sender_id'] == $_SESSION['user']['id'] ? 'none' : '1px solid #ddd' ?>; padding: 12px 15px; border-radius: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                            <p class="mb-1" style="word-break: break-word;">
                                                <?= htmlspecialchars($msg['content']) ?>
                                            </p>
                                            <small style="opacity: 0.7; display: block; font-size: 12px;">
                                                <i class="fas fa-clock"></i> <?= date('H:i - d/m/Y', strtotime($msg['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Reply Form -->
                            <form method="POST" action="index.php?action=admin_send_message" class="mt-3">
                                <input type="hidden" name="receiver_id" value="<?= $hdvId ?>">
                                <div class="input-group">
                                    <textarea class="form-control" name="message" placeholder="Nhập tin nhắn trả lời..." rows="3" required style="resize: vertical;"></textarea>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Gửi
                                    </button>
                                </div>
                            </form>

                            <script>
                            // Auto scroll xuống dưới khi load
                            document.addEventListener('DOMContentLoaded', function() {
                                var chatContainer = document.querySelector('.chat-container');
                                if (chatContainer) {
                                    chatContainer.scrollTop = chatContainer.scrollHeight;
                                }
                            });
                            </script>
                        </div>
                    </div>
                    <hr>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

