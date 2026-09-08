# OCR Converter App

A modern, simple, responsive OCR converter web application featuring PHP server-side proxy handling with **Agnes AI (`agnes-2.5-flash`)**, full bilingual support (English and Simplified Chinese), client/server dual validations, and an alternate **Client-Side JS Offline Mode**.

## Requirements
- **PHP 8+** with cURL and Fileinfo extensions enabled.
- Any standard web server compatible with PHP (Apache/Nginx/XAMPP/cPanel).

## XAMPP Installation
1. Move the `OCRConverter/` folder into your XAMPP `htdocs/` directory (`C:\xampp\htdocs\OCRConverter`).
2. Create a `.env` file inside the root folder with your valid API key (`AGNES_API_KEY=your_key`).
3. Start Apache from the XAMPP Control Panel.
4. Access via browser: `http://localhost/OCRConverter/index.php`.

## cPanel Installation
1. Compress your `OCRConverter/` project folder into a `.zip` archive.
2. Log into your cPanel account and open the **File Manager**.
3. Navigate to `public_html/` (or your preferred subdomain folder) and upload the `.zip` file. Extract it.
4. Ensure the `.env` file containing your private API key is present in the application directory.
5. Access the app via your domain: `https://yourdomain.com/index.php`.

## Agnes API Configuration & Environment Setup
- The application isolates the Agnes AI key completely on the server side using the `.env` configuration file.
- The API key is never exposed to frontend code or client-side JavaScript bundle files.

## Step-by-Step Usage Guide
<img width="1917" height="1081" alt="image" src="https://github.com/user-attachments/assets/c71f628b-c467-4a93-b662-70c0aa72221c" />

1. **Open the Application:** Navigate to `index.php` in your browser. Use the language links at the top header to toggle between **English** and **简体中文**.
<img width="1911" height="1085" alt="image" src="https://github.com/user-attachments/assets/6a1d4aed-f8a1-43ba-8569-bfb8c7ca326a" />

2. **Choose an Image:** Click the **"Choose Image"** button to select an image from your device. 
   - *Supported formats:* JPG, JPEG, PNG, GIF, BMP, TIFF, WEBP.
   - *Size limit:* Maximum 10 MB.
<img width="1905" height="1073" alt="image" src="https://github.com/user-attachments/assets/2f62b5b8-72cb-4b35-a313-b4925c98a272" />

3. **Preview Image:** Once selected, a thumbnail preview and the exact file size will appear on screen.
<img width="1896" height="1082" alt="image" src="https://github.com/user-attachments/assets/0ee9d347-9485-4ee3-8d7d-cf126fe8ec20" />

4. **Start OCR:** Click the **"Start OCR"** button. The status box will dynamically update through the workflow stages:
   - `Starting...` → `Uploading...` → `Recognizing...` → `Complete`
<img width="1213" height="475" alt="image" src="https://github.com/user-attachments/assets/ffdfc7be-d9d5-49d9-80a3-8df1fde73276" />

5. **Edit Recognized Text:** The extracted text will automatically load into the responsive textarea. You can safely edit typos, correct characters, or format line breaks directly.
<img width="1270" height="137" alt="image" src="https://github.com/user-attachments/assets/5b6d2275-3116-4889-b97d-e72930a59a64" />

6. **Use Actions:**
   - **Copy Text:** Copies the text directly to your clipboard.
   - **Print:** Opens a formatted print layout window.
   - **Export to Word:** Downloads the text as a Microsoft Word-compatible `.doc` file.
<img width="1125" height="97" alt="image" src="https://github.com/user-attachments/assets/fba0631e-2638-4f89-a9b1-2a7d3adc7d24" />

7. **Clear:** Click the **"Clear"** button to reset the image preview, status, and text editor for a new scan.

## Security Information
- **API Key Protection:** Handled via backend cURL communication.
- **MIME Inspection:** Validated rigorously server-side via PHP Fileinfo.
- **Temporary Handling:** Uploaded payloads are processed in temporary space and never permanently stored.
- **Output Sanitization:** Recognized text is handled securely to protect against XSS execution.

## Testing Instructions
- Test English and Simplified Chinese images (JPG/PNG).
- Verify file rejection triggers for files > 10 MB or invalid extensions.
- Test copying text to clipboard, layout printing, and downloading Word-compatible `.doc` files.
- Switch seamlessly between English (`?lang=en`) and Simplified Chinese (`?lang=zh`).
- Test responsiveness across mobile viewports or check the browser-based client-side offline version (`offline.html`).
