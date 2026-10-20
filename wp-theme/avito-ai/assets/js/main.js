(function () {
  'use strict';

  var burger = document.querySelector('.header__burger');
  var nav = document.querySelector('.header__nav');

  function closeMenu() {
    if (!burger || !nav) return;
    nav.classList.remove('header__nav--open');
    burger.classList.remove('header__burger--open');
    burger.setAttribute('aria-expanded', 'false');
    burger.setAttribute('aria-label', 'Открыть меню');
  }

  if (burger && nav) {
    burger.addEventListener('click', function () {
      var open = nav.classList.toggle('header__nav--open');
      burger.classList.toggle('header__burger--open', open);
      burger.setAttribute('aria-expanded', open ? 'true' : 'false');
      burger.setAttribute('aria-label', open ? 'Закрыть меню' : 'Открыть меню');
    });

    document.querySelectorAll('.header__link, .header__logo, .header .button').forEach(function (el) {
      el.addEventListener('click', closeMenu);
    });
  }

  function mountEmbed(holder) {
    if (holder.getAttribute('data-mounted')) return;
    holder.setAttribute('data-mounted', '1');

    var frame = document.createElement('iframe');
    frame.src = holder.getAttribute('data-video-src');
    frame.allow = 'autoplay; fullscreen; picture-in-picture; encrypted-media';
    frame.allowFullscreen = true;
    frame.title = 'Видео';
    holder.parentNode.appendChild(frame);
    holder.remove();
  }

  var embeds = document.querySelectorAll('[data-video-src]');
  var videos = document.querySelectorAll('video[data-autoplay]');

  if ('IntersectionObserver' in window) {
    var mediaObserver = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          var node = entry.target;

          if (node.tagName === 'VIDEO') {
            if (entry.isIntersecting) {
              var played = node.play();
              if (played && played.catch) played.catch(function () {});
            } else {
              node.pause();
            }
          } else if (entry.isIntersecting) {
            mountEmbed(node);
            mediaObserver.unobserve(node);
          }
        });
      },
      { threshold: 0.35 }
    );

    embeds.forEach(function (node) {
      mediaObserver.observe(node);
    });
    videos.forEach(function (node) {
      mediaObserver.observe(node);
    });
  } else {
    embeds.forEach(mountEmbed);
    videos.forEach(function (node) {
      var played = node.play();
      if (played && played.catch) played.catch(function () {});
    });
  }

  var links = Array.prototype.slice.call(document.querySelectorAll('.header__link'));
  var sections = links
    .map(function (link) {
      var href = link.getAttribute('href') || '';
      return href.charAt(0) === '#' && href.length > 1 ? document.getElementById(href.slice(1)) : null;
    })
    .filter(Boolean);
  var ticking = false;

  function updateActive() {
    ticking = false;
    var line = window.innerHeight * 0.4;
    var current = sections.filter(function (s) {
      var rect = s.getBoundingClientRect();
      return rect.top <= line && rect.bottom > line;
    })[0];

    links.forEach(function (link) {
      var active = current && link.getAttribute('href') === '#' + current.id;
      link.classList.toggle('header__link--active', !!active);
      if (active) {
        link.setAttribute('aria-current', 'true');
      } else {
        link.removeAttribute('aria-current');
      }
    });
  }

  function onScroll() {
    if (!ticking) {
      ticking = true;
      window.requestAnimationFrame(updateActive);
    }
  }

  if (sections.length) {
    updateActive();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
  }

  document.querySelectorAll('.details').forEach(function (block) {
    var tabs = Array.prototype.slice.call(block.querySelectorAll('.details__tab'));
    var panels = Array.prototype.slice.call(block.querySelectorAll('.details__panel'));

    tabs.forEach(function (tab, index) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (other, i) {
          var active = i === index;
          other.classList.toggle('details__tab--active', active);
          other.setAttribute('aria-selected', active ? 'true' : 'false');
          if (panels[i]) panels[i].hidden = !active;
        });
      });
    });
  });

  if ('IntersectionObserver' in window) {
    var revealObserver = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('reveal--visible');
            revealObserver.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.1, rootMargin: '0px 0px -60px 0px' }
    );

    document.querySelectorAll('.reveal').forEach(function (el) {
      revealObserver.observe(el);
    });
  } else {
    document.querySelectorAll('.reveal').forEach(function (el) {
      el.classList.add('reveal--visible');
    });
  }
})();
