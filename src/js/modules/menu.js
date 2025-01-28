document.addEventListener('DOMContentLoaded', () => {
  const SHOW_CLASS = 'header__navigation--show';
  const FIXED_CLASS = 'page--fixed';
  const OPEN_CLASS = 'header__menu-button--open';

  const body = document.querySelector('body');
  const header = body.querySelector('.header');
  const menuList = header.querySelector('.navigation');
  const menuButton = header.querySelector('.header__menu-button');

  if (!menuButton) return;

  menuButton.addEventListener('click', () => {
    menuList.classList.toggle(SHOW_CLASS);
    body.classList.toggle(FIXED_CLASS);
    menuButton.classList.toggle(OPEN_CLASS);
  })
})
