// =====================================================
//  ISI LAB - GSAP animations for the main site
//  Replaces AOS: every existing [data-aos] / [data-aos-delay]
//  attribute in the views keeps working, now driven by
//  GSAP + ScrollTrigger (GPU transforms, no CSS-transition fights).
// =====================================================
(function () {
    'use strict';

    var root = document.documentElement;
    function showAll() { root.classList.remove('gsap-pending'); }

    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // ---------- Counters (always run; instant when reduced motion) ----------
    function runCounters() {
        document.querySelectorAll('.stat-number').forEach(function (el) {
            var target = parseInt(el.dataset.target || '0', 10);
            if (!window.gsap || reduce) { el.textContent = target; return; }
            var o = { v: 0 };
            gsap.to(o, {
                v: target,
                duration: 1.8,
                delay: 0.5,
                ease: 'power2.out',
                scrollTrigger: { trigger: el, start: 'top 92%', once: true },
                onUpdate: function () { el.textContent = Math.round(o.v); }
            });
        });
    }

    if (!window.gsap || !window.ScrollTrigger) { showAll(); return; }   // main.js fallbacks handle the rest
    gsap.registerPlugin(ScrollTrigger);

    if (reduce) { runCounters(); showAll(); return; }

    // ---------- Smooth scrolling (must be created before any ScrollTrigger) ----------
    var smoother = null;
    if (window.ScrollSmoother && document.getElementById('smooth-wrapper')) {
        gsap.registerPlugin(ScrollSmoother);
        smoother = ScrollSmoother.create({
            wrapper: '#smooth-wrapper',
            content: '#smooth-content',
            smooth: 1.1,          // seconds to "catch up" to the native scroll position
            effects: true,        // enables data-speed / data-lag attributes if added later
            smoothTouch: false    // keep native momentum scrolling on phones/tablets
        });
        root.classList.add('has-smoother');

        // In-page anchors (#id) glide to their target instead of jumping
        document.addEventListener('click', function (e) {
            var a = e.target.closest && e.target.closest('a[href*="#"]');
            if (!a || a.pathname !== location.pathname || !a.hash) return;
            var target = null;
            try { target = document.querySelector(a.hash); } catch (err) {}
            if (!target) return;
            e.preventDefault();
            smoother.scrollTo(target, true, 'top 100px');
            history.replaceState(null, '', a.hash);
        });
        // Landing on /page#section
        var hashTarget = null;
        try { hashTarget = location.hash ? document.querySelector(location.hash) : null; } catch (err) {}
        if (hashTarget) {
            window.addEventListener('load', function () { smoother.scrollTo(hashTarget, false, 'top 100px'); });
        }
    }

    // ---------- Animation presets (AOS names -> GSAP "from" state) ----------
    var FROM = {
        'fade-up':    { y: 44 },
        'fade-down':  { y: -30 },
        'fade-left':  { x: 60 },
        'fade-right': { x: -60 },
        'zoom-in':    { scale: 0.86 },
        'fade':       {}
    };
    function fromState(el) {
        var base = FROM[el.getAttribute('data-aos')] || FROM['fade-up'];
        var s = { autoAlpha: 0 };
        for (var k in base) s[k] = base[k];
        return s;
    }

    // Disable CSS transitions while GSAP drives transform/opacity,
    // then hand the element back to CSS (hover effects keep working).
    function animateIn(el, delay) {
        if (el._gsapIn) return;
        el._gsapIn = true;
        el.style.transition = 'none';
        gsap.to(el, {
            autoAlpha: 1, x: 0, y: 0, scale: 1,
            duration: 0.9,
            delay: delay || 0,
            ease: 'power3.out',
            overwrite: 'auto',
            onComplete: function () {
                gsap.set(el, { clearProps: 'transform,opacity,visibility,transition' });
            }
        });
    }

    var all = gsap.utils.toArray('[data-aos]');
    var heroScope = document.querySelector('.hero');
    var pageHero = document.querySelector('.page-hero .container');

    // Hide everything in its start state *before* removing the CSS guard
    all.forEach(function (el) { gsap.set(el, fromState(el)); });
    if (pageHero) gsap.set(pageHero.children, { autoAlpha: 0, y: 26 });
    showAll();

    // ---------- Home hero: one orchestrated timeline ----------
    if (heroScope) {
        var heroEls = all.filter(function (el) { return heroScope.contains(el); });
        heroEls.forEach(function (el, i) {
            var d = parseInt(el.getAttribute('data-aos-delay') || '0', 10) / 1000;

            if (el.classList.contains('hero-title')) {
                el._gsapIn = true;
                gsap.set(el, { autoAlpha: 1, y: 0 });
                gsap.from(el.querySelectorAll('.title-line'), {
                    yPercent: 70, autoAlpha: 0, rotationX: -35, transformOrigin: '50% 100%',
                    duration: 1.1, stagger: 0.14, delay: 0.15 + d, ease: 'power4.out'
                });
                return;
            }
            if (el.classList.contains('hero-stats')) {
                el._gsapIn = true;
                gsap.set(el, { autoAlpha: 1, y: 0 });
                var items = el.querySelectorAll('.stat-item');
                items.forEach(function (it) { it.style.transition = 'none'; });
                gsap.from(items, {
                    y: 40, autoAlpha: 0, scale: 0.92,
                    duration: 0.9, stagger: 0.1, delay: 0.25 + d, ease: 'back.out(1.6)',
                    onComplete: function () { gsap.set(items, { clearProps: 'all' }); }
                });
                return;
            }
            animateIn(el, 0.1 + d + i * 0.02);
        });

        // Gentle parallax as the hero scrolls away
        gsap.to('.hero-content', {
            yPercent: -10, autoAlpha: 0.35, ease: 'none',
            scrollTrigger: { trigger: heroScope, start: 'top top', end: 'bottom top', scrub: 0.6 }
        });
        gsap.to('.hero-grid', {
            yPercent: 18, ease: 'none',
            scrollTrigger: { trigger: heroScope, start: 'top top', end: 'bottom top', scrub: true }
        });
    }

    // ---------- Inner-page hero (crumbs / h1 / subtitle) ----------
    if (pageHero) {
        gsap.to(pageHero.children, {
            autoAlpha: 1, y: 0, duration: 0.9, stagger: 0.12, delay: 0.1, ease: 'power3.out',
            onComplete: function () { gsap.set(pageHero.children, { clearProps: 'transform,opacity,visibility' }); }
        });
    }

    // ---------- Everything else: batched scroll reveals ----------
    var rest = all.filter(function (el) { return !el._gsapIn; });
    if (rest.length) {
        ScrollTrigger.batch(rest, {
            start: 'top 88%',
            once: true,
            onEnter: function (batch) {
                batch.forEach(function (el, i) { animateIn(el, i * 0.09); });
            },
            // Elements already scrolled past on load (e.g. reload mid-page)
            onLeave: function (batch) {
                batch.forEach(function (el) { animateIn(el, 0); });
            }
        });
    }

    // ---------- Section underline grows in ----------
    gsap.utils.toArray('.section-line').forEach(function (line) {
        gsap.from(line, {
            scaleX: 0, duration: 1.1, ease: 'power3.out',
            scrollTrigger: { trigger: line, start: 'top 92%', once: true }
        });
    });

    // ---------- Counters ----------
    runCounters();

    // ---------- Smooth cursor glow ----------
    var glow = document.getElementById('cursorGlow');
    if (glow && window.matchMedia('(pointer: fine)').matches) {
        gsap.set(glow, { left: 0, top: 0, xPercent: -50, yPercent: -50 });
        var gx = gsap.quickTo(glow, 'x', { duration: 0.6, ease: 'power3.out' });
        var gy = gsap.quickTo(glow, 'y', { duration: 0.6, ease: 'power3.out' });
        window.addEventListener('mousemove', function (e) { gx(e.clientX); gy(e.clientY); }, { passive: true });
    }

    // Recalculate trigger positions once images/fonts have loaded
    window.addEventListener('load', function () { ScrollTrigger.refresh(); });
})();
