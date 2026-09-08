<?php
$lang = isset($_GET['lang']) && $_GET['lang'] === 'zh' ? 'zh' : 'en';

$content = [
    'en' => [
        'title' => 'User Manual - OCR Converter',
        'back' => '← Back to Converter',
        's1_title' => '1. Selecting an Image',
        's1_desc' => 'Click the "Choose Image" button to select a file from your device. Supported formats: JPG, JPEG, PNG, GIF, BMP, TIFF, WEBP.',
        's2_title' => '2. File Limit',
        's2_desc' => 'Maximum allowed file size is 10 MB. Files over this size or with invalid formats will be rejected with clear error indicators.',
        's3_title' => '3. Starting OCR',
        's3_desc' => 'Once an image is selected, click "Start OCR". Status stages will display progress: Starting → Uploading → Recognizing → Complete.',
        's4_title' => '4. Editing Recognized Text',
        's4_desc' => 'The recognized text is loaded into a responsive editable textarea where you can refine typos, adjust line breaks, or correct specific words safely.',
        's5_title' => '5. Copy, Print, and Export',
        's5_desc' => 'Use the action buttons below the text box to copy text to your clipboard, print the layout directly, or export data directly into a Microsoft Word compatible (.doc) file format.'
    ],
    'zh' => [
        'title' => '使用手册 - 文字识别转换器',
        'back' => '← 返回转换器',
        's1_title' => '1. 选择图片',
        's1_desc' => '点击“选择图片”按钮从您的设备中选择文件。支持的格式：JPG, JPEG, PNG, GIF, BMP, TIFF, WEBP。',
        's2_title' => '2. 文件限制',
        's2_desc' => '最大允许文件大小为 10 MB。超过此大小或格式无效的文件将被拒绝，并显示明确的错误提示。',
        's3_title' => '3. 开始识别',
        's3_desc' => '选择图片后，点击“开始识别”。界面将依次显示状态阶段：Starting → Uploading → Recognizing → Complete。',
        's4_title' => '4. 编辑识别文本',
        's4_desc' => '识别出的文本会加载到响应式文本框中，您可以在此处安全地修改错别字、调整换行或更正特定词汇。',
        's5_title' => '5. 复制、打印与导出',
        's5_desc' => '使用文本框下方的操作按钮将文本复制到剪贴板、直接打印排版，或者导出为与 Microsoft Word 兼容的 (.doc) 文件格式。'
    ]
];

$c = $content[$lang];
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $c['title']; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><?php echo $c['title']; ?></h1>
            <div class="nav-links">
                <a href="index.php?lang=<?php echo $lang; ?>"><?php echo $c['back']; ?></a>
                <div class="lang-switch" style="display:inline-block; margin-left:15px;">
                    <a href="?lang=en">English</a> | <a href="?lang=zh">简体中文</a>
                </div>
            </div>
        </header>

        <main class="manual-content">
            <section>
                <h2><?php echo $c['s1_title']; ?></h2>
                <p><?php echo $c['s1_desc']; ?></p>
            </section>
            <section>
                <h2><?php echo $c['s2_title']; ?></h2>
                <p><?php echo $c['s2_desc']; ?></p>
            </section>
            <section>
                <h2><?php echo $c['s3_title']; ?></h2>
                <p><?php echo $c['s3_desc']; ?></p>
            </section>
            <section>
                <h2><?php echo $c['s4_title']; ?></h2>
                <p><?php echo $c['s4_desc']; ?></p>
            </section>
            <section>
                <h2><?php echo $c['s5_title']; ?></h2>
                <p><?php echo $c['s5_desc']; ?></p>
            </section>
        </main>
    </div>
</body>
</html>