document.addEventListener('DOMContentLoaded', () => {
  const ITEM_ANIMATE_DELAY = 200;
  const ANIMATE_DELAY = 1000;
  const POINTING_DELAY = 1000;

  const CLASS_ANIMATE = 'animate';
  const CLASS_POINTING = 'pointing';

  const CLASS_SECTION = '.section';
  const CLASS_INTRO = 'intro';
  const CLASS_QUOTE = 'quote';
  const CLASS_QUOTE_ITEM = '.quote__text';
  const CLASS_WORKS = 'works';
  const CLASS_WORKS_ITEM = '.works__item';
  const CLASS_PHOTO_LIST = 'photo-list';
  const CLASS_PHOTO_LIST_ITEM = '.photo-list__item';
  const CLASS_SKILLS = 'skills-list';
  const CLASS_SKILLS_ITEM = '.skills-list__item';
  const CLASS_CONTACTS = 'contacts';
  const CLASS_CONTACTS_ITEM = '.contacts__item';
  const CLASS_PROJECT_TECHNOLOGIES = 'project-technologies';
  const CLASS_PROJECT_TECHNOLOGIES_ITEM = '.project-technologies__item';
  const CLASS_PROJECT_INFO = 'project-info';
  const CLASS_PROJECT_INFO_LINKS_ITEM = '.project-info__links-item';
  const CLASS_PROJECT_INFO_FEATURES_ITEM = '.project-info__features-description-item';
  const CLASS_MY_WAY = 'my-way';
  const CLASS_MY_WAY_ITEM = '.my-way__item';
  const CLASS_INFO = 'info';
  const CLASS_INFO_ITEM = '.info__text';
  const CLASS_INFO_FACTS = 'info-facts';
  const CLASS_INFO_FACTS_ITEM = '.info-facts__item-text';
  const CLASS_INFO_INTO = 'info-into';
  const CLASS_INFO_INTO_ITEM = '.info-into__text';

  (function observeSections() {
    const options = {
      root: null,
      rootMargin: '0px',
      threshold: 0
    };

    const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
          const target = entry.target;

          setTimeout(() => {
            target.classList.add(CLASS_ANIMATE);

            if (target.classList.contains(CLASS_QUOTE)) {
              animateItems(target, CLASS_QUOTE_ITEM, CLASS_ANIMATE, CLASS_POINTING);
            }
            if (target.classList.contains(CLASS_WORKS)) {
              animateItems(target, CLASS_WORKS_ITEM, CLASS_ANIMATE, CLASS_POINTING);
            }
            if (target.classList.contains(CLASS_PHOTO_LIST)) {
              animateItems(target, CLASS_PHOTO_LIST_ITEM, CLASS_ANIMATE, CLASS_POINTING);
            }
            if (target.classList.contains(CLASS_SKILLS)) {
              animateItems(target, CLASS_SKILLS_ITEM, CLASS_ANIMATE, CLASS_POINTING);
            }
            if (target.classList.contains(CLASS_CONTACTS)) {
              animateItems(target, CLASS_CONTACTS_ITEM, CLASS_ANIMATE, CLASS_POINTING);
            }
            if (target.classList.contains(CLASS_PROJECT_TECHNOLOGIES)) {
              animateItems(target, CLASS_PROJECT_TECHNOLOGIES_ITEM, CLASS_ANIMATE, CLASS_POINTING);
            }
            if (target.classList.contains(CLASS_PROJECT_INFO)) {
              animateItems(target, CLASS_PROJECT_INFO_LINKS_ITEM, CLASS_ANIMATE, CLASS_POINTING);
              animateItems(target, CLASS_PROJECT_INFO_FEATURES_ITEM, CLASS_ANIMATE, CLASS_POINTING);
            }
            if (target.classList.contains(CLASS_MY_WAY)) {
              animateItems(target, CLASS_MY_WAY_ITEM, CLASS_ANIMATE, CLASS_POINTING);
            }
            if (target.classList.contains(CLASS_INFO)) {
              animateItems(target, CLASS_INFO_ITEM, CLASS_ANIMATE, CLASS_POINTING);
            }
            if (target.classList.contains(CLASS_INFO_FACTS)) {
              animateItems(target, CLASS_INFO_FACTS_ITEM, CLASS_ANIMATE, CLASS_POINTING);
            }
            if (target.classList.contains(CLASS_INFO_INTO)) {
              animateItems(target, CLASS_INFO_INTO_ITEM, CLASS_ANIMATE, CLASS_POINTING);
            }
          }, ITEM_ANIMATE_DELAY * index);

          observer.unobserve(target);
        }
      });
    }, options);

    const sections = document.querySelectorAll(CLASS_SECTION);
    sections.forEach(section => {
      if (section.classList.contains(CLASS_INTRO)) return;

      observer.observe(section);
    });
  }());

  function animateItems(section, itemsSelector, animateClass, pointingClass) {
    setTimeout(() => {
      const items = section.querySelectorAll(itemsSelector);

      items.forEach((item, index) => {
        setTimeout(() => {
          item.classList.add(animateClass);

          setTimeout(() => {
            item.classList.add(pointingClass);
          }, POINTING_DELAY);
        }, ITEM_ANIMATE_DELAY * index);
      });
    }, ANIMATE_DELAY);
  }
});
