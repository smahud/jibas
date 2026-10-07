<?php
// face_capture_upload.php
// Face Photo Capture — from local file (no upload to server, JS only)
// After capture, calls window.opener.acceptFaceCapture(base64)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Face Capture (Upload) — JIBAS</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:        #0d1117;
      --surface:   #161b22;
      --border:    #21262d;
      --accent:    #2f81f7;
      --accent-dim:#1a4a8a;
      --success:   #3fb950;
      --warn:      #d29922;
      --danger:    #f85149;
      --text:      #e6edf3;
      --muted:     #7d8590;
      --vf-corner: #58a6ff;
      --mono:      'JetBrains Mono', 'Fira Code', 'Consolas', monospace;
      --sans:      'Inter', system-ui, -apple-system, sans-serif;
    }

    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap');

    body {
      background: var(--bg);
      color: var(--text);
      font-family: var(--sans);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 24px 16px 48px;
    }

    header {
      width: 100%;
      max-width: 860px;
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 28px;
      padding-bottom: 20px;
      border-bottom: 1px solid var(--border);
    }
    .logo-mark {
      width: 36px; height: 36px;
      background: var(--accent);
      border-radius: 8px;
      display: grid;
      place-items: center;
    }
    .logo-mark svg { width: 20px; height: 20px; fill: #fff; }
    header h1 { font-size: 1rem; font-weight: 600; letter-spacing: .02em; }
    header span { font-size: .8rem; color: var(--muted); font-family: var(--mono); }

    .layout {
      width: 100%;
      max-width: 860px;
      display: grid;
      grid-template-columns: 1fr 300px;
      gap: 20px;
      align-items: start;
    }

    .panel {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 12px;
      overflow: hidden;
    }
    .panel-header {
      padding: 12px 16px;
      border-bottom: 1px solid var(--border);
      font-size: .75rem;
      font-family: var(--mono);
      color: var(--muted);
      letter-spacing: .08em;
      text-transform: uppercase;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .panel-header .dot { width: 7px; height: 7px; border-radius: 50%; background: var(--muted); }
    .panel-header .dot.live { background: var(--success); box-shadow: 0 0 6px var(--success); }

    /* ── Image viewport ── */
    .view-wrap {
      position: relative;
      width: 100%;
      background:
        linear-gradient(45deg, #1c2128 25%, transparent 25%),
        linear-gradient(-45deg, #1c2128 25%, transparent 25%),
        linear-gradient(45deg, transparent 75%, #1c2128 75%),
        linear-gradient(-45deg, transparent 75%, #1c2128 75%);
      background-size: 20px 20px;
      background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
      background-color: #000;
      cursor: crosshair;
      user-select: none;
      overflow: hidden;
      aspect-ratio: 4/3;
    }
    #img-canvas {
      position: absolute;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      display: block;
    }
    #overlay {
      position: absolute;
      inset: 0;
      pointer-events: none;
    }

    /* ── Empty / drop screen ── */
    #empty-screen {
      position: absolute;
      inset: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 14px;
      z-index: 10;
      background: #000a;
      border: 2px dashed var(--border);
      transition: border-color .15s, background .15s;
    }
    #empty-screen.drag-over { border-color: var(--accent); background: #2f81f722; }
    #empty-screen svg { opacity: .45; }
    #empty-screen p { font-size: .85rem; color: var(--muted); text-align: center; max-width: 260px; line-height: 1.5; }
    #empty-screen .or { font-size: .7rem; color: var(--muted); font-family: var(--mono); letter-spacing: .1em; }

    #file-input { display: none; }

    #browse-btn {
      padding: 10px 20px;
      background: var(--accent);
      border: none;
      border-radius: 8px;
      color: #fff;
      font-family: var(--sans);
      font-size: .85rem;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: background .15s;
    }
    #browse-btn:hover { background: #388bfd; }

    /* ── Status bar ── */
    .status-bar {
      padding: 8px 14px;
      font-family: var(--mono);
      font-size: .72rem;
      color: var(--muted);
      border-top: 1px solid var(--border);
      display: flex;
      gap: 18px;
      flex-wrap: wrap;
    }
    .status-bar span { display: flex; align-items: center; gap: 5px; }
    .status-bar b { color: var(--text); }

    /* ── Zoom bar ── */
    .zoom-bar {
      padding: 10px 14px;
      border-top: 1px solid var(--border);
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .zoom-bar svg { color: var(--muted); flex-shrink: 0; }
    .zoom-bar input[type="range"] {
      flex: 1;
      -webkit-appearance: none;
      appearance: none;
      height: 4px;
      background: var(--border);
      border-radius: 2px;
      outline: none;
    }
    .zoom-bar input[type="range"]::-webkit-slider-thumb {
      -webkit-appearance: none;
      width: 15px; height: 15px;
      border-radius: 50%;
      background: var(--accent);
      cursor: pointer;
      border: 2px solid #fff2;
      transition: transform .1s;
    }
    .zoom-bar input[type="range"]::-webkit-slider-thumb:hover { transform: scale(1.15); }
    .zoom-bar input[type="range"]::-moz-range-thumb {
      width: 15px; height: 15px;
      border-radius: 50%;
      background: var(--accent);
      cursor: pointer;
      border: 2px solid #fff2;
    }
    #zoom-val {
      font-family: var(--mono);
      font-size: .72rem;
      color: var(--text);
      width: 42px;
      text-align: right;
      flex-shrink: 0;
    }

    /* ── Controls panel ── */
    .controls { display: flex; flex-direction: column; }
    .ctrl-section { padding: 14px 16px; border-bottom: 1px solid var(--border); }
    .ctrl-section:last-child { border-bottom: none; }
    .ctrl-label {
      font-size: .68rem;
      font-family: var(--mono);
      color: var(--muted);
      letter-spacing: .1em;
      text-transform: uppercase;
      margin-bottom: 10px;
    }

    .size-options { display: flex; flex-direction: column; gap: 8px; }
    .size-btn {
      background: transparent;
      border: 1px solid var(--border);
      border-radius: 8px;
      color: var(--text);
      padding: 10px 14px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: border-color .15s, background .15s;
      font-size: .82rem;
    }
    .size-btn:hover { border-color: var(--accent); background: #1a4a8a22; }
    .size-btn.active { border-color: var(--accent); background: #1a4a8a44; color: #fff; }
    .size-btn .badge {
      font-family: var(--mono);
      font-size: .7rem;
      background: var(--border);
      padding: 2px 6px;
      border-radius: 4px;
      color: var(--muted);
      margin-left: auto;
    }
    .size-btn.active .badge { background: var(--accent); color: #fff; }
    .rect-preview { width: 32px; border: 2px solid var(--vf-corner); flex-shrink: 0; opacity: .7; }

    .hint { font-size: .75rem; color: var(--muted); line-height: 1.5; }
    .hint kbd {
      background: var(--border);
      border-radius: 4px;
      padding: 1px 5px;
      font-family: var(--mono);
      font-size: .68rem;
      color: var(--text);
    }

    #capture-btn {
      width: 100%;
      padding: 13px;
      background: var(--accent);
      border: none;
      border-radius: 8px;
      color: #fff;
      font-family: var(--sans);
      font-size: .9rem;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: background .15s, transform .1s;
    }
    #capture-btn:hover:not(:disabled) { background: #388bfd; }
    #capture-btn:active:not(:disabled) { transform: scale(.98); }
    #capture-btn:disabled { background: var(--border); color: var(--muted); cursor: not-allowed; }
    #capture-btn svg { width: 18px; height: 18px; }

    #change-img-btn {
      width: 100%;
      padding: 10px;
      background: transparent;
      border: 1px solid var(--border);
      border-radius: 8px;
      color: var(--muted);
      font-size: .82rem;
      cursor: pointer;
      font-family: var(--sans);
      transition: border-color .15s, color .15s;
    }
    #change-img-btn:hover { border-color: var(--accent); color: var(--accent); }

    /* ── Result panel ── */
    #result-panel { grid-column: 1 / -1; display: none; }
    #result-panel.visible { display: block; }
    .result-inner {
      padding: 16px;
      display: grid;
      grid-template-columns: auto 1fr;
      gap: 16px;
      align-items: start;
    }
    #preview-img {
      border: 2px solid var(--accent);
      border-radius: 6px;
      background: #000;
      display: block;
      image-rendering: pixelated;
    }
    .result-meta { display: flex; flex-direction: column; gap: 10px; }
    .meta-row { display: flex; flex-direction: column; gap: 4px; }
    .meta-key { font-size: .68rem; font-family: var(--mono); color: var(--muted); text-transform: uppercase; letter-spacing: .08em; }
    .meta-val { font-family: var(--mono); font-size: .78rem; color: var(--text); }

    #b64-box {
      background: var(--bg);
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 10px 12px;
      font-family: var(--mono);
      font-size: .68rem;
      color: var(--muted);
      word-break: break-all;
      max-height: 90px;
      overflow-y: auto;
      white-space: pre-wrap;
      line-height: 1.5;
    }
    .copy-btn {
      align-self: flex-start;
      background: transparent;
      border: 1px solid var(--border);
      border-radius: 6px;
      color: var(--muted);
      padding: 5px 10px;
      font-size: .75rem;
      cursor: pointer;
      font-family: var(--mono);
      transition: border-color .15s, color .15s;
      display: flex; align-items: center; gap: 6px;
    }
    .copy-btn:hover { border-color: var(--accent); color: var(--accent); }
    .copy-btn.copied { border-color: var(--success); color: var(--success); }

    .send-btn {
      width: 100%;
      padding: 11px 14px;
      background: var(--success);
      border: none;
      border-radius: 8px;
      color: #fff;
      font-family: var(--sans);
      font-size: .88rem;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: background .15s;
    }
    .send-btn:hover { background: #45c55a; }
    .send-btn.sent { background: #21262d; color: var(--success); border: 1px solid var(--success); cursor: default; }
    .send-btn.send-error { background: #21262d; color: var(--danger); border: 1px solid var(--danger); cursor: default; }

    .no-opener-notice {
      font-size: .74rem;
      color: var(--warn);
      font-family: var(--mono);
      padding: 7px 10px;
      background: #d2992211;
      border: 1px solid var(--warn);
      border-radius: 6px;
      line-height: 1.5;
    }

    #flash {
      position: absolute;
      inset: 0;
      background: #fff;
      opacity: 0;
      pointer-events: none;
      z-index: 20;
      transition: opacity .05s;
    }
    #flash.on { opacity: .8; }

    #canvas { display: none; }

    @media (max-width: 680px) {
      .layout { grid-template-columns: 1fr; }
      #result-panel { grid-column: 1; }
    }
  </style>
</head>
<body>

<header>
  <div class="logo-mark">
    <svg viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
  </div>
  <div>
    <h1>Face Capture &mdash; From File</h1>
    <span>JIBAS &middot; Jendela Sekolah</span>
  </div>
</header>

<div class="layout">

  <!-- Image Panel -->
  <div class="panel">
    <div class="panel-header">
      <div class="dot" id="live-dot"></div>
      <span id="img-status">No image loaded</span>
    </div>

    <div class="view-wrap" id="view-wrap">
      <canvas id="img-canvas"></canvas>
      <canvas id="overlay"></canvas>
      <div id="flash"></div>

      <div id="empty-screen">
        <svg width="56" height="56" viewBox="0 0 24 24" fill="white"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
        <p>Drag &amp; drop a photo here, or browse your computer.<br>Nothing is uploaded &mdash; the image stays in your browser.</p>
        <span class="or">JPG &middot; PNG &middot; WEBP</span>
        <button id="browse-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M9.17 6l2 2H20v10H4V6h5.17M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>
          Browse Files
        </button>
        <input type="file" id="file-input" accept="image/*">
      </div>
    </div>

    <div class="zoom-bar">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14zM7 9h2v2h1V9h2V8h-2V6h-1v2H7z"/></svg>
      <input type="range" id="zoom-slider" min="50" max="300" value="100" disabled>
      <span id="zoom-val">100%</span>
    </div>

    <div class="status-bar">
      <span>Rect: <b id="stat-pos">— , —</b></span>
      <span>Size: <b id="stat-size">130 &times; 190</b></span>
      <span>Image: <b id="stat-res">—</b></span>
    </div>
  </div>

  <!-- Controls Panel -->
  <div class="panel controls">
    <div class="panel-header">
      <div class="dot"></div>
      <span>Controls</span>
    </div>

    <div class="ctrl-section">
      <div class="ctrl-label">Capture Size</div>
      <div class="size-options">
        <button class="size-btn active" data-w="130" data-h="190">
          <div class="rect-preview" style="height:48px;"></div>
          <div>
            <div style="font-weight:500">Portrait S</div>
            <div style="font-size:.72rem;color:var(--muted);margin-top:2px">Standard ID photo</div>
          </div>
          <span class="badge">130&times;190</span>
        </button>
        <button class="size-btn" data-w="260" data-h="380">
          <div class="rect-preview" style="height:64px;"></div>
          <div>
            <div style="font-weight:500">Portrait L</div>
            <div style="font-size:.72rem;color:var(--muted);margin-top:2px">High-res ID photo</div>
          </div>
          <span class="badge">260&times;380</span>
        </button>
      </div>
    </div>

    <div class="ctrl-section">
      <div class="ctrl-label">How to Position</div>
      <div class="hint">
        Use the <span style="color:var(--accent)">zoom slider</span> to scale the photo, then drag the <span style="color:var(--accent)">blue viewfinder</span> over the face.<br><br>
        <kbd>Drag</kbd> to move &nbsp;&middot;&nbsp; <kbd>Click</kbd> outside to re-center
      </div>
    </div>

    <div class="ctrl-section">
      <button id="capture-btn" disabled>
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 15.2c-1.77 0-3.2-1.43-3.2-3.2s1.43-3.2 3.2-3.2 3.2 1.43 3.2 3.2-1.43 3.2-3.2 3.2zM9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/></svg>
        Capture Face Photo
      </button>
    </div>

    <div class="ctrl-section" id="change-section" style="display:none">
      <button id="change-img-btn">&#8635; &nbsp;Choose Different Photo</button>
    </div>
  </div>

  <!-- Result Panel -->
  <div class="panel" id="result-panel">
    <div class="panel-header">
      <div class="dot" style="background:var(--success);box-shadow:0 0 6px var(--success)"></div>
      <span>Capture Result</span>
    </div>
    <div class="result-inner">
      <img id="preview-img" alt="Captured face">
      <div class="result-meta">

        <div class="meta-row">
          <span class="meta-key">Dimensions</span>
          <span class="meta-val" id="meta-dim">—</span>
        </div>
        <div class="meta-row">
          <span class="meta-key">Format</span>
          <span class="meta-val">image/jpeg &middot; quality 95%</span>
        </div>
        <div class="meta-row">
          <span class="meta-key">Base64 String</span>
          <div id="b64-box"></div>
          <button class="copy-btn" id="copy-btn">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
            Copy base64
          </button>
        </div>
        <div class="meta-row">
          <span class="meta-key">Captured At</span>
          <span class="meta-val" id="meta-time">—</span>
        </div>

        <button class="send-btn" id="send-result-btn" style="display:none">
          <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
          <span class="send-label">Send to Opener Page</span>
        </button>

        <div class="no-opener-notice" id="no-opener-notice" style="display:none">
          &#9888;&nbsp; No opener detected. Open this page from the main page to send results automatically.
        </div>

      </div>
    </div>
  </div>

</div>

<canvas id="canvas"></canvas>

<script>
(function () {
  /* ── State ── */
  var img             = null;     // loaded HTMLImageElement
  var imgReady        = false;
  var zoom             = 1.0;     // 1.0 = 100%
  var baseScale        = 1.0;     // scale to fit image into viewport at zoom=100%
  var panX = 0, panY = 0;         // image top-left position in viewport coords (for centering)
  var rectW = 130, rectH = 190;
  var rectX = 0,  rectY = 0;
  var dragging   = false;
  var dragOffX   = 0, dragOffY = 0;
  var lastBase64 = '';

  /* ── Elements ── */
  var viewWrap        = document.getElementById('view-wrap');
  var imgCanvas        = document.getElementById('img-canvas');
  var ictx              = imgCanvas.getContext('2d');
  var overlay          = document.getElementById('overlay');
  var ctx               = overlay.getContext('2d');
  var canvas            = document.getElementById('canvas');
  var cctx               = canvas.getContext('2d');
  var emptyScreen      = document.getElementById('empty-screen');
  var browseBtn        = document.getElementById('browse-btn');
  var fileInput         = document.getElementById('file-input');
  var captureBtn       = document.getElementById('capture-btn');
  var changeBtn         = document.getElementById('change-img-btn');
  var changeSec          = document.getElementById('change-section');
  var resultPanel      = document.getElementById('result-panel');
  var previewImg       = document.getElementById('preview-img');
  var b64Box             = document.getElementById('b64-box');
  var copyBtn            = document.getElementById('copy-btn');
  var liveDot            = document.getElementById('live-dot');
  var imgStatus         = document.getElementById('img-status');
  var statPos            = document.getElementById('stat-pos');
  var statSize           = document.getElementById('stat-size');
  var statRes            = document.getElementById('stat-res');
  var metaDim            = document.getElementById('meta-dim');
  var metaTime           = document.getElementById('meta-time');
  var flash               = document.getElementById('flash');
  var sizeBtns           = document.querySelectorAll('.size-btn');
  var zoomSlider        = document.getElementById('zoom-slider');
  var zoomVal             = document.getElementById('zoom-val');
  var sendResultBtn    = document.getElementById('send-result-btn');
  var noOpenerNotice   = document.getElementById('no-opener-notice');

  /* ── File loading (client-side only, no upload) ── */
  browseBtn.addEventListener('click', function () { fileInput.click(); });
  fileInput.addEventListener('change', function (e) {
    if (e.target.files && e.target.files[0]) loadFile(e.target.files[0]);
  });

  // Drag & drop support
  ['dragenter', 'dragover'].forEach(function (evt) {
    emptyScreen.addEventListener(evt, function (e) {
      e.preventDefault(); e.stopPropagation();
      emptyScreen.classList.add('drag-over');
    });
  });
  ['dragleave', 'drop'].forEach(function (evt) {
    emptyScreen.addEventListener(evt, function (e) {
      e.preventDefault(); e.stopPropagation();
      emptyScreen.classList.remove('drag-over');
    });
  });
  emptyScreen.addEventListener('drop', function (e) {
    var files = e.dataTransfer.files;
    if (files && files[0] && files[0].type.indexOf('image/') === 0) loadFile(files[0]);
  });

  function loadFile(file) {
    if (!file.type || file.type.indexOf('image/') !== 0) return;

    var reader = new FileReader();
    reader.onload = function (e) {
      var image = new Image();
      image.onload = function () {
        img = image;
        imgReady = true;
        onImageLoaded();
      };
      image.src = e.target.result;  // data URL — stays in-browser, never uploaded
    };
    reader.readAsDataURL(file);
  }

  function onImageLoaded() {
    emptyScreen.style.display = 'none';
    liveDot.classList.add('live');
    imgStatus.textContent = 'Image loaded';
    captureBtn.disabled = false;
    zoomSlider.disabled = false;
    changeSec.style.display = 'block';
    statRes.textContent = img.naturalWidth + ' \u00d7 ' + img.naturalHeight;

    resizeCanvases();
    fitImageToViewport();
    centerRect();
    requestAnimationFrame(drawLoop);
  }

  changeBtn.addEventListener('click', function () {
    imgReady = false;
    img = null;
    fileInput.value = '';
    emptyScreen.style.display = 'flex';
    liveDot.classList.remove('live');
    imgStatus.textContent = 'No image loaded';
    captureBtn.disabled = true;
    zoomSlider.disabled = true;
    changeSec.style.display = 'none';
    resultPanel.classList.remove('visible');
    ictx.clearRect(0, 0, imgCanvas.width, imgCanvas.height);
    ctx.clearRect(0, 0, overlay.width, overlay.height);
  });

  /* ── Canvas sizing ── */
  function resizeCanvases() {
    var w = viewWrap.clientWidth, h = viewWrap.clientHeight;
    imgCanvas.width  = w; imgCanvas.height = h;
    overlay.width    = w; overlay.height   = h;
  }
  window.addEventListener('resize', function () {
    if (!imgReady) return;
    resizeCanvases();
    fitImageToViewport();
    clampRect();
  });

  // Fit image inside the viewport at 100% zoom slider value (baseScale = "fit" scale)
  function fitImageToViewport() {
    var vw = imgCanvas.width, vh = imgCanvas.height;
    var iw = img.naturalWidth, ih = img.naturalHeight;
    baseScale = Math.min(vw / iw, vh / ih);
    zoom = parseInt(zoomSlider.value, 10) / 100;
    updatePan();
  }

  function updatePan() {
    var vw = imgCanvas.width, vh = imgCanvas.height;
    var iw = img.naturalWidth * baseScale * zoom;
    var ih = img.naturalHeight * baseScale * zoom;
    panX = (vw - iw) / 2;
    panY = (vh - ih) / 2;
  }

  /* ── Zoom slider ── */
  zoomSlider.addEventListener('input', function () {
    if (!imgReady) return;
    zoom = parseInt(zoomSlider.value, 10) / 100;
    zoomVal.textContent = zoomSlider.value + '%';
    updatePan();
    clampRect();
  });

  /* ── Size buttons ── */
  sizeBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      sizeBtns.forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      rectW = parseInt(btn.dataset.w);
      rectH = parseInt(btn.dataset.h);
      statSize.textContent = rectW + ' \u00d7 ' + rectH;
      if (imgReady) centerRect();
    });
  });

  function centerRect() {
    rectX = (overlay.width  - rectW) / 2;
    rectY = (overlay.height - rectH) / 2;
    clampRect();
  }
  function clampRect() {
    rectX = Math.max(0, Math.min(overlay.width  - rectW, rectX));
    rectY = Math.max(0, Math.min(overlay.height - rectH, rectY));
  }

  /* ── Draw loop ── */
  function drawLoop() {
    if (!imgReady) return;
    drawImage();
    ctx.clearRect(0, 0, overlay.width, overlay.height);
    drawDim();
    drawRect();
    updateStats();
    requestAnimationFrame(drawLoop);
  }

  function drawImage() {
    ictx.clearRect(0, 0, imgCanvas.width, imgCanvas.height);
    var dw = img.naturalWidth  * baseScale * zoom;
    var dh = img.naturalHeight * baseScale * zoom;
    ictx.drawImage(img, panX, panY, dw, dh);
  }

  function drawDim() {
    ctx.fillStyle = 'rgba(0,0,0,0.45)';
    ctx.fillRect(0,           0,           overlay.width,            rectY);
    ctx.fillRect(0,           rectY+rectH, overlay.width,             overlay.height - rectY - rectH);
    ctx.fillRect(0,           rectY,       rectX,                     rectH);
    ctx.fillRect(rectX+rectW, rectY,       overlay.width-rectX-rectW, rectH);
  }

  function drawRect() {
    var x = rectX, y = rectY, w = rectW, h = rectH, c = 16;

    ctx.save();
    ctx.strokeStyle = 'rgba(47,129,247,0.55)';
    ctx.lineWidth = 1;
    ctx.setLineDash([6, 5]);
    ctx.lineDashOffset = -(Date.now() / 60) % 22;
    ctx.strokeRect(x, y, w, h);
    ctx.restore();

    ctx.strokeStyle = '#58a6ff';
    ctx.lineWidth = 2.5;
    ctx.lineCap = 'square';
    ctx.setLineDash([]);

    [[x,   y,   1, 0, 0, 1],
     [x+w, y,  -1, 0, 0, 1],
     [x,   y+h, 1, 0, 0,-1],
     [x+w, y+h,-1, 0, 0,-1]
    ].forEach(function (p) {
      ctx.beginPath();
      ctx.moveTo(p[0] + p[2]*c, p[1]);
      ctx.lineTo(p[0], p[1]);
      ctx.lineTo(p[0], p[1] + p[5]*c);
      ctx.stroke();
    });

    var mx = x + w/2, my = y + h/2, cl = 8;
    ctx.strokeStyle = 'rgba(88,166,255,0.5)';
    ctx.lineWidth = 1;
    ctx.beginPath();
    ctx.moveTo(mx-cl, my); ctx.lineTo(mx+cl, my);
    ctx.moveTo(mx, my-cl); ctx.lineTo(mx, my+cl);
    ctx.stroke();
  }

  function updateStats() {
    statPos.textContent = Math.round(rectX) + ' , ' + Math.round(rectY);
  }

  /* ── Drag logic ── */
  function getPos(e) {
    var r = overlay.getBoundingClientRect();
    var cx = (e.touches ? e.touches[0].clientX : e.clientX) - r.left;
    var cy = (e.touches ? e.touches[0].clientY : e.clientY) - r.top;
    return { cx: cx, cy: cy };
  }
  function insideRect(cx, cy) {
    return cx >= rectX && cx <= rectX+rectW && cy >= rectY && cy <= rectY+rectH;
  }

  overlay.style.pointerEvents = 'auto';
  overlay.addEventListener('mousedown',  onDown);
  overlay.addEventListener('touchstart', onDown, { passive: false });

  function onDown(e) {
    if (!imgReady) return;
    e.preventDefault();
    var p = getPos(e);
    if (insideRect(p.cx, p.cy)) {
      dragging = true;
      dragOffX = p.cx - rectX;
      dragOffY = p.cy - rectY;
      overlay.style.cursor = 'grabbing';
    } else {
      rectX = p.cx - rectW / 2;
      rectY = p.cy - rectH / 2;
      clampRect();
    }
  }

  window.addEventListener('mousemove', onMove);
  window.addEventListener('touchmove', onMove, { passive: false });
  function onMove(e) {
    if (!dragging) return;
    e.preventDefault();
    var p = getPos(e);
    rectX = p.cx - dragOffX;
    rectY = p.cy - dragOffY;
    clampRect();
  }

  window.addEventListener('mouseup',  onUp);
  window.addEventListener('touchend', onUp);
  function onUp() { dragging = false; overlay.style.cursor = 'crosshair'; }

  /* ── Capture ── */
  captureBtn.addEventListener('click', capture);

  function capture() {
    if (!imgReady) return;

    // Map overlay rect coords -> source image pixel coords.
    // The image is drawn at (panX, panY) with scale = baseScale * zoom.
    var totalScale = baseScale * zoom;

    var sx = Math.round((rectX - panX) / totalScale);
    var sy = Math.round((rectY - panY) / totalScale);
    var sw = Math.round(rectW / totalScale);
    var sh = Math.round(rectH / totalScale);

    canvas.width  = rectW;
    canvas.height = rectH;

    // Fill background black first, in case the rect extends past image edges (zoomed out / panned)
    cctx.fillStyle = '#000';
    cctx.fillRect(0, 0, rectW, rectH);
    cctx.drawImage(img, sx, sy, sw, sh, 0, 0, rectW, rectH);

    // Flash
    flash.classList.add('on');
    setTimeout(function () { flash.classList.remove('on'); }, 150);

    // Export
    var dataURL = canvas.toDataURL('image/jpeg', 0.95);
    var b64     = dataURL.split(',')[1];
    lastBase64  = b64;

    // Show result
    previewImg.src          = dataURL;
    previewImg.style.width  = rectW + 'px';
    previewImg.style.height = rectH + 'px';
    metaDim.textContent     = rectW + ' \u00d7 ' + rectH + ' px';
    metaTime.textContent    = new Date().toLocaleString();
    b64Box.textContent      = b64.substring(0, 200) + '\u2026  [' + Math.round(b64.length/1024) + ' KB]';
    resultPanel.classList.add('visible');

    // Return result to opener
    if (window.opener && !window.opener.closed &&
        typeof window.opener.acceptFaceCapture === 'function') {
      sendResultBtn.style.display  = 'flex';
      noOpenerNotice.style.display = 'none';
      setSendState('idle');
      try {
        window.opener.acceptFaceCapture(b64);
        setSendState('sent');
      } catch (err) {
        setSendState('error', err.message);
      }
    } else {
      sendResultBtn.style.display  = 'none';
      noOpenerNotice.style.display = 'block';
    }

    resultPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  function setSendState(state, msg) {
    sendResultBtn.classList.remove('sent', 'send-error');
    if (state === 'idle') {
      sendResultBtn.querySelector('.send-label').textContent = 'Send to Opener Page';
    } else if (state === 'sent') {
      sendResultBtn.classList.add('sent');
      sendResultBtn.querySelector('.send-label').textContent =
        '\u2713  Sent to opener \u2014 closing window\u2026';
      setTimeout(function () { window.close(); }, 1800);
    } else if (state === 'error') {
      sendResultBtn.classList.add('send-error');
      sendResultBtn.querySelector('.send-label').textContent =
        '\u2717  Failed: ' + (msg || 'unknown error');
    }
  }

  sendResultBtn.addEventListener('click', function () {
    if (!lastBase64 || sendResultBtn.classList.contains('sent')) return;
    try {
      window.opener.acceptFaceCapture(lastBase64);
      setSendState('sent');
    } catch (err) {
      setSendState('error', err.message);
    }
  });

  /* ── Copy base64 ── */
  copyBtn.addEventListener('click', function () {
    if (!lastBase64) return;
    navigator.clipboard.writeText(lastBase64).then(function () {
      copyBtn.classList.add('copied');
      copyBtn.querySelector('svg').style.display = 'none';
      copyBtn.childNodes[copyBtn.childNodes.length-1].textContent = ' Copied!';
      setTimeout(function () {
        copyBtn.classList.remove('copied');
        copyBtn.querySelector('svg').style.display = '';
        copyBtn.childNodes[copyBtn.childNodes.length-1].textContent = ' Copy base64';
      }, 2000);
    });
  });

})();
</script>
</body>
</html>
