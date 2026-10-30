=== PDF Forge ===
Contributors: gunjanjaswal
Tags: pdf, embed, pdf viewer, document, shortcode
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Embed a PDF right inside a post or page with a clean, responsive viewer. Add it with a block or a shortcode. No third-party services.

== Description ==

PDF Forge drops a PDF straight into your content so readers can scroll through it without leaving the page. Pick a file from your Media Library (or paste a URL), choose a height, and you are done.

It uses the browser's own PDF viewer, so there is no heavy library to load and nothing is sent to an outside service. Your files stay on your site.

**What you get**

* A "PDF Forge" block for the editor with a live preview while you set it up.
* A `[pdf-forge]` shortcode for the classic editor, widgets, or anywhere shortcodes run.
* Control over the viewer height and the built-in toolbar.
* Fits the page to the viewer width and hides the thumbnail sidebar for a clean, full-width read.
* Responsive by default, so it fits phones as well as wide desktop layouts.
* Translation ready, properly escaped output, and no settings page to wade through.

== Installation ==

1. Upload the `pdf-forge` folder to `/wp-content/plugins/`, or install it from the Plugins screen.
2. Activate it through the **Plugins** menu in WordPress.
3. In the editor, add the **PDF Forge** block and choose a PDF, or drop the shortcode into your content.

== Usage ==

**Block**

Add the **PDF Forge** block, upload or select a PDF, then adjust the height and toolbar in the sidebar.

**Shortcode**

Embed by attachment ID:

`[pdf-forge id="123"]`

Or by URL, with a custom height and no toolbar:

`[pdf-forge url="https://example.com/files/report.pdf" height="800" toolbar="0"]`

Attributes:

* `id` — Media Library attachment ID of the PDF.
* `url` — Direct URL to a PDF (used if no `id` is given).
* `height` — Viewer height in pixels. Default `600`.
* `toolbar` — `1` to show the viewer toolbar, `0` to hide it. Default `1`.
* `title` — Accessible title for the viewer (read by screen readers).

== Frequently Asked Questions ==

= Does it work on mobile? =

Yes. The viewer is responsive and uses the device browser's own PDF support, so it reads well from phones up to wide desktop layouts.

= Can I hide the toolbar? =

Yes, set `toolbar="0"` on the shortcode or flip the toggle in the block sidebar. Hiding the toolbar is honoured by Chromium-based browsers; others simply ignore it.

= Does it send my PDFs anywhere? =

No. It points the browser at your own file. Nothing is uploaded to or proxied through any external service.

== Changelog ==

= 1.0.1 =
* The viewer now fits the page to its width and hides the thumbnail sidebar for a clean, full-width read.
* Added Ko-fi and contact links to the plugin row on the Plugins screen.
* Removed the separate download link; the viewer's own toolbar already offers downloads.

= 1.0.0 =
* Initial release: PDF Forge block and `[pdf-forge]` shortcode with height and toolbar controls.

== Upgrade Notice ==

= 1.0.1 =
Cleaner full-width viewer with the thumbnail sidebar hidden.

= 1.0.0 =
First release.
