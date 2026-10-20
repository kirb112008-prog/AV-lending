(function () {
  'use strict';

  document.querySelectorAll('.faq__item').forEach(function (item) {
    var button = item.querySelector('.faq__question');
    var panel = item.querySelector('.faq__panel');

    if (!button || !panel) return;

    button.addEventListener('click', function () {
      var opening = !item.classList.contains('faq__item--open');

      document.querySelectorAll('.faq__item').forEach(function (other) {
        var otherButton = other.querySelector('.faq__question');
        var otherPanel = other.querySelector('.faq__panel');
        var isThis = other === item;
        var open = isThis && opening;

        other.classList.toggle('faq__item--open', open);
        if (otherButton) otherButton.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (otherPanel) otherPanel.setAttribute('aria-hidden', open ? 'false' : 'true');
      });
    });
  });

  var calculator = document.querySelector('[data-calculator]');

  if (calculator) {
    var inputs = calculator.querySelectorAll('input');
    var MAX_LOSS_RATE = 0.6;
    var LOSS_RATE_PER_MINUTE = 0.4 / 15;
    var RECOVERABLE_SHARE = 0.75;

    var format = function (value) {
      return Math.round(value).toLocaleString('ru-RU');
    };

    var values = {};

    inputs.forEach(function (input) {
      values[input.name] = Number(input.value);
    });

    var read = function (name) {
      return values[name];
    };

    var update = function () {
      var requests = read('requestsPerMonth');
      var check = read('averageCheck');
      var minutes = read('responseMinutes');
      var conversion = read('conversionPercent');

      var lossRate = Math.min(MAX_LOSS_RATE, minutes * LOSS_RATE_PER_MINUTE);
      var lostRequests = Math.round(requests * lossRate);
      var lostRevenue = Math.round(lostRequests * (conversion / 100) * check);
      var recoverable = Math.round(lostRevenue * RECOVERABLE_SHARE);

      var results = {
        lostRequests: lostRequests,
        lostRevenue: lostRevenue,
        recoverableRevenue: recoverable
      };

      calculator.querySelectorAll('[data-result]').forEach(function (node) {
        var key = node.getAttribute('data-result');
        node.textContent =
          (node.getAttribute('data-prefix') || '') + format(results[key]) + (node.getAttribute('data-suffix') || '');
      });
    };

    inputs.forEach(function (input) {
      input.addEventListener('input', function () {
        if (!isNaN(input.value)) values[input.name] = Math.max(0, Number(input.value));
        input.value = values[input.name];
        update();
      });
    });
  }

  var qrCta = document.querySelector('.qr-hero__cta');

  if (qrCta) {
    qrCta.addEventListener('click', function (event) {
      var name = document.getElementById('qr-name');

      if (!name) return;

      event.preventDefault();
      name.focus();
    });
  }

  var phone = document.querySelector('[data-phone-mask]');

  if (phone) {
    phone.addEventListener('input', function () {
      var digits = phone.value.replace(/\D/g, '');
      if (digits.charAt(0) === '7' || digits.charAt(0) === '8') digits = digits.slice(1);
      digits = digits.slice(0, 10);
      var out = '';
      if (digits) {
        out = '+7 (' + digits.slice(0, 3);
        if (digits.length >= 3) out += ')';
        if (digits.length > 3) out += ' ' + digits.slice(3, 6);
        if (digits.length > 6) out += '-' + digits.slice(6, 8);
        if (digits.length > 8) out += '-' + digits.slice(8, 10);
      }
      phone.value = out;
    });
  }

  var form = document.querySelector('[data-cta-form]');

  if (form) {
    var button = form.querySelector('[data-submit], .cta__button');
    var defaultLabel = form.getAttribute('data-label') || button.textContent;
    var isQr = form.classList.contains('qr-lead');
    var errorNote = form.querySelector('[data-status-error]');
    var TEXT = isQr
      ? { posting: 'Отправляем…', success: 'Заявка успешно отправлена!', error: 'Ошибка отправки' }
      : {
          posting: 'Одну секунду...',
          success: 'Заявка успешно отправлена!',
          error: 'Что-то пошло не так, перезагрузите страницу и попробуйте снова'
        };

    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

    var showError = function (name, message) {
      var holder = form.querySelector('[data-error-for="' + name + '"]');
      var control = form.elements[name];

      if (holder) {
        holder.textContent = message || '';
        holder.hidden = !message;
      }

      if (control) {
        if (message) {
          control.setAttribute('aria-invalid', 'true');
        } else {
          control.removeAttribute('aria-invalid');
        }
      }
    };

    var validate = function (values) {
      var digits = values.phone.replace(/\D/g, '');
      if (digits.charAt(0) === '7' || digits.charAt(0) === '8') digits = digits.slice(1);

      var nameHolder = form.querySelector('[data-error-for="name"]');
      var nameLabel = (nameHolder && nameHolder.getAttribute('data-label')) || 'имя';
      var errors = {};

      if (values.name.length < 2) errors.name = 'Укажите ' + nameLabel;
      if (!emailPattern.test(values.email)) errors.email = 'Введите email в формате name@mail.ru';
      if (digits.length !== 10) errors.phone = 'Введите номер полностью: +7 (900) 000-00-00';
      if (!values.niche) errors.niche = 'Выберите нишу';

      ['name', 'email', 'phone', 'niche'].forEach(function (name) {
        showError(name, errors[name]);
      });

      var first = ['name', 'email', 'phone', 'niche'].filter(function (name) {
        return errors[name];
      })[0];

      if (first && form.elements[first]) form.elements[first].focus();

      return !first;
    };

    ['input', 'change'].forEach(function (type) {
      form.addEventListener(type, function (event) {
        if (event.target && event.target.name) showError(event.target.name, '');
      });
    });

    var setState = function (label, disabled) {
      button.textContent = label;
      button.disabled = disabled;
    };

    var lockFields = function () {
      if (!isQr) return;

      form.querySelectorAll('input, select').forEach(function (control) {
        control.disabled = true;
      });
    };

    var unlockFields = function () {
      form.querySelectorAll('input, select').forEach(function (control) {
        control.disabled = false;
      });
    };

    var fail = function () {
      setState(TEXT.error, true);
      if (errorNote) errorNote.hidden = false;
    };

    // Яндекс SmartCaptcha в невидимом режиме: токен запрашивается при отправке, форма выглядит как раньше.
    // Ключ клиента задаётся в wp-config.php; без него капча выключена.
    var captchaKey = form.getAttribute('data-captcha-key') || '';
    var captchaWidget = null;
    var captchaPending = null;

    var getCaptchaToken = function () {
      return new Promise(function (resolve, reject) {
        var waited = 0;

        var start = function () {
          var api = window.smartCaptcha;

          if (!api) {
            waited += 100;

            if (waited > 10000) {
              reject(new Error('captcha unavailable'));
              return;
            }

            setTimeout(start, 100);
            return;
          }

          var pending = { resolve: resolve, reject: reject, done: false };
          captchaPending = pending;

          var settle = function (method, value) {
            if (captchaPending && !captchaPending.done) {
              captchaPending.done = true;
              captchaPending[method](value);
            }
          };

          if (captchaWidget === null) {
            var holder = document.createElement('div');
            document.body.appendChild(holder);

            captchaWidget = api.render(holder, {
              sitekey: captchaKey,
              invisible: true,
              hl: 'ru',
              callback: function (token) {
                settle('resolve', token);
              }
            });

            // окно проверки закрыли, не пройдя её: даём отправить форму ещё раз
            api.subscribe(captchaWidget, 'challenge-hidden', function () {
              var current = captchaPending;

              setTimeout(function () {
                if (current && current === captchaPending) settle('reject', new Error('cancelled'));
              }, 500);
            });
            api.subscribe(captchaWidget, 'javascript-error', function () {
              settle('reject', new Error('captcha error'));
            });
            api.subscribe(captchaWidget, 'network-error', function () {
              settle('reject', new Error('captcha error'));
            });
          } else {
            api.reset(captchaWidget);
          }

          api.execute(captchaWidget);
        };

        start();
      });
    };

    form.addEventListener('submit', function (event) {
      event.preventDefault();

      var values = {
        name: String(form.elements.name.value || '').trim(),
        email: String(form.elements.email.value || '').trim(),
        phone: String(form.elements.phone.value || '').trim(),
        niche: form.elements.niche.value || ''
      };

      if (!validate(values)) {
        return;
      }

      var endpoint = form.getAttribute('data-endpoint');
      var redirect = form.getAttribute('data-redirect');

      if (!endpoint) {
        setState('Форма не настроена', true);
        return;
      }

      setState(TEXT.posting, true);
      lockFields();

      var send = function (payload) {
        fetch(endpoint, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        })
          .then(function (response) {
            if (response.status === 200) {
              setState(TEXT.success, true);
              if (redirect) window.location.href = redirect;
            } else {
              fail();
            }
          })
          .catch(fail);
      };

      if (!captchaKey) {
        send(values);
        return;
      }

      getCaptchaToken().then(
        function (token) {
          send({ name: values.name, email: values.email, phone: values.phone, niche: values.niche, captcha: token });
        },
        function (error) {
          if (error && error.message === 'cancelled') {
            unlockFields();
            setState(defaultLabel, false);
          } else {
            fail();
          }
        }
      );
    });

    form.setAttribute('data-default-label', defaultLabel);
  }
})();
