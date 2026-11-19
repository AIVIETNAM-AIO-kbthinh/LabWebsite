// Logo replacement patch for ISI Lab
// Replaces the microchip icon in the navbar and the About-section center icon.
(function () {
  const LOGO_SRC = 'assets/isilab-new-logo.png';

  function makeLogoImage(extraClass = '') {
    const img = document.createElement('img');
    img.src = LOGO_SRC;
    img.alt = 'ISI Lab Logo';
    img.className = `isilab-new-logo-img ${extraClass}`.trim();
    return img;
  }

  function replaceElementContent(el, extraClass = '') {
    if (!el) return false;
    el.innerHTML = '';
    el.appendChild(makeLogoImage(extraClass));
    return true;
  }

  function firstExisting(selectors) {
    for (const selector of selectors) {
      const el = document.querySelector(selector);
      if (el) return el;
    }
    return null;
  }

  document.addEventListener('DOMContentLoaded', function () {
    // 1) Header logo: the small square icon at the top-left.
    const headerLogo = firstExisting([
      'header .logo-icon',
      '.navbar .logo-icon',
      '.nav-logo .logo-icon',
      '.brand .logo-icon',
      '.brand-icon',
      'header .fa-microchip',
      '.navbar .fa-microchip'
    ]);

    if (headerLogo) {
      const target = headerLogo.classList && headerLogo.classList.contains('fa-microchip')
        ? headerLogo.parentElement
        : headerLogo;
      replaceElementContent(target, 'isilab-header-logo');
    }

    // 2) About center logo: the big square icon in the circular/orbit diagram.
    const aboutLogo = firstExisting([
      '#about .tech-core',
      '.about .tech-core',
      '#about .core-icon',
      '.about .core-icon',
      '#about .orbit-center',
      '.about .orbit-center',
      '#about .center-icon',
      '.about .center-icon',
      '#about .fa-microchip',
      '.about .fa-microchip'
    ]);

    if (aboutLogo) {
      const target = aboutLogo.classList && aboutLogo.classList.contains('fa-microchip')
        ? aboutLogo.parentElement
        : aboutLogo;
      replaceElementContent(target, 'isilab-about-logo');
    }
  });
})();
