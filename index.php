<?php
// PHP Backend Handle & View Logic
$lang = isset($_GET['lang']) && $_GET['lang'] === 'zh' ? 'zh' : 'en';

$translations = [
    'en' => [
        'title' => 'OCR Converter',
        'manual' => 'User Manual',
        'offline_ver' => 'Offline Version (JS)',
        'choose_img' => 'Choose Image',
        'start_ocr' => 'Start OCR',
        'clear' => 'Clear',
        'copy' => 'Copy Text',
        'print' => 'Print',
        'export_word' => 'Export to Word',
        'placeholder' => 'Recognized text will appear here...',
        'status_ready' => 'Ready',
        'err_no_img' => 'Please select an image file first.',
        'err_format' => 'Unsupported image format. Allowed: JPG, JPEG, PNG, GIF, BMP, TIFF, WEBP.',
        'err_size' => 'File size exceeds the 10 MB maximum limit.',
        'err_api' => 'OCR/API failure occurred.',
        'err_notext' => 'No text detected in the image.'
    ],
    'zh' => [
        'title' => '文字识别转换器 (OCR)',
        'manual' => '使用手册',
        'offline_ver' => '离线版本 (JS)',
        'choose_img' => '选择图片',
        'start_ocr' => '开始识别',
        'clear' => '清空',
        'copy' => '复制文本',
        'print' => '打印',
        'export_word' => '导出为 Word',
        'placeholder' => '识别出的文本将显示在这里...',
        'status_ready' => '就绪',
        'err_no_img' => '请先选择一个图片文件。',
        'err_format' => '不支持的图片格式。允许的格式：JPG, JPEG, PNG, GIF, BMP, TIFF, WEBP。',
        'err_size' => '文件大小超过了 10 MB 的限制。',
        'err_api' => 'OCR/API 处理失败。',
        'err_notext' => '未能在图片中检测到文字。'
    ]
];

$t = $translations[$lang];

// Handle AJAX Request for OCR Processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        echo json_encode(['success' => false, 'error' => $t['err_no_img']]);
        exit;
    }

    $file = $_FILES['image'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'error' => $t['err_api']]);
        exit;
    }

    // 10 MB Max Size Check
    if ($file['size'] > 10 * 1024 * 1024) {
        echo json_encode(['success' => false, 'error' => $t['err_size']]);
        exit;
    }

    // MIME Type Validation using Fileinfo
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowedMimes = [
        'image/jpeg', 'image/png', 'image/gif', 
        'image/bmp', 'image/tiff', 'image/webp'
    ];

    if (!in_array($mimeType, $allowedMimes)) {
        echo json_encode(['success' => false, 'error' => $t['err_format']]);
        exit;
    }

    // Load .env configuration safely
    $envPath = __DIR__ . '/.env';
    $apiKey = '';
    if (file_exists($envPath)) {
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            list($key, $value) = explode('=', $line, 2);
            if (trim($key) === 'AGNES_API_KEY') {
                $apiKey = trim($value);
                break;
            }
        }
    }

    if (empty($apiKey)) {
        $apiKey = getenv('AGNES_API_KEY');
    }

    if (empty($apiKey)) {
        echo json_encode(['success' => false, 'error' => 'Server Configuration Error: API Key missing.']);
        exit;
    }

    // Prepare image for Agnes AI Base64 Transmission
    $imageData = file_get_contents($file['tmp_name']);
    $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);

    $payload = [
        'model' => 'agnes-2.5-flash',
        'messages' => [
            [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => 'Extract all visible English or Simplified Chinese text from this image precisely. Preserve line breaks and reading layout order where possible. Do not translate.'
                    ],
                    [
                        'type' => 'image_url',
                        'image_url' => ['url' => $base64Image]
                    ]
                ]
            ]
        ]
    ];

    $ch = curl_init('https://apihub.agnes-ai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$response) {
        echo json_encode(['success' => false, 'error' => $t['err_api']]);
        exit;
    }

    $responseData = json_decode($response, true);
    $extractedText = $responseData['choices'][0]['message']['content'] ?? '';

    if (empty(trim($extractedText))) {
        echo json_encode(['success' => false, 'error' => $t['err_notext']]);
        exit;
    }

    echo json_encode(['success' => true, 'text' => $extractedText]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['title']; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><?php echo $t['title']; ?></h1>
            <div class="nav-links">
                <a href="offline.html"><?php echo $t['offline_ver']; ?></a>
                <a href="manual.php?lang=<?php echo $lang; ?>"><?php echo $t['manual']; ?></a>
                <div class="lang-switch" style="display:inline-block; margin-left:15px;">
                    <a href="?lang=en">English</a> | <a href="?lang=zh">简体中文</a>
                </div>
            </div>
        </header>

        <main>
            <div class="upload-section">
                <input type="file" id="image-input" accept=".jpg, .jpeg, .png, .gif, .bmp, .tiff, .webp" style="display: none;">
                <button id="choose-btn"><?php echo $t['choose_img']; ?></button>
                <div class="preview-container">
                    <img id="image-preview" src="" alt="Preview" style="display: none;">
                    <span id="file-name" class="file-info"></span>
                </div>
            </div>

            <div class="actions">
                <button id="start-ocr-btn" disabled><?php echo $t['start_ocr']; ?></button>
                <button id="clear-btn"><?php echo $t['clear']; ?></button>
            </div>

            <div id="status-box" class="status-box"><?php echo $t['status_ready']; ?></div>

            <div class="editor-section">
                <textarea id="ocr-textarea" placeholder="<?php echo $t['placeholder']; ?>"></textarea>
                <div class="editor-actions">
                    <button id="copy-btn"><?php echo $t['copy']; ?></button>
                    <button id="print-btn"><?php echo $t['print']; ?></button>
                    <button id="export-word-btn"><?php echo $t['export_word']; ?></button>
                </div>
            </div>
        </main>
    </div>

    <script>
        window.TRANSLATIONS = <?php echo json_encode($t); ?>;
    </script>
    <script src="app.js"></script>
</body>
</html>