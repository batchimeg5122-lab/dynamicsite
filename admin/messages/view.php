<?php
require_once '../../config.php';
checkAdminLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM messages WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$message = $result->fetch_assoc();

if (!$message) {
    $_SESSION['error'] = 'Мессеж олдсонгүй!';
    redirect(ADMIN_URL . 'messages/');
}
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мессеж үзэх - Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Header -->
            <div class="col-12 bg-white shadow-sm py-3 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="index.php" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="bi bi-arrow-left"></i> Буцах
                        </a>
                        <h4 class="d-inline mb-0">Мессеж #<?php echo $message['id']; ?></h4>
                    </div>
                    <div>
                        <a href="delete.php?id=<?php echo $message['id']; ?>" 
                           class="btn btn-outline-danger btn-sm"
                           onclick="return confirm('Устгахдаа итгэлтэй байна уу?')">
                            <i class="bi bi-trash"></i> Устгах
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="col-12">
                <div class="row">
                    <!-- Мессежийн дэлгэрэнгүй -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-envelope-open"></i> Мессежийн мэдээлэл
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Илгээгч мэдээлэл -->
                                <div class="mb-4">
                                    <h6 class="text-muted mb-3">Илгээгч:</h6>
                                    
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-person fs-4"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0"><?php echo htmlspecialchars($message['name']); ?></h5>
                                            <small class="text-muted">
                                                <i class="bi bi-clock"></i> 
                                                <?php echo date('Y оны m сарын d, H:i', strtotime($message['created_at'])); ?>
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="list-group">
                                        <div class="list-group-item">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-envelope-fill text-primary me-2"></i>
                                                <div>
                                                    <small class="text-muted d-block">И-мэйл</small>
                                                    <a href="mailto:<?php echo $message['email']; ?>" class="text-decoration-none">
                                                        <?php echo htmlspecialchars($message['email']); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <?php if($message['phone']): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-telephone-fill text-primary me-2"></i>
                                                <div>
                                                    <small class="text-muted d-block">Утас</small>
                                                    <a href="tel:<?php echo $message['phone']; ?>" class="text-decoration-none">
                                                        <?php echo htmlspecialchars($message['phone']); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Мессеж -->
                                <div>
                                    <h6 class="text-muted mb-2">Мессеж:</h6>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <p class="mb-0" style="white-space: pre-wrap;"><?php echo htmlspecialchars($message['message']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Хариу илгээх форм -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-reply-fill"></i> Хариу илгээх
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="replyAlert"></div>
                                
                                <form id="replyForm">
                                    <input type="hidden" id="messageId" value="<?php echo $message['id']; ?>">
                                    <input type="hidden" id="recipientEmail" value="<?php echo htmlspecialchars($message['email']); ?>">
                                    <input type="hidden" id="recipientName" value="<?php echo htmlspecialchars($message['name']); ?>">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Хэнд:</label>
                                        <input type="text" 
                                               class="form-control" 
                                               value="<?php echo htmlspecialchars($message['name']); ?> <<?php echo htmlspecialchars($message['email']); ?>>" 
                                               readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="subject" class="form-label">
                                            Гарчиг <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="subject" 
                                               name="subject" 
                                               value="Re: Таны мессежийн хариу"
                                               required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="replyMessage" class="form-label">
                                            Хариу <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control" 
                                                  id="replyMessage" 
                                                  name="replyMessage" 
                                                  rows="10"
                                                  required
                                                  placeholder="Хариу бичнэ үү..."></textarea>
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle"></i> Таны хариу <?php echo $message['email']; ?> хаягруу илгээгдэнэ
                                        </small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body py-2">
                                                <small class="text-muted">
                                                    <strong>Анхааруулга:</strong> Энэ функц ажиллахын тулд серверт PHP mail() эсвэл SMTP тохируулсан байх шаардлагатай.
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-success btn-lg w-100" id="sendBtn">
                                        <i class="bi bi-send-fill"></i> Хариу илгээх
                                    </button>
                                </form>
                                
                                <div class="mt-3">
                                    <h6 class="text-muted mb-2">Хурдан хариу загварууд:</h6>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-sm btn-outline-secondary text-start" onclick="insertTemplate('thanks')">
                                            <i class="bi bi-chat-left-text"></i> Талархал илэрхийлэх
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary text-start" onclick="insertTemplate('info')">
                                            <i class="bi bi-info-circle"></i> Нэмэлт мэдээлэл хүсэх
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary text-start" onclick="insertTemplate('meeting')">
                                            <i class="bi bi-calendar-event"></i> Уулзалтын санал
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Хариу илгээх
        document.getElementById('replyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('sendBtn');
            const alert = document.getElementById('replyAlert');
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Илгээж байна...';
            
            const formData = new FormData();
            formData.append('messageId', document.getElementById('messageId').value);
            formData.append('email', document.getElementById('recipientEmail').value);
            formData.append('name', document.getElementById('recipientName').value);
            formData.append('subject', document.getElementById('subject').value);
            formData.append('message', document.getElementById('replyMessage').value);
            
            fetch('reply.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle"></i> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    document.getElementById('replyForm').reset();
                } else {
                    alert.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle"></i> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                }
            })
            .catch(error => {
                alert.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle"></i> Алдаа гарлаа!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-send-fill"></i> Хариу илгээх';
            });
        });
        
        // Загвар оруулах
        function insertTemplate(type) {
            const textarea = document.getElementById('replyMessage');
            const name = document.getElementById('recipientName').value;
            
            let template = '';
            
            if (type === 'thanks') {
                template = `Сайн байна уу ${name},

Таны мессежинд баярлалаа. Бид танд удахгүй холбогдох болно.

Хүндэтгэсэн,
${document.querySelector('.navbar-brand')?.textContent || 'Компани'}`;
            } else if (type === 'info') {
                template = `Сайн байна уу ${name},

Таны мессежийг хүлээн авлаа. Илүү дэлгэрэнгүй мэдээлэл өгөх боломжтой.

Та дараах мэдээллийг бидэнд хүргэж өгнө үү:
- 
- 
- 

Баярлалаа,
${document.querySelector('.navbar-brand')?.textContent || 'Компани'}`;
            } else if (type === 'meeting') {
                template = `Сайн байна уу ${name},

Таны мессежинд баярлалаа. Бид танд уулзаж ярилцахыг хүсч байна.

Та дараах цагуудын аль нь тохиромжтой вэ?
- 
- 
- 

Та сонирхсон цагаа мэдэгдээрэй.

Хүндэтгэсэн,
${document.querySelector('.navbar-brand')?.textContent || 'Компани'}`;
            }
            
            textarea.value = template;
            textarea.focus();
        }
    </script>
</body>
</html>