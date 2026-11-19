// ============================================
//  ISI LAB - 20/11 Teachers' Day page
//  GSAP + ScrollTrigger animations (hero timeline, scroll reveals,
//  envelope opening, flower bursts, card tilt) with a CSS fallback
//  when GSAP is unavailable or the user prefers reduced motion.
// ============================================
(function () {
    'use strict';

    var root = document.documentElement;
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var useGsap = !!(window.gsap && window.ScrollTrigger) && !reduceMotion;
    var finePointer = window.matchMedia && window.matchMedia('(pointer: fine)').matches;

    var smoother = null;
    if (useGsap) {
        gsap.registerPlugin(ScrollTrigger);
        root.classList.add('td-gsap');

        // Smooth scrolling - created before any ScrollTrigger
        if (window.ScrollSmoother && document.getElementById('smooth-wrapper')) {
            gsap.registerPlugin(ScrollSmoother);
            smoother = ScrollSmoother.create({
                wrapper: '#smooth-wrapper',
                content: '#smooth-content',
                smooth: 1.2,
                effects: true,
                smoothTouch: false
            });
            root.classList.add('has-smoother');
        }
    }

    // In-page anchors (#letter...) glide to their target
    document.addEventListener('click', function (e) {
        var a = e.target.closest && e.target.closest('a[href^="#"]');
        if (!a || a.getAttribute('href').length < 2) return;
        var target = null;
        try { target = document.querySelector(a.getAttribute('href')); } catch (err) {}
        if (!target) return;
        e.preventDefault();
        if (smoother) smoother.scrollTo(target, true, 'top 110px');
        else target.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth' });
    });

    function $(sel, ctx) { return (ctx || document).querySelector(sel); }
    function $$(sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); }
    function rand(min, max) { return min + Math.random() * (max - min); }

    // ---------- Theme toggle (same storage key as main site) ----------
    var themeBtn = document.getElementById('tdThemeToggle');
    function syncThemeIcon(animate) {
        if (!themeBtn) return;
        var dark = root.getAttribute('data-theme') === 'dark';
        themeBtn.innerHTML = dark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        if (animate && useGsap) {
            gsap.fromTo(themeBtn.firstChild, { rotation: -120, scale: 0.4 }, { rotation: 0, scale: 1, duration: 0.6, ease: 'back.out(2.2)' });
        }
    }
    if (themeBtn) {
        syncThemeIcon(false);
        themeBtn.addEventListener('click', function () {
            var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            try { localStorage.setItem('isilab-theme', next); } catch (e) {}
            syncThemeIcon(true);
        });
    }

    // ---------- Top bar background on scroll ----------
    var topbar = document.getElementById('tdTopbar');
    function onScroll() {
        if (topbar) topbar.classList.toggle('is-scrolled', window.scrollY > 20);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    // ---------- Flower burst ----------
    var FLOWERS = ['🌸', '🌹', '💐', '🌷', '🌺', '💮'];
    function flowerEl(cls) {
        var el = document.createElement('span');
        el.className = cls;
        el.textContent = FLOWERS[Math.floor(Math.random() * FLOWERS.length)];
        el.style.fontSize = rand(16, 30) + 'px';
        document.body.appendChild(el);
        return el;
    }

    function burst(x, y, count) {
        if (useGsap) {
            for (var i = 0; i < count; i++) {
                (function () {
                    var el = flowerEl('td-burst-g');
                    var angle = rand(0, Math.PI * 2);
                    var dist = rand(60, 170);
                    gsap.set(el, { x: x, y: y, xPercent: -50, yPercent: -50, scale: 0.3, rotation: 0 });
                    gsap.timeline({ onComplete: function () { el.remove(); } })
                        .to(el, {
                            x: x + Math.cos(angle) * dist,
                            y: y + Math.sin(angle) * dist - 50,
                            scale: 1,
                            rotation: rand(-200, 200),
                            duration: rand(0.55, 0.85),
                            ease: 'power3.out'
                        })
                        .to(el, {
                            y: '+=' + rand(110, 180),
                            rotation: '+=' + rand(-90, 90),
                            autoAlpha: 0,
                            duration: rand(0.9, 1.3),
                            ease: 'power1.in'
                        }, '>-0.15');
                })();
            }
            return;
        }
        // CSS fallback
        if (reduceMotion) count = Math.min(count, 4);
        for (var j = 0; j < count; j++) {
            var el = flowerEl('td-burst');
            var a = Math.random() * Math.PI * 2;
            var d = 60 + Math.random() * 110;
            el.style.left = x + 'px';
            el.style.top = y + 'px';
            el.style.setProperty('--x', Math.cos(a) * d + 'px');
            el.style.setProperty('--y', Math.sin(a) * d - 40 + 'px');
            el.style.setProperty('--r', (Math.random() * 360 - 180) + 'deg');
            el.addEventListener('animationend', el.remove.bind(el));
            setTimeout(el.remove.bind(el), 1600);
        }
    }

    // =====================================================
    //  Reveal animations
    // =====================================================
    var reveals = $$('.td-reveal');

    function markDone(els) {
        els.forEach(function (el) { el.classList.add('is-in'); });
        gsap.set(els, { clearProps: 'transform,opacity,visibility' });
    }

    if (useGsap) {
        var hero = $('.td-hero');
        var heroReveals = reveals.filter(function (el) { return hero && hero.contains(el); });
        var restReveals = reveals.filter(function (el) { return heroReveals.indexOf(el) === -1; });
        var cards = $$('.td-teacher');

        // Start states (set before anything paints as visible)
        gsap.set(heroReveals, { autoAlpha: 0, y: 30 });
        gsap.set(restReveals, { autoAlpha: 0, y: 50 });
        cards.forEach(function (c, i) { gsap.set(c, { rotation: i % 2 ? 3 : -3, scale: 0.94 }); });

        // ----- Hero: one orchestrated timeline -----
        var dateEl = $('.td-date');
        var titleEl = $('.td-title');
        var heroRest = ['.td-lead', '.td-countdown', '.td-hero .td-hero-actions']
            .map(function (s) { return $(s); }).filter(Boolean);

        var tl = gsap.timeline({ defaults: { ease: 'power3.out', duration: 0.9 }, delay: 0.1 });
        if (topbar) tl.from(topbar, { yPercent: -100, autoAlpha: 0, duration: 0.8 });
        tl.to('.td-eyebrow', { autoAlpha: 1, y: 0 }, '-=0.45');
        if (dateEl) {
            tl.set(dateEl, { autoAlpha: 1, y: 0 }, '-=0.5')
              .from(dateEl.children, {
                  yPercent: 90,
                  rotation: function (i) { return [-12, 0, 12][i] || 0; },
                  scale: 0.7,
                  autoAlpha: 0,
                  duration: 1.25,
                  stagger: 0.14,
                  ease: 'back.out(1.6)'
              }, '<');
        }
        if (titleEl) {
            tl.to(titleEl, { autoAlpha: 1, y: 0 }, '-=0.8')
              .from('.td-title-big', { letterSpacing: '0.14em', duration: 1.5, ease: 'power2.out' }, '<');
        }
        tl.to(heroRest, { autoAlpha: 1, y: 0, stagger: 0.12 }, '-=1.1')
          .from('.td-scroll', { autoAlpha: 0, duration: 0.6 }, '-=0.4')
          .add(function () { markDone(heroReveals); });

        // Hero glow: slow breathing + parallax
        gsap.to('.td-hero-glow', { xPercent: 6, yPercent: 5, scale: 1.08, duration: 7, ease: 'sine.inOut', yoyo: true, repeat: -1 });
        gsap.to('.td-hero-glow', {
            y: 160, ease: 'none',
            scrollTrigger: { trigger: '.td-hero', start: 'top top', end: 'bottom top', scrub: true }
        });

        // ----- Scroll reveals (batched, staggered) -----
        function revealBatch(batch) {
            var todo = batch.filter(function (el) { return !el._tdIn; });
            if (!todo.length) return;
            todo.forEach(function (el) { el._tdIn = true; });
            gsap.to(todo, {
                autoAlpha: 1, y: 0, rotation: 0, scale: 1,
                duration: 1,
                stagger: 0.12,
                ease: 'power3.out',
                overwrite: 'auto',
                onComplete: function () { markDone(todo); }
            });
            todo.forEach(function (el, i) {
                var av = $('.td-avatar', el);
                if (av) gsap.from(av, { scale: 0.4, rotation: -120, duration: 1.1, delay: 0.1 + i * 0.12, ease: 'back.out(1.8)' });
                var script = $('.td-cta-script', el);
                if (script) gsap.from(script, { scale: 0.75, duration: 1.4, delay: 0.1, ease: 'elastic.out(1, 0.5)' });
            });
        }
        if (restReveals.length) {
            ScrollTrigger.batch(restReveals, { start: 'top 86%', once: true, onEnter: revealBatch, onLeave: revealBatch });
        }

        // Section underline grows in
        $$('.td-h2').forEach(function (h) {
            gsap.fromTo(h, { '--td-line-scale': 0 }, {
                '--td-line-scale': 1, duration: 1, ease: 'power3.out',
                scrollTrigger: { trigger: h, start: 'top 85%', once: true }
            });
        });

        // ----- Teacher cards: 3D tilt on hover -----
        if (finePointer) {
            cards.forEach(function (card) {
                card.addEventListener('pointermove', function (e) {
                    if (!card.classList.contains('is-in')) return;
                    var r = card.getBoundingClientRect();
                    var px = (e.clientX - r.left) / r.width - 0.5;
                    var py = (e.clientY - r.top) / r.height - 0.5;
                    gsap.to(card, {
                        rotationY: px * 12, rotationX: -py * 12, y: -8,
                        transformPerspective: 900, duration: 0.5, ease: 'power3.out', overwrite: 'auto'
                    });
                });
                card.addEventListener('pointerleave', function () {
                    gsap.to(card, { rotationY: 0, rotationX: 0, y: 0, duration: 0.8, ease: 'elastic.out(1, 0.5)', overwrite: 'auto' });
                });
            });
        }

        window.addEventListener('load', function () { ScrollTrigger.refresh(); });
    } else if ('IntersectionObserver' in window && !reduceMotion) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) {
                    en.target.classList.add('is-in');
                    io.unobserve(en.target);
                }
            });
        }, { threshold: 0.15 });
        reveals.forEach(function (el) { io.observe(el); });
    } else {
        reveals.forEach(function (el) { el.classList.add('is-in'); });
    }

    // =====================================================
    //  "Send a flower" buttons
    // =====================================================
    // =====================================================
    //  Avatar photos: on a broken image try data-fallback,
    //  then show the initials instead of a broken icon
    // =====================================================
    function avatarFailed(img) {
        var fb = img.getAttribute('data-fallback');
        if (fb) {
            img.removeAttribute('data-fallback');
            img.src = fb;
            return;
        }
        var span = document.createElement('span');
        span.textContent = img.getAttribute('data-initials') || '';
        img.replaceWith(span);
    }
    $$('.td-avatar img').forEach(function (img) {
        img.addEventListener('error', function () { avatarFailed(img); });
        if (img.complete && img.naturalWidth === 0 && img.getAttribute('src')) avatarFailed(img);
    });

    function sendFlower(btn, particles) {
        var r = btn.getBoundingClientRect();
        var visible = r.bottom > 0 && r.top < window.innerHeight;
        if (particles && visible) burst(r.left + r.width / 2, r.top + r.height / 2, particles);
        btn.classList.add('is-sent');
        var icon = $('i', btn);
        if (icon) icon.className = 'fas fa-heart';
        if (useGsap) {
            gsap.fromTo(btn, { scale: 0.8 }, { scale: 1, duration: 0.9, ease: 'elastic.out(1, 0.35)' });
            if (icon) gsap.fromTo(icon, { scale: 0 }, { scale: 1, duration: 0.6, ease: 'back.out(3)' });
        }
    }

    $$('[data-flower]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            sendFlower(btn, 14);
            addPetals(12);
        });
    });

    // "Send flowers to everyone" in the faculty section
    var flowerAll = document.getElementById('tdFlowerAll');
    if (flowerAll) {
        flowerAll.addEventListener('click', function () {
            var section = flowerAll.closest('section') || document;
            var pending = $$('[data-flower]:not(.is-sent)', section);
            flowerAll.disabled = true;
            var fIcon = $('i', flowerAll);
            if (fIcon) fIcon.className = 'fas fa-heart';
            pending.forEach(function (btn, i) {
                setTimeout(function () { sendFlower(btn, 6); }, reduceMotion ? 0 : i * 90);
            });
            addPetals(30);
        });
    }

    // =====================================================
    //  Envelope -> letter
    // =====================================================
    var envelope = document.getElementById('tdEnvelope');
    var letter = document.getElementById('tdLetter');
    if (envelope && letter) {
        var wrap = envelope.parentElement;
        var flap = $('.td-env-flap', envelope);
        var seal = $('.td-env-seal', envelope);
        var hint = $('.td-env-hint', envelope);
        var floatTween = null;

        if (useGsap) {
            floatTween = gsap.to(envelope, { y: -10, rotation: -1.2, duration: 2.2, ease: 'sine.inOut', yoyo: true, repeat: -1 });
            if (finePointer && seal) {
                envelope.addEventListener('pointerenter', function () {
                    if (!wrap.classList.contains('is-busy')) gsap.to(seal, { scale: 1.15, duration: 0.4, ease: 'back.out(3)' });
                });
                envelope.addEventListener('pointerleave', function () {
                    if (!wrap.classList.contains('is-busy')) gsap.to(seal, { scale: 1, duration: 0.4 });
                });
            }
        }

        envelope.addEventListener('click', function () {
            if (wrap.classList.contains('is-opening') || wrap.classList.contains('is-busy') || wrap.classList.contains('is-open')) return;
            envelope.setAttribute('aria-expanded', 'true');

            if (!useGsap) {
                wrap.classList.add('is-opening');
                var r0 = envelope.getBoundingClientRect();
                burst(r0.left + r0.width / 2, r0.top + r0.height * 0.55, 18);
                setTimeout(function () {
                    wrap.classList.add('is-open');
                    letter.setAttribute('aria-hidden', 'false');
                }, reduceMotion ? 0 : 1000);
                return;
            }

            // GSAP opening sequence. Uses its own "is-busy" flag: the CSS
            // .is-opening keyframes would otherwise override GSAP's inline styles.
            wrap.classList.add('is-busy');
            if (floatTween) floatTween.kill();
            if (hint) hint.style.animation = 'none';

            var paras = $$('p:not(.td-letter-sign)', letter);
            var sign = $('.td-signature', letter);

            gsap.timeline()
                .to(envelope, { y: 0, rotation: 0, duration: 0.25, ease: 'power2.out' })
                .to(hint, { autoAlpha: 0, duration: 0.2 }, 0)
                .to(seal, { scale: 0, rotation: 200, autoAlpha: 0, duration: 0.45, ease: 'back.in(2)' }, 0)
                .to(flap, { rotationX: 180, transformPerspective: 800, duration: 0.75, ease: 'power2.inOut' }, 0.25)
                .set(flap, { zIndex: 1 }, 0.62)
                .call(function () {
                    var r = envelope.getBoundingClientRect();
                    burst(r.left + r.width / 2, r.top + r.height * 0.45, 20);
                }, null, 0.7)
                .to(envelope, { y: 70, scale: 0.88, autoAlpha: 0, duration: 0.5, ease: 'power2.in' }, 1.05)
                .call(function () {
                    wrap.classList.add('is-open');
                    letter.setAttribute('aria-hidden', 'false');
                    gsap.set(letter, { autoAlpha: 0, y: 80, rotationX: -14, transformPerspective: 1000, transformOrigin: '50% 100%' });
                    gsap.set(paras, { autoAlpha: 0, y: 18 });
                    if (sign) gsap.set(sign, { clipPath: 'inset(0% 100% 0% 0%)' });

                    gsap.timeline()
                        .to(letter, { autoAlpha: 1, y: 0, rotationX: 0, duration: 1.1, ease: 'power3.out' })
                        .to(paras, { autoAlpha: 1, y: 0, duration: 0.7, stagger: 0.16, ease: 'power2.out' }, '-=0.55')
                        .to(sign, { clipPath: 'inset(0% 0% 0% 0%)', duration: 1.5, ease: 'power2.inOut' }, '-=0.2')
                        .add(function () {
                            gsap.set(letter, { clearProps: 'transform' });
                            ScrollTrigger.refresh();
                        });
                });
        });
    }

    // =====================================================
    //  Falling petals (canvas)
    // =====================================================
    var canvas = document.getElementById('tdPetals');
    var ctx = canvas && canvas.getContext ? canvas.getContext('2d') : null;
    var petals = [];
    var W = 0, H = 0, dpr = 1;
    var COLORS_LIGHT = ['#e8a3a8', '#f2c4c6', '#c93a4f', '#e6c784', '#f7d9dc'];
    var COLORS_DARK  = ['#f07a8c', '#e2546a', '#e1b65c', '#f5b3bd', '#c93a4f'];

    function resize() {
        if (!canvas) return;
        dpr = Math.min(window.devicePixelRatio || 1, 2);
        W = window.innerWidth;
        H = window.innerHeight;
        canvas.width = W * dpr;
        canvas.height = H * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    }

    function makePetal(fromTop) {
        return {
            x: Math.random() * W,
            y: fromTop ? -20 - Math.random() * H * 0.5 : Math.random() * H,
            s: 6 + Math.random() * 8,
            vy: 0.4 + Math.random() * 0.9,
            vx: -0.3 + Math.random() * 0.6,
            rot: Math.random() * Math.PI * 2,
            vr: -0.02 + Math.random() * 0.04,
            sway: Math.random() * Math.PI * 2,
            ci: Math.floor(Math.random() * 5),
            a: 0.35 + Math.random() * 0.4,
            temp: false
        };
    }

    function addPetals(n) {
        if (!ctx || reduceMotion) return;
        for (var i = 0; i < n; i++) {
            var p = makePetal(true);
            p.temp = true;
            p.vy *= 1.6;
            petals.push(p);
        }
    }

    function drawPetal(p, colors) {
        ctx.save();
        ctx.translate(p.x, p.y);
        ctx.rotate(p.rot);
        ctx.scale(1, 0.6 + 0.4 * Math.abs(Math.sin(p.sway)));
        ctx.globalAlpha = p.a;
        ctx.fillStyle = colors[p.ci];
        ctx.beginPath();
        ctx.moveTo(0, -p.s);
        ctx.bezierCurveTo(p.s * 0.9, -p.s * 0.6, p.s * 0.7, p.s * 0.7, 0, p.s);
        ctx.bezierCurveTo(-p.s * 0.7, p.s * 0.7, -p.s * 0.9, -p.s * 0.6, 0, -p.s);
        ctx.fill();
        ctx.restore();
    }

    // Frame step; dt keeps speed constant on 60/120/144Hz screens
    function step(dt) {
        ctx.clearRect(0, 0, W, H);
        var colors = root.getAttribute('data-theme') === 'dark' ? COLORS_DARK : COLORS_LIGHT;
        for (var i = petals.length - 1; i >= 0; i--) {
            var p = petals[i];
            p.sway += 0.02 * dt;
            p.x += (p.vx + Math.sin(p.sway) * 0.6) * dt;
            p.y += p.vy * dt;
            p.rot += p.vr * dt;
            if (p.y > H + 20 || p.x < -30 || p.x > W + 30) {
                if (p.temp) { petals.splice(i, 1); continue; }
                petals[i] = makePetal(true);
                petals[i].y = -20;
                continue;
            }
            drawPetal(p, colors);
        }
    }

    if (ctx && !reduceMotion) {
        resize();
        var base = Math.round(Math.min(45, Math.max(18, W / 35)));
        for (var k = 0; k < base; k++) petals.push(makePetal(false));
        window.addEventListener('resize', resize);

        if (useGsap) {
            // Share GSAP's ticker: one RAF loop for everything, auto-pauses in background tabs
            gsap.ticker.add(function (time, deltaTime) {
                step(Math.min(deltaTime / 16.667, 3));
            });
        } else {
            var last = performance.now();
            var loop = function (now) {
                step(Math.min((now - last) / 16.667, 3));
                last = now;
                requestAnimationFrame(loop);
            };
            requestAnimationFrame(loop);
        }
    }

    // ---------- "Shower of petals" button ----------
    var shower = document.getElementById('tdShower');
    if (shower) {
        shower.addEventListener('click', function () {
            addPetals(60);
            var r = shower.getBoundingClientRect();
            burst(r.left + r.width / 2, r.top + r.height / 2, 16);
            if (useGsap) {
                gsap.fromTo('.td-cta-script', { rotation: -4, scale: 0.92 }, { rotation: 0, scale: 1, duration: 1.2, ease: 'elastic.out(1, 0.35)' });
            }
        });
    }
})();
