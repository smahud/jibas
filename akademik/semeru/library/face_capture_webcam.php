<?php
// face_capture.php
// Face Photo Capture — popup window
// After capture, calls window.opener.acceptFaceCapture(base64)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Face Capture — JIBAS</title>
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
    .panel-header .dot {
      width: 7px; height: 7px;
      border-radius: 50%;
      background: var(--muted);
    }
    .panel-header .dot.live {
      background: var(--success);
      box-shadow: 0 0 6px var(--success);
      animation: pulse 2s infinite;
    }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

    .camera-wrap {
      position: relative;
      width: 100%;
      background: #000;
      cursor: crosshair;
      user-select: none;
      overflow: hidden;
      aspect-ratio: 4/3;
    }
    #video {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    #overlay {
      position: absolute;
      inset: 0;
      pointer-events: none;
    }

    #permission-screen {
      position: absolute;
      inset: 0;
      background: #000d;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 16px;
      z-index: 10;
    }
    #permission-screen svg { opacity: .5; }
    #permission-screen p { font-size: .85rem; color: var(--muted); text-align: center; max-width: 220px; }

    .status-bar {
      padding: 8px 14px;
      font-family: var(--mono);
      font-size: .72rem;
      color: var(--muted);
      border-top: 1px solid var(--border);
      display: flex;
      gap: 20px;
    }
    .status-bar span { display: flex; align-items: center; gap: 5px; }
    .status-bar b { color: var(--text); }

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
    .rect-preview {
      width: 32px;
      border: 2px solid var(--vf-corner);
      flex-shrink: 0;
      opacity: .7;
    }

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
      letter-spacing: .01em;
    }
    #capture-btn:hover:not(:disabled) { background: #388bfd; }
    #capture-btn:active:not(:disabled) { transform: scale(.98); }
    #capture-btn:disabled { background: var(--border); color: var(--muted); cursor: not-allowed; }
    #capture-btn svg { width: 18px; height: 18px; }

    #start-camera-btn {
      width: 100%;
      padding: 11px;
      background: transparent;
      border: 1px solid var(--accent);
      border-radius: 8px;
      color: var(--accent);
      font-family: var(--sans);
      font-size: .85rem;
      font-weight: 500;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: background .15s;
    }
    #start-camera-btn:hover { background: #1a4a8a33; }

    /* ── Result Panel ── */
    #result-panel {
      grid-column: 1 / -1;
      display: none;
    }
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

    /* Send to Opener button */
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
      transition: background .15s, opacity .15s;
    }
    .send-btn:hover { background: #45c55a; }
    .send-btn.sent { background: #21262d; color: var(--success); border: 1px solid var(--success); cursor: default; }
    .send-btn.send-error { background: #21262d; color: var(--danger); border: 1px solid var(--danger); cursor: default; }

    /* No-opener notice */
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
    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
  </div>
  <div>
    <h1>Face Photo Capture</h1>
    <span>JIBAS &middot; Jendela Sekolah</span>
  </div>
</header>

<div class="layout">

  <!-- Camera Panel -->
  <div class="panel">
    <div class="panel-header">
      <div class="dot" id="live-dot"></div>
      <span id="cam-status">Camera &mdash; waiting for permission</span>
    </div>

    <div class="camera-wrap" id="camera-wrap">
      <video id="video" autoplay muted playsinline></video>
      <canvas id="overlay"></canvas>
      <div id="flash"></div>

      <div id="permission-screen">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="white"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg>
        <p>Camera access is required to capture face photos</p>
        <button id="start-camera-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1C5.93 1 1 5.93 1 12s4.93 11 11 11 11-4.93 11-11S18.07 1 12 1zm5 12h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
          Enable Camera
        </button>
      </div>
    </div>

    <div class="status-bar">
      <span>Rect: <b id="stat-pos">— , —</b></span>
      <span>Size: <b id="stat-size">130 &times; 190</b></span>
      <span>Cam: <b id="stat-res">—</b></span>
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
        Click &amp; drag the <span style="color:var(--accent)">blue viewfinder</span> over the live image to frame the face.<br><br>
        <kbd>Drag</kbd> to move &nbsp;&middot;&nbsp; <kbd>Click</kbd> outside to re-center
      </div>
    </div>

    <div class="ctrl-section">
      <button id="capture-btn" disabled>
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 15.2c-1.77 0-3.2-1.43-3.2-3.2s1.43-3.2 3.2-3.2 3.2 1.43 3.2 3.2-1.43 3.2-3.2 3.2zM9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/></svg>
        Capture Face Photo
      </button>
    </div>

    <div class="ctrl-section" id="retake-section" style="display:none">
      <button id="retake-btn" style="width:100%;padding:10px;background:transparent;border:1px solid var(--border);border-radius:8px;color:var(--muted);font-size:.82rem;cursor:pointer;font-family:var(--sans);">
        &#8635; &nbsp;Retake
      </button>
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

        <!-- Send to opener -->
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
  let stream      = null;
  let cameraReady = false;
  let rectW = 130, rectH = 190;
  let rectX = 0,  rectY = 0;
  let dragging    = false;
  let dragOffX    = 0, dragOffY = 0;
  let lastBase64  = '';

  /* ── Elements ── */
  const video           = document.getElementById('video');
  const overlay         = document.getElementById('overlay');
  const ctx             = overlay.getContext('2d');
  const canvas          = document.getElementById('canvas');
  const cctx            = canvas.getContext('2d');
  const permScreen      = document.getElementById('permission-screen');
  const startBtn        = document.getElementById('start-camera-btn');
  const captureBtn      = document.getElementById('capture-btn');
  const retakeBtn       = document.getElementById('retake-btn');
  const retakeSec       = document.getElementById('retake-section');
  const resultPanel     = document.getElementById('result-panel');
  const previewImg      = document.getElementById('preview-img');
  const b64Box          = document.getElementById('b64-box');
  const copyBtn         = document.getElementById('copy-btn');
  const liveDot         = document.getElementById('live-dot');
  const camStatus       = document.getElementById('cam-status');
  const statPos         = document.getElementById('stat-pos');
  const statSize        = document.getElementById('stat-size');
  const statRes         = document.getElementById('stat-res');
  const metaDim         = document.getElementById('meta-dim');
  const metaTime        = document.getElementById('meta-time');
  const flash           = document.getElementById('flash');
  const cameraWrap      = document.getElementById('camera-wrap');
  const sizeBtns        = document.querySelectorAll('.size-btn');
  const sendResultBtn   = document.getElementById('send-result-btn');
  const noOpenerNotice  = document.getElementById('no-opener-notice');

  /* ── Camera start ── */
  startBtn.addEventListener('click', startCamera);

  async function startCamera() {
    try {
      stream = await navigator.mediaDevices.getUserMedia({
        video: { width: { ideal: 1280 }, height: { ideal: 720 }, facingMode: 'user' },
        audio: false
      });
      video.srcObject = stream;
      video.onloadedmetadata = () => {
        video.play();
        cameraReady = true;
        permScreen.style.display = 'none';
        liveDot.classList.add('live');
        camStatus.textContent = 'Camera \u2014 live';
        captureBtn.disabled = false;
        statRes.textContent = video.videoWidth + ' \u00d7 ' + video.videoHeight;
        resizeOverlay();
        centerRect();
        requestAnimationFrame(drawLoop);
      };
    } catch (e) {
      camStatus.textContent = 'Permission denied or no camera found';
      permScreen.querySelector('p').textContent =
        'Access was denied. Please allow camera access in your browser settings and reload.';
    }
  }

  /* ── Overlay sizing ── */
  function resizeOverlay() {
    overlay.width  = cameraWrap.clientWidth;
    overlay.height = cameraWrap.clientHeight;
  }
  window.addEventListener('resize', () => { if (cameraReady) { resizeOverlay(); clampRect(); } });

  function centerRect() {
    rectX = (overlay.width  - rectW) / 2;
    rectY = (overlay.height - rectH) / 2;
    clampRect();
  }

  function clampRect() {
    rectX = Math.max(0, Math.min(overlay.width  - rectW, rectX));
    rectY = Math.max(0, Math.min(overlay.height - rectH, rectY));
  }

  /* ── Size buttons ── */
  sizeBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      sizeBtns.forEach(function(b) { b.classList.remove('active'); });
      btn.classList.add('active');
      rectW = parseInt(btn.dataset.w);
      rectH = parseInt(btn.dataset.h);
      statSize.textContent = rectW + ' \u00d7 ' + rectH;
      if (cameraReady) { centerRect(); }
    });
  });

  /* ── Draw loop ── */
  function drawLoop() {
    if (!cameraReady) return;
    ctx.clearRect(0, 0, overlay.width, overlay.height);
    drawDim();
    drawRect();
    updateStats();
    requestAnimationFrame(drawLoop);
  }

  function drawDim() {
    ctx.fillStyle = 'rgba(0,0,0,0.45)';
    ctx.fillRect(0,          0,          overlay.width,          rectY);
    ctx.fillRect(0,          rectY+rectH, overlay.width,         overlay.height - rectY - rectH);
    ctx.fillRect(0,          rectY,      rectX,                  rectH);
    ctx.fillRect(rectX+rectW,rectY,      overlay.width-rectX-rectW, rectH);
  }

  function drawRect() {
    var x = rectX, y = rectY, w = rectW, h = rectH, c = 16;

    ctx.save();
    ctx.strokeStyle = 'rgba(47,129,247,0.55)';
    ctx.lineWidth   = 1;
    ctx.setLineDash([6, 5]);
    ctx.lineDashOffset = -(Date.now() / 60) % 22;
    ctx.strokeRect(x, y, w, h);
    ctx.restore();

    ctx.strokeStyle = '#58a6ff';
    ctx.lineWidth   = 2.5;
    ctx.lineCap     = 'square';
    ctx.setLineDash([]);

    [[x,   y,   1, 0,  0, 1],
     [x+w, y,  -1, 0,  0, 1],
     [x,   y+h, 1, 0,  0,-1],
     [x+w, y+h,-1, 0,  0,-1]
    ].forEach(function(p) {
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
    var r  = overlay.getBoundingClientRect();
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
    if (!cameraReady) return;
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

  function onUp() {
    dragging = false;
    overlay.style.cursor = 'crosshair';
  }

  /* ── Capture ── */
  captureBtn.addEventListener('click', capture);

  function capture() {
    if (!cameraReady) return;

    // Correct mapping for object-fit: cover
    var dispW = overlay.width,  dispH = overlay.height;
    var vidW  = video.videoWidth, vidH = video.videoHeight;
    var scale = Math.max(dispW / vidW, dispH / vidH);
    var rendW = vidW * scale,   rendH = vidH * scale;
    var offX  = (dispW - rendW) / 2;
    var offY  = (dispH - rendH) / 2;

    var vx = Math.round((rectX - offX) / scale);
    var vy = Math.round((rectY - offY) / scale);
    var vw = Math.round(rectW / scale);
    var vh = Math.round(rectH / scale);

    canvas.width  = rectW;
    canvas.height = rectH;
    cctx.drawImage(video, vx, vy, vw, vh, 0, 0, rectW, rectH);

    // Flash
    flash.classList.add('on');
    setTimeout(function() { flash.classList.remove('on'); }, 150);

    // Export
    var dataURL = canvas.toDataURL('image/jpeg', 0.95);
    var b64     = dataURL.split(',')[1];
    lastBase64  = b64;

    // Show result panel
    previewImg.src           = dataURL;
    previewImg.style.width   = rectW + 'px';
    previewImg.style.height  = rectH + 'px';
    metaDim.textContent      = rectW + ' \u00d7 ' + rectH + ' px';
    metaTime.textContent     = new Date().toLocaleString();
    b64Box.textContent       = b64.substring(0, 200) + '\u2026  [' + Math.round(b64.length/1024) + ' KB]';
    resultPanel.classList.add('visible');
    retakeSec.style.display  = 'block';

    // ── Return result to opener via acceptFaceCapture(base64) ────────────
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
      // Opened standalone (not as a popup) — show a notice
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
      setTimeout(function() { window.close(); }, 1800);
    } else if (state === 'error') {
      sendResultBtn.classList.add('send-error');
      sendResultBtn.querySelector('.send-label').textContent =
        '\u2717  Failed: ' + (msg || 'unknown error');
    }
  }

  // Manual re-send button (in case auto-send failed)
  sendResultBtn.addEventListener('click', function() {
    if (!lastBase64 || sendResultBtn.classList.contains('sent')) return;
    try {
      window.opener.acceptFaceCapture(lastBase64);
      setSendState('sent');
    } catch (err) {
      setSendState('error', err.message);
    }
  });

  /* ── Copy base64 ── */
  copyBtn.addEventListener('click', function() {
    if (!lastBase64) return;
    navigator.clipboard.writeText(lastBase64).then(function() {
      copyBtn.classList.add('copied');
      copyBtn.querySelector('svg').style.display = 'none';
      copyBtn.childNodes[copyBtn.childNodes.length-1].textContent = ' Copied!';
      setTimeout(function() {
        copyBtn.classList.remove('copied');
        copyBtn.querySelector('svg').style.display = '';
        copyBtn.childNodes[copyBtn.childNodes.length-1].textContent = ' Copy base64';
      }, 2000);
    });
  });

  /* ── Retake ── */
  retakeBtn.addEventListener('click', function() {
    resultPanel.classList.remove('visible');
    retakeSec.style.display     = 'none';
    sendResultBtn.style.display = 'none';
    noOpenerNotice.style.display = 'none';
    lastBase64 = '';
    centerRect();
  });

})();
</script>
</body>
</html>
