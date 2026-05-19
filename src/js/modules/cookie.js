/**
 * Cookie Consent Module
 *
 * Категории:
 * - necessary: Строго необходимые (всегда true)
 * - performance: Производительности
 * - functional: Функциональные
 * - targeting: Целевые и аналитические
 *
 * Использование:
 *   CookieConsent.registerCallback('performance', () => { ... });
 *   CookieConsent.registerCallback('targeting', () => { ... });
 *   CookieConsent.openBanner();
 *   CookieConsent.getConsent(); // { necessary: true, performance: false, ... }
 */

(function() {
  'use strict';

  const COOKIE_NAME = 'cookie_consent';
  const COOKIE_EXPIRY_DAYS = 365;

  const callbackRegistry = new Map();
  let currentConsent = null;

  let banner = null;
  let mainView = null;
  let settingsView = null;

  const getCookie = (name) => {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) {
      try {
        return JSON.parse(decodeURIComponent(parts.pop().split(';').shift()));
      } catch (e) {
        return null;
      }
    }
    return null;
  };

  const setCookie = (name, value, days) => {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = `expires=${date.toUTCString()}`;
    document.cookie = `${name}=${encodeURIComponent(JSON.stringify(value))}; ${expires}; path=/; SameSite=Lax`;
  };

  const runCallbacks = (consent) => {
    for (const [category, fns] of callbackRegistry.entries()) {
      if (consent[category]) {
        fns.forEach(fn => {
          try {
            fn();
          } catch (e) {
            console.error(`CookieConsent: Error in callback for "${category}":`, e);
          }
        });
      }
    }
  };

  const showBanner = () => {
    if (!banner) return;
    banner.hidden = false;
    if (mainView) mainView.hidden = false;
    if (settingsView) settingsView.hidden = true;
  };

  const hideBanner = () => {
    if (banner) banner.hidden = true;
  };

  const showSettings = () => {
    if (mainView) mainView.hidden = true;
    if (settingsView) settingsView.hidden = false;
    restoreCheckboxes();
  };

  const hideSettings = () => {
    if (mainView) mainView.hidden = false;
    if (settingsView) settingsView.hidden = true;
  };

  const restoreCheckboxes = () => {
    if (!settingsView || !currentConsent) return;

    const checkboxes = settingsView.querySelectorAll('[data-cookie-category]');
    checkboxes.forEach((checkbox) => {
      const category = checkbox.dataset.cookieCategory;
      if (currentConsent[category] !== undefined) {
        checkbox.checked = currentConsent[category];
      }
    });
  };

  const saveConsent = (consent) => {
    consent.necessary = true;
    currentConsent = consent;
    setCookie(COOKIE_NAME, consent, COOKIE_EXPIRY_DAYS);
    hideBanner();
    runCallbacks(consent);
  };

  const acceptAll = () => {
    saveConsent({
      necessary: true,
      performance: true,
      functional: true,
      targeting: true
    });
  };

  const acceptNecessary = () => {
    saveConsent({
      necessary: true,
      performance: false,
      functional: false,
      targeting: false
    });
  };

  const saveSelected = () => {
    if (!settingsView) return;

    const checkboxes = settingsView.querySelectorAll('[data-cookie-category]');
    const consent = {
      necessary: true
    };

    checkboxes.forEach((checkbox) => {
      const category = checkbox.dataset.cookieCategory;
      consent[category] = checkbox.checked;
    });

    saveConsent(consent);
  };

  const handleClick = (event) => {
    const action = event.target.closest('[data-cookie-action]');
    if (!action) return;

    const actionName = action.dataset.cookieAction;

    switch (actionName) {
      case 'acceptAll':
        acceptAll();
        break;
      case 'acceptNecessary':
        acceptNecessary();
        break;
      case 'showSettings':
        showSettings();
        break;
      case 'hideSettings':
        hideSettings();
        break;
      case 'saveSelected':
        saveSelected();
        break;
    }
  };

  const init = () => {
    banner = document.getElementById('cookieBanner');
    mainView = document.getElementById('cookieMain');
    settingsView = document.getElementById('cookieSettings');

    if (!banner) return;

    currentConsent = getCookie(COOKIE_NAME);

    banner.addEventListener('click', handleClick);

    if (currentConsent === null) {
      showBanner();
    } else {
      runCallbacks(currentConsent);
    }
  };

  window.CookieConsent = {
    /**
     * Получить текущее согласие
     * @returns {Object|null} { necessary: true, performance: false, ... }
     */
    getConsent: () => currentConsent,

    /**
     * Открыть баннер повторно (для кнопки в футере)
     */
    openBanner: () => showBanner(),

    /**
     * Зарегистрировать колбэк для категории
     * Если согласие на категорию уже есть — колбэк вызывается сразу
     * Если нет — будет вызван при сохранении согласия
     *
     * @param {string} category - 'necessary' | 'performance' | 'functional' | 'targeting'
     * @param {Function} fn - колбэк
     */
    registerCallback: (category, fn) => {
      if (typeof fn !== 'function') {
        console.error('CookieConsent.registerCallback: fn must be a function');
        return;
      }

      if (!callbackRegistry.has(category)) {
        callbackRegistry.set(category, new Set());
      }
      callbackRegistry.get(category).add(fn);

      if (currentConsent && currentConsent[category]) {
        try {
          fn();
        } catch (e) {
          console.error(`CookieConsent: Error in callback for "${category}":`, e);
        }
      }
    },

    /**
     * Сохранить согласие программно
     * @param {Object} consent - { necessary: true, performance: true, ... }
     */
    saveConsent: (consent) => saveConsent(consent)
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
