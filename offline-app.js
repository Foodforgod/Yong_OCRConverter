document.addEventListener('DOMContentLoaded', () => {
    const imageInput = document.getElementById('image-input');
    const chooseBtn = document.getElementById('choose-btn');
    const imagePreview = document.getElementById('image-preview');
    const fileNameSpan = document.getElementById('file-name');
    const startOcrBtn = document.getElementById('start-ocr-btn');
    const clearBtn = document.getElementById('clear-btn');
    const statusBox = document.getElementById('status-box');
    const ocrTextarea = document.getElementById('ocr-textarea');
    const copyBtn = document.getElementById('copy-btn');
    const printBtn = document.getElementById('print-btn');
    const exportWordBtn = document.getElementById('export-word-btn');

    let selectedFile = null;

    chooseBtn.addEventListener('click', () => imageInput.click());

    imageInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;

        if (file.size > 10 * 1024 * 1024) {
            showStatus('File size exceeds the 10 MB maximum limit.', 'error');
            resetFileState();
            return;
        }

        selectedFile = file;
        fileNameSpan.textContent = `${file.name} (${(file.size / (1024*1024)).toFixed(2)} MB)`;
        
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);

        startOcrBtn.disabled = false;
        showStatus('Ready', '');
    });

    startOcrBtn.addEventListener('click', async () => {
        if (!selectedFile) return;

        try {
            showStatus('Starting...', '');
            await new Promise(r => setTimeout(r, 200));

            showStatus('Recognizing locally with Tesseract...', '');
            
            const worker = await Tesseract.createWorker(['eng', 'chi_sim']);
            const ret = await worker.recognize(selectedFile);
            await worker.terminate();

            if (ret && ret.data && ret.data.text.trim()) {
                showStatus('Complete', 'success');
                ocrTextarea.value = ret.data.text;
            } else {
                showStatus('No text detected.', 'error');
            }
        } catch (err) {
            showStatus('OCR/API failure occurred.', 'error');
        }
    });

    clearBtn.addEventListener('click', () => {
        resetFileState();
        ocrTextarea.value = '';
        showStatus('Ready', '');
    });

    copyBtn.addEventListener('click', () => {
        if (!ocrTextarea.value.trim()) return;
        navigator.clipboard.writeText(ocrTextarea.value).then(() => {
            const originalText = copyBtn.textContent;
            copyBtn.textContent = 'Copied!';
            setTimeout(() => copyBtn.textContent = originalText, 2000);
        });
    });

    printBtn.addEventListener('click', () => {
        if (!ocrTextarea.value.trim()) return;
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Print OCR Result</title></head><body>');
        printWindow.document.write('<pre style="font-family:sans-serif; white-space:pre-wrap;">' + escapeHtml(ocrTextarea.value) + '</pre>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    });

    exportWordBtn.addEventListener('click', () => {
        const text = ocrTextarea.value;
        if (!text.trim()) return;

        const safeHtmlText = escapeHtml(text).replace(/\n/g, '<br>');
        const htmlContent = `<html><head><meta charset="utf-8"><title>OCR Export</title></head><body style="font-family:Arial; font-size:12pt;"><p>${safeHtmlText}</p></body></html>`;
        const blob = new Blob(['\ufeff' + htmlContent], { type: 'application/msword' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'OCR_Result_Offline.doc';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });

    function showStatus(message, type) {
        statusBox.textContent = message;
        statusBox.className = 'status-box ' + type;
    }

    function resetFileState() {
        selectedFile = null;
        imageInput.value = '';
        imagePreview.src = '';
        imagePreview.style.display = 'none';
        fileNameSpan.textContent = '';
        startOcrBtn.disabled = true;
    }

    function escapeHtml(text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
});