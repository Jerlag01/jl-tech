(function () {
    'use strict';

    var navbar = document.getElementById('navbar');
    var hamburger = document.getElementById('hamburger');
    var navMenu = document.getElementById('navMenu');
    var mobileQuery = window.matchMedia('(max-width: 1024px)');

    /* --- Navbar shadow on scroll --- */
    function onScroll() {
        if (navbar) {
            navbar.classList.toggle('scrolled', window.scrollY > 40);
        }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    /* --- Mobile menu --- */
    function setMenu(open) {
        hamburger.classList.toggle('active', open);
        navMenu.classList.toggle('active', open);
        hamburger.setAttribute('aria-expanded', open ? 'true' : 'false');
        document.body.style.overflow = open ? 'hidden' : '';
    }

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function () {
            setMenu(!navMenu.classList.contains('active'));
        });

        navMenu.querySelectorAll('.nav-trigger').forEach(function (trigger) {
            trigger.addEventListener('click', function (event) {
                if (!mobileQuery.matches) {
                    return;
                }
                var item = trigger.parentElement;
                if (!item.querySelector('.nav-dropdown')) {
                    return;
                }
                event.preventDefault();
                item.classList.toggle('open');
            });
        });

        navMenu.querySelectorAll('a:not(.nav-trigger)').forEach(function (link) {
            link.addEventListener('click', function () {
                setMenu(false);
            });
        });

        mobileQuery.addEventListener('change', function () {
            setMenu(false);
        });
    }

    /* --- Hero background zoom --- */
    window.addEventListener('load', function () {
        var heroBg = document.getElementById('heroBg');
        if (heroBg) {
            heroBg.classList.add('loaded');
        }
    });

    /* --- Reveal on scroll --- */
    var revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');

    if ('IntersectionObserver' in window) {
        var revealObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        revealElements.forEach(function (element) {
            revealObserver.observe(element);
        });
    } else {
        revealElements.forEach(function (element) {
            element.classList.add('visible');
        });
    }

    /* --- Animated counters --- */
    var counters = document.querySelectorAll('[data-count]');

    function animateCounter(element) {
        var target = parseInt(element.getAttribute('data-count'), 10);
        if (!target) {
            return;
        }
        var prefix = element.getAttribute('data-prefix') || '';
        var suffix = element.getAttribute('data-suffix') || '';
        // Years count up from a nearby value instead of from zero.
        var start = target > 1900 && target < 2200 ? target - 50 : 0;
        var duration = 1800;
        var startTime = null;

        function step(timestamp) {
            if (!startTime) {
                startTime = timestamp;
            }
            var progress = Math.min((timestamp - startTime) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            element.textContent = prefix + Math.floor(start + eased * (target - start)) + suffix;
            if (progress < 1) {
                requestAnimationFrame(step);
            }
        }
        requestAnimationFrame(step);
    }

    if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        var counterObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    counterObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(function (counter) {
            counterObserver.observe(counter);
        });
    }
})();

/* --- Support portal: chosen attachments and double-submit protection --- */
(function () {
    'use strict';

    document.querySelectorAll('[data-file-input]').forEach(function (input) {
        var list = document.querySelector('[data-file-list="' + input.id + '"]');
        if (!list) {
            return;
        }
        input.addEventListener('change', function () {
            list.innerHTML = '';
            Array.prototype.forEach.call(input.files, function (file) {
                var item = document.createElement('li');
                item.textContent = file.name + ' (' + Math.max(1, Math.round(file.size / 1024)) + ' KB)';
                list.appendChild(item);
            });
        });
    });

    document.querySelectorAll('form[data-submit-once]').forEach(function (form) {
        form.addEventListener('submit', function () {
            var button = form.querySelector('button[type="submit"]');
            if (button) {
                setTimeout(function () { button.disabled = true; }, 0);
            }
        });
    });
})();
