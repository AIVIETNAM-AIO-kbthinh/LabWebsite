// =====================================================
//  ISI LAB v3 - Main client-side script (NO Firebase)
//  All data is rendered server-side. JS only handles
//  interactions: theme, language, navbar, lightbox, etc.
// =====================================================

// Scroll/entrance animations live in site-anim.js (GSAP + ScrollTrigger).

// ----- Theme toggle -----
(() => {
    const btn  = document.getElementById('themeToggle');
    const icon = btn?.querySelector('i');
    const saved = localStorage.getItem('isilab-theme') || 'light';
    document.documentElement.setAttribute('data-theme', saved);
    if (icon) icon.className = saved === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    btn?.addEventListener('click', () => {
        const cur = document.documentElement.getAttribute('data-theme');
        const nx  = cur === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', nx);
        localStorage.setItem('isilab-theme', nx);
        if (icon) icon.className = nx === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    });
})();

// ----- Hamburger -----
(() => {
    const hb = document.getElementById('hamburger');
    const nm = document.getElementById('navMenu');
    hb?.addEventListener('click', () => {
        hb.classList.toggle('active');
        nm?.classList.toggle('active');
    });
    document.querySelectorAll('.nav-link').forEach(l =>
        l.addEventListener('click', () => {
            hb?.classList.remove('active');
            nm?.classList.remove('active');
        })
    );
})();

// ----- Navbar scroll + back-to-top -----
(() => {
    const nav = document.getElementById('navbar');
    const btt = document.getElementById('backToTop');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) nav?.classList.add('scrolled');
        else                     nav?.classList.remove('scrolled');
        if (window.scrollY > 500) btt?.classList.add('visible');
        else                       btt?.classList.remove('visible');
    });
    btt?.addEventListener('click', () => {
        const smoother = window.ScrollSmoother?.get();
        if (smoother) smoother.scrollTo(0, true);
        else window.scrollTo({ top: 0, behavior: 'smooth' });
    });
})();

// ----- Cursor glow -----
(() => {
    const g = document.getElementById('cursorGlow');
    if (!g || window.gsap) return; // GSAP version (smoothed) lives in site-anim.js
    document.addEventListener('mousemove', e => {
        g.style.left = e.clientX + 'px';
        g.style.top  = e.clientY + 'px';
    });
})();

// ----- Animate counters -----
(() => {
    const stats = document.querySelectorAll('.stat-number');
    if (!stats.length || window.gsap) return; // GSAP counters live in site-anim.js
    const animate = el => {
        const target = parseInt(el.dataset.target || '0', 10);
        const dur = 1600, start = performance.now();
        const tick = now => {
            const p = Math.min(1, (now - start) / dur);
            el.textContent = Math.floor(target * (1 - Math.pow(1 - p, 3)));
            if (p < 1) requestAnimationFrame(tick);
            else el.textContent = target;
        };
        requestAnimationFrame(tick);
    };
    const io = new IntersectionObserver(entries => {
        entries.forEach(en => {
            if (en.isIntersecting) { animate(en.target); io.unobserve(en.target); }
        });
    }, { threshold: 0.4 });
    stats.forEach(s => io.observe(s));
})();



// ----- Member tab filter (members page) -----
(() => {
    const tabs = document.querySelectorAll('.member-tab');
    if (!tabs.length) return;
    tabs.forEach(t => t.addEventListener('click', () => {
        tabs.forEach(x => x.classList.remove('active'));
        t.classList.add('active');
        const f = t.dataset.filter;
        document.querySelectorAll('.member-card').forEach(c => {
            c.style.display = (f === 'all' || c.dataset.role === f) ? '' : 'none';
        });
        window.ScrollTrigger?.refresh(); // layout changed -> recompute reveal positions
    }));
})();

// ----- Image lightbox (gallery + member portraits) -----
(() => {
    const modal   = document.getElementById('imageModal');
    const mImg    = document.getElementById('modalImg');
    const mCap    = document.getElementById('modalCaption');
    const closeEl = modal?.querySelector('.modal-close');
    if (!modal) return;
    document.addEventListener('click', e => {
        const t = e.target.closest('.gallery-item img, .member-image img');
        if (!t) return;
        mImg.src = t.src;
        mCap.textContent = t.alt || '';
        modal.classList.add('active');
    });
    closeEl?.addEventListener('click', () => modal.classList.remove('active'));
    modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('active'); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') modal.classList.remove('active'); });
})();

// ----- Loader auto-hide if exists -----
(() => {
    const l = document.getElementById('loader');
    if (l) setTimeout(() => l.classList.add('hidden'), 1200);
})();
