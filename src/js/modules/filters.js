document.addEventListener('DOMContentLoaded', () => {
  // Классы для активных состояний
  const OPTION_SELECTED_CLASS = 'sorting__option--active'; // Для опций сортировки [4]
  const ACTIVE_CATEGORY_FILTER_CLASS = 'categories__item-button--active'; // Для активной кнопки категории (один за раз)
  const ACTIVE_TECHNOLOGY_FILTER_CLASS = 'technologies__item-button--active'; // Новый класс для активных кнопок технологий (несколько сразу)

  // Элементы DOM
  const projectsContainer = document.querySelector('.works__list'); // Контейнер для списка проектов [4]
  const allProjectsInitialOrder = Array.from(projectsContainer.children); // [4]

  const filtersWrapper = document.querySelector('.works__filters'); // Общий контейнер для всех фильтров

  // Отдельные контейнеры для категорий и технологий
  const categoryFiltersContainer = filtersWrapper.querySelector('.works__filters-categories');
  const technologyFiltersContainer = filtersWrapper.querySelector('.works__filters-technologies');
  const sortContainer = filtersWrapper.querySelector('.sorting'); // Контейнер сортировки [4]

  // Отдельные коллекции кнопок для категорий и технологий
  const categoryFilterButtons = categoryFiltersContainer.querySelectorAll('.categories__item-button');
  const technologyFilterButtons = technologyFiltersContainer.querySelectorAll('.technologies__item-button');


  // Глобальное состояние фильтров и сортировки
  let currentCategoryFilter = 'all'; // Текущий активный фильтр категории (один за раз) [4]
  let currentTechnologyFilters = []; // Массив активных фильтров по технологиям (несколько сразу)
  let currentSort = 'date-to-low'; // Текущая активная сортировка [4]

  // --- Основная логика: Обновление отображения проектов ---
  // Эта функция остается БЕЗ ИЗМЕНЕНИЙ, потому что она работает с глобальными переменными состояния,
  // а не напрямую с DOM-элементами кнопок.
  const updateProjectsDisplay = () => {
    let projectsToDisplay = [...allProjectsInitialOrder]; // Начинаем со всех проектов в их исходном порядке

    // 1. Применяем фильтр по категориям (один за раз)
    projectsToDisplay = projectsToDisplay.filter(project => {
      if (currentCategoryFilter === 'all') {
        return true; // Если выбран фильтр "Все", показываем все проекты
      }
      return project.dataset.category === currentCategoryFilter; // [4]
    });

    // 2. Применяем фильтр по технологиям (несколько сразу)
    if (currentTechnologyFilters.length > 0) {
      projectsToDisplay = projectsToDisplay.filter(project => {
        const projectTechnologies = project.dataset.technologies ? project.dataset.technologies.split(',') : [];
        return currentTechnologyFilters.some(tech => projectTechnologies.includes(tech));
      });
    }

    // 3. Применяем сортировку к отфильтрованным проектам [4]
    switch (currentSort) {
      case 'date-to-low':
        projectsToDisplay.sort((a, b) => new Date(b.dataset.date) - new Date(a.dataset.date));
        break;
      case 'date-to-high':
        projectsToDisplay.sort((a, b) => new Date(a.dataset.date) - new Date(b.dataset.date));
        break;
      case 'complexity-to-low':
        projectsToDisplay.sort((a, b) => parseInt(b.dataset.complexity) - parseInt(a.dataset.complexity));
        break;
      case 'complexity-to-high':
        projectsToDisplay.sort((a, b) => parseInt(a.dataset.complexity) - parseInt(b.dataset.complexity));
        break;
    }

    // 4. Перерисовываем проекты в DOM [4]
    projectsContainer.innerHTML = '';
    projectsToDisplay.forEach(project => projectsContainer.appendChild(project));
  };

  // --- Инициализация и обработчики событий для фильтров по категориям ---
  categoryFilterButtons.forEach(button => {
    button.addEventListener('click', () => {
      const filterValue = button.dataset.filterValue;

      if (filterValue && filterValue !== currentCategoryFilter) {
        currentCategoryFilter = filterValue; // Обновляем глобальное состояние категории

        // Удаляем активный класс со всех кнопок категорий
        categoryFilterButtons.forEach(btn => btn.classList.remove(ACTIVE_CATEGORY_FILTER_CLASS));
        // Добавляем активный класс к текущей выбранной кнопке категории
        button.classList.add(ACTIVE_CATEGORY_FILTER_CLASS);

        updateProjectsDisplay(); // Запускаем комбинированное обновление
      }
    });
  });

  // Устанавливаем начальную активную кнопку категории (например, "Все")
  const defaultCategoryButton = categoryFiltersContainer.querySelector(`[data-filter-value="${currentCategoryFilter}"]`);
  if (defaultCategoryButton) {
    defaultCategoryButton.classList.add(ACTIVE_CATEGORY_FILTER_CLASS);
  }

  // --- Инициализация и обработчики событий для фильтров по технологиям ---
  technologyFilterButtons.forEach(button => {
    button.addEventListener('click', () => {
      const filterValue = button.dataset.filterValue;

      if (filterValue) {
        const index = currentTechnologyFilters.indexOf(filterValue);
        if (index === -1) {
          // Если технологии нет в списке активных, добавляем ее и делаем кнопку активной
          currentTechnologyFilters.push(filterValue);
          button.classList.add(ACTIVE_TECHNOLOGY_FILTER_CLASS);
        } else {
          // Если технология уже есть, удаляем ее и делаем кнопку неактивной
          currentTechnologyFilters.splice(index, 1);
          button.classList.remove(ACTIVE_TECHNOLOGY_FILTER_CLASS);
        }
        updateProjectsDisplay(); // Запускаем комбинированное обновление
      }
    });
  });


  // --- Инициализация сортировки (остается БЕЗ ИЗМЕНЕНИЙ, кроме селекторов DOM и имени класса) ---
  const initSort = (container) => {
    const sortButton = container.querySelector('.sorting__current'); // [4]
    const sortList = container.querySelector('.sorting__list'); // [4]
    const sortOptions = sortList.querySelectorAll('.sorting__option'); // [4]

    let selectedOptionIndex = Array.from(sortOptions).findIndex((option) =>
      option.classList.contains(OPTION_SELECTED_CLASS),
    );
    if (selectedOptionIndex === -1) selectedOptionIndex = 0;

    const openSort = () => {
      sortList.hidden = false;
      sortButton.setAttribute('aria-expanded', 'true');

      if (sortOptions[selectedOptionIndex]) {
        sortOptions[selectedOptionIndex].focus();
      } else if (sortOptions.length > 0) {
        sortOptions[0].focus();
      }
    };

    const closeSort = () => {
      sortList.hidden = true;
      sortButton.setAttribute('aria-expanded', 'false');
      sortButton.focus();
    };

    const updateSelected = (index) => {
      sortOptions.forEach((option, i) => {
        option.setAttribute('aria-selected', i === index);
        option.classList.toggle(OPTION_SELECTED_CLASS, i === index);
      });

      selectedOptionIndex = index;
      sortButton.childNodes[0].nodeValue = `${sortOptions[index].textContent} `;
    };

    sortButton.addEventListener('click', (evt) => {
      evt.stopPropagation();
      if (sortList.hidden) {
        openSort();
      } else {
        closeSort();
      }
    });

    sortButton.addEventListener('keydown', (evt) => {
      if (['Enter', ' ', 'ArrowDown', 'ArrowUp'].includes(evt.key)) {
        evt.preventDefault();
        openSort();
      }
    });

    sortOptions.forEach((option, index) => {
      option.tabIndex = -1;

      option.addEventListener('click', (evt) => {
        evt.stopPropagation();
        updateSelected(index);
        closeSort();
        currentSort = option.dataset.value;
        updateProjectsDisplay();
      });

      option.addEventListener('keydown', (evt) => {
        let nextIndex;

        switch (evt.key) {
          case 'ArrowDown':
            evt.preventDefault();
            nextIndex = (index + 1) % sortOptions.length;
            sortOptions[nextIndex].focus();
            break;
          case 'ArrowUp':
            evt.preventDefault();
            nextIndex = (index - 1 + sortOptions.length) % sortOptions.length;
            sortOptions[nextIndex].focus();
            break;
          case 'Home':
            evt.preventDefault();
            sortOptions[0].focus();
            break;
          case 'End':
            evt.preventDefault();
            sortOptions[sortOptions.length - 1].focus();
            break;
          case 'Enter':
          case ' ':
            evt.preventDefault();
            updateSelected(index);
            closeSort();
            currentSort = option.dataset.value;
            updateProjectsDisplay();
            break;
          case 'Escape':
            closeSort();
            break;
        }
      });
    });

    document.addEventListener('click', (evt) => {
      if (!container.contains(evt.target) && !sortList.hidden) {
        closeSort();
      }
    });

    sortList.hidden = true;
    updateSelected(selectedOptionIndex);
    currentSort = sortOptions[selectedOptionIndex].dataset.value;

    updateProjectsDisplay();
  };

  if (sortContainer) {
    initSort(sortContainer);
  }
});
