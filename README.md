<div align="center">

# 📄 PDF Forge

### Embed PDFs directly inside your WordPress posts and pages

A lightweight PDF embed plugin with a Gutenberg block **and** a shortcode. It uses the browser's own PDF viewer, so there's no bulky library to load and nothing ever leaves your server.

[![Version](https://img.shields.io/badge/version-1.0.1-2563eb.svg?style=for-the-badge)](https://github.com/gunjanjaswal/PDF-Forge/releases)
[![WordPress](https://img.shields.io/badge/WordPress-6.0%E2%80%937.1-21759b.svg?style=for-the-badge&logo=wordpress&logoColor=white)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777bb4.svg?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-GPL--2.0%2B-3fb950.svg?style=for-the-badge)](LICENSE)

</div>

---

## ✨ What it does

Most PDF embeds either drop a plain download link, or pull in a megabyte of JavaScript to render something the browser already renders perfectly well. PDF Forge takes the lighter road: it points the browser at your file and lets its built-in viewer do the work. You pick a PDF, set a height, and your readers scroll through it without ever leaving the page.

No account, no API key, no external request. The file stays on your own site.

## 🎯 Features

| | |
|---|---|
| 🧱 **Block editor** | A **PDF Forge** block with a live preview while you set it up. |
| 🔤 **Shortcode** | `[pdf-forge]` for the classic editor, widgets, or template files. |
| 📐 **Adjustable height** | Set the viewer to any pixel height you like. |
| 🛠️ **Toolbar toggle** | Show or hide the viewer's built-in toolbar. |
| 🖥️ **Full-width read** | Fits the page to the viewer width and hides the thumbnail sidebar. |
| 📱 **Responsive** | Fits phones and wide desktop layouts out of the box. |
| 🌍 **Translation ready** | All strings are wrapped and ready for localisation. |
| 🔒 **Safe output** | Everything is escaped and sanitised the WordPress way. |

## 📦 Installation

**From this repository**

1. Download the latest release, or clone the repo.
2. Upload the `pdf-forge` folder to `wp-content/plugins/`.
3. Activate **PDF Forge** on the Plugins screen.

**From the WordPress admin**

1. Go to **Plugins → Add New → Upload Plugin**.
2. Choose `pdf-forge.zip` and click **Install Now**.
3. Click **Activate**.

## 🚀 Usage

### Using the block

Add the **PDF Forge** block in the editor, upload or pick a PDF, then adjust the height and toggles in the block sidebar. The preview updates as you go.

### Using the shortcode

Embed a PDF from the Media Library by its attachment ID:

```text
[pdf-forge id="123"]
```

Or embed any PDF by URL, taller and with the toolbar hidden:

```text
[pdf-forge url="https://example.com/files/report.pdf" height="800" toolbar="0"]
```

### Shortcode attributes

| Attribute  | Description                                                   | Default |
|------------|---------------------------------------------------------------|---------|
| `id`       | Media Library attachment ID of the PDF.                       | —       |
| `url`      | Direct URL to a PDF (used when no `id` is given).             | —       |
| `height`   | Viewer height in pixels.                                      | `600`   |
| `toolbar`  | `1` shows the viewer toolbar, `0` hides it.                   | `1`     |
| `title`    | Accessible title for the viewer, read out by screen readers. | —       |

## ❓ FAQ

**Does it work on mobile?**
Yes. The viewer is responsive and uses the device browser's own PDF support, so it reads well from phones up to wide desktop layouts.

**Can I hide the toolbar?**
Set `toolbar="0"` or flip the toggle in the block sidebar. Chromium-based browsers honour it; others simply ignore the request and show their own controls.

**Where do my PDFs go?**
Nowhere new. The browser loads the file straight from your site. Nothing is uploaded to or routed through any outside service.

**Can I embed a PDF hosted somewhere else?**
Yes, pass its full URL to the `url` attribute. Bear in mind some hosts block being embedded in a frame, which is their setting, not the plugin's.

## 🧩 WordPress.org ready

PDF Forge is built to the [WordPress.org plugin guidelines](https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/): a proper `readme.txt`, a unique prefix, escaped output, sanitised input, GPL-2.0-or-later licensing, and no external calls. You can run it through the official [Plugin Check](https://wordpress.org/plugins/plugin-check/) plugin before submission.

## 🛠️ Development

The block is written in plain `wp.element` calls with no build step, so the source in `assets/js/block.js` is exactly what runs. Clone the repo into `wp-content/plugins/` and start editing.

```text
pdf-forge/
├── pdf-forge.php              # Plugin bootstrap
├── readme.txt                # WordPress.org readme
├── includes/
│   └── class-pdf-forge.php   # Block + shortcode + shared renderer
├── block/
│   └── block.json            # Block metadata
└── assets/
    ├── css/pdf-forge.css     # Front-end + editor styles
    └── js/block.js           # Block editor (no build required)
```

## 💛 Support

If PDF Forge saves you some time, a coffee is always appreciated.

<a href="https://ko-fi.com/gunjanjaswal"><img src="https://img.shields.io/badge/Buy%20me%20a%20coffee-Ko--fi-ff5e5b.svg?style=for-the-badge&logo=ko-fi&logoColor=white" alt="Ko-fi"></a>

## 👤 Author

**Gunjan Jaswal**

- 🌐 [gunjanjaswal.me](https://www.gunjanjaswal.me)
- ✉️ [hello@gunjanjaswal.me](mailto:hello@gunjanjaswal.me)
- 💻 [github.com/gunjanjaswal](https://github.com/gunjanjaswal)

## 📜 License

Released under the [GPL-2.0-or-later](LICENSE) license.
