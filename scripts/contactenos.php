<?php
// ============================================================
//  Semillas y Bosques Mejorados S.A.
//  contactenos.php — Form mail handler
//  Accepts POST from any page form; sends email and renders
//  a styled confirmation (or error) page.
// ============================================================

// ---- Configuration -----------------------------------------
$mailbox  = 'info@semillasybosques.com';
$subject  = 'Semillas y Bosques — Nuevo Mensaje';

// ---- Helpers -----------------------------------------------
function clean(string $value): string {
    return htmlspecialchars(trim(strip_tags($value)), ENT_QUOTES, 'UTF-8');
}

function isValidEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// ---- Guard: only handle POST -------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contactenos.html');
    exit;
}

// ---- Collect and sanitize inputs ---------------------------
$nombre      = clean($_POST['nombre']      ?? '');
$email       = clean($_POST['email']       ?? '');
$telefono    = clean($_POST['telefono']    ?? '');
$pais        = clean($_POST['pais']        ?? '');
$comentarios = clean($_POST['comentarios'] ?? '');

// ---- Validate required fields ------------------------------
$errors = [];
if ($nombre === '')      $errors[] = 'El campo Nombre es requerido.';
if ($email === '')       $errors[] = 'El campo Email es requerido.';
elseif (!isValidEmail($email)) $errors[] = 'La dirección de email no es válida.';
if ($telefono === '')    $errors[] = 'El campo Teléfono es requerido.';
if ($comentarios === '') $errors[] = 'El campo de comentarios / mensaje es requerido.';

$success = false;

if (empty($errors)) {
    // ---- Build message -------------------------------------
    $body  = "Ha recibido un nuevo mensaje desde Semillas y Bosques Mejorados.\n\n";
    $body .= "Nombre:      {$nombre}\n";
    $body .= "Email:       {$email}\n";
    $body .= "Teléfono:    {$telefono}\n";
    $body .= "País:        {$pais}\n";
    $body .= "Mensaje:\n{$comentarios}\n";

    $headers  = "From: Formulario Web <{$mailbox}>\r\n";
    $headers .= "Reply-To: {$nombre} <{$email}>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $success = mail($mailbox, $subject, $body, $headers);
    if (!$success) {
        $errors[] = 'No fue posible enviar el mensaje. Por favor inténtelo de nuevo o escríbanos directamente a <a href="mailto:info@semillasybosques.com">info@semillasybosques.com</a>.';
    }
}

// ---- Determine back URL -----------------------------------
// Use HTTP_REFERER when available so the back link returns the user
// to the exact page they came from.
$referer   = $_SERVER['HTTP_REFERER'] ?? '../contactenos.html';
$backLabel = 'Volver';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $success ? 'Mensaje enviado' : 'Error al enviar' ?> — Semillas y Bosques Mejorados S.A.</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../css/styles.css" />
  <style>
    .response-wrap {
      min-height: calc(100dvh - 68px);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: var(--sp-10) var(--sp-5);
    }
    .response-card {
      background: var(--clr-surface);
      border-radius: var(--radius-2xl);
      border: 1px solid var(--clr-border);
      box-shadow: var(--shadow-md);
      padding: var(--sp-12) var(--sp-10);
      max-width: 520px;
      width: 100%;
      text-align: center;
    }
    .response-icon {
      width: 64px; height: 64px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto var(--sp-6);
    }
    .response-icon--success {
      background: var(--clr-forest-50);
      border: 2px solid var(--clr-forest-200);
    }
    .response-icon--error {
      background: #fef2f2;
      border: 2px solid #fecaca;
    }
    .response-icon svg {
      width: 32px; height: 32px;
    }
    .response-card h1 {
      font-size: var(--text-2xl);
      font-weight: 700;
      color: var(--clr-forest-800);
      margin-bottom: var(--sp-4);
      letter-spacing: -0.01em;
    }
    .response-card p {
      font-size: var(--text-base);
      color: var(--clr-stone-600);
      line-height: 1.7;
      margin-bottom: var(--sp-4);
    }
    .response-card p:last-of-type { margin-bottom: 0; }
    .response-actions {
      display: flex;
      gap: var(--sp-3);
      justify-content: center;
      flex-wrap: wrap;
      margin-top: var(--sp-8);
    }
    .error-list {
      text-align: left;
      background: #fef2f2;
      border: 1px solid #fecaca;
      border-radius: var(--radius-md);
      padding: var(--sp-4) var(--sp-5);
      margin: var(--sp-4) 0;
      font-size: var(--text-sm);
      color: #b91c1c;
      list-style: none;
    }
    .error-list li {
      padding: var(--sp-1) 0;
      padding-left: var(--sp-5);
      position: relative;
    }
    .error-list li::before {
      content: '✕';
      position: absolute;
      left: 0;
      font-size: 0.7em;
      top: 3px;
    }
  </style>
</head>
<body>

  <a href="#main" class="skip-link">Saltar al contenido principal</a>

  <header class="site-header" role="banner">
    <div class="nav-wrapper">
      <a href="../index.html" class="site-logo" aria-label="Semillas y Bosques Mejorados — Inicio">
        <img src="../images/semillasybosques-logo.jpg" alt="Semillas y Bosques Mejorados" width="120" height="44" />
        <div class="site-logo-text">
          <span class="site-logo-name">S&amp;BM</span>
          <span class="site-logo-tagline">Costa Rica</span>
        </div>
      </a>
      <nav class="primary-nav" aria-label="Navegación principal">
        <ul>
          <li><a href="../nosotros.html">Nosotros</a></li>
          <li><a href="../semillas-forestales.html">Semillas Forestales</a></li>
          <li><a href="../servicios.html">Servicios</a></li>
          <li><a href="../centro-documentacion.html">Documentación</a></li>
          <li><a href="../galeria.html">Galería</a></li>
          <li><a href="../distribuidores.html">Distribuidores</a></li>
          <li><a href="../contactenos.html">Contáctenos</a></li>
        </ul>
      </nav>
      <button class="nav-mobile-toggle" id="nav-toggle" aria-expanded="false" aria-controls="mobile-nav" aria-label="Abrir menú">
        <span class="hamburger" aria-hidden="true"><span></span><span></span><span></span></span>
      </button>
    </div>
    <nav class="mobile-nav" id="mobile-nav" aria-label="Menú móvil">
      <div class="mobile-nav-inner">
        <ul>
          <li><a href="../nosotros.html">Nosotros</a></li>
          <li><a href="../semillas-forestales.html">Semillas Forestales</a></li>
          <li><a href="../servicios.html">Servicios</a></li>
          <li><a href="../centro-documentacion.html">Centro de Documentación</a></li>
          <li><a href="../galeria.html">Galería</a></li>
          <li><a href="../distribuidores.html">Distribuidores</a></li>
          <li><a href="../contactenos.html">Contáctenos</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <main id="main">
    <div class="response-wrap">
      <div class="response-card">

        <?php if ($success): ?>

          <div class="response-icon response-icon--success" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--clr-forest-600)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
          </div>

          <h1>¡Mensaje enviado!</h1>
          <p>
            Gracias, <strong><?= $nombre ?></strong>. Hemos recibido su mensaje y nos pondremos
            en contacto con usted a la brevedad posible.
          </p>
          <p>
            Si necesita atención inmediata puede llamarnos al
            <a href="tel:+50625240925">(+506) 2524-0925</a> o escribirnos a
            <a href="mailto:info@semillasybosques.com">info@semillasybosques.com</a>.
          </p>

          <div class="response-actions">
            <a href="<?= htmlspecialchars($referer) ?>" class="btn btn-primary" style="background: var(--clr-forest-800); color:#fff; border-color: var(--clr-forest-800);">
              ← <?= $backLabel ?>
            </a>
            <a href="../index.html" class="btn btn-outline" style="border-color: var(--clr-stone-300); color: var(--clr-stone-700);">
              Ir al inicio
            </a>
          </div>

        <?php else: ?>

          <div class="response-icon response-icon--error" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="#b91c1c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
          </div>

          <h1>Algo salió mal</h1>
          <p>Por favor corrija los siguientes errores e inténtelo de nuevo:</p>

          <ul class="error-list" role="list">
            <?php foreach ($errors as $error): ?>
              <li><?= $error ?></li>
            <?php endforeach; ?>
          </ul>

          <div class="response-actions">
            <a href="<?= htmlspecialchars($referer) ?>" class="btn btn-primary" style="background: var(--clr-forest-800); color:#fff; border-color: var(--clr-forest-800);">
              ← Volver al formulario
            </a>
          </div>

        <?php endif; ?>

      </div>
    </div>
  </main>

  <footer class="site-footer" role="contentinfo">
    <div class="footer-inner">
      <div class="footer-bottom" style="border-top: none; padding-top: 0;">
        <p>© 2025 Semillas y Bosques Mejorados S.A. · San José, Costa Rica</p>
      </div>
    </div>
  </footer>

  <script src="../scripts/main.js" defer></script>
</body>
</html>
