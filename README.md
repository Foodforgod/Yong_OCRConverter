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
1. **Open the Application:** Navigate to `index.php` in your browser. Use the language links at the top header to toggle between **English** and **简体中文**.
2. **Choose an Image:** Click the **"Choose Image"** button to select an image from your device. 
   - *Supported formats:* JPG, JPEG, PNG, GIF, BMP, TIFF, WEBP.
   - *Size limit:* Maximum 10 MB.
3. **Preview Image:** Once selected, a thumbnail preview and the exact file size will appear on screen.
4. **Start OCR:** Click the **"Start OCR"** button. The status box will dynamically update through the workflow stages:
   - `Starting...` → `Uploading...` → `Recognizing...` → `Complete`
5. **Edit Recognized Text:** The extracted text will automatically load into the responsive textarea. You can safely edit typos, correct characters, or format line breaks directly.
6. **Use Actions:**
   - **Copy Text:** Copies the text directly to your clipboard.
   - **Print:** Opens a formatted print layout window.
   - **Export to Word:** Downloads the text as a Microsoft Word-compatible `.doc` file.
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