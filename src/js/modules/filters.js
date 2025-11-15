document.addEventListener('DOMContentLoaded', () => {
  const OPTION_SELECTED_CLASS = 'sorting__option--active';
  const ACTIVE_CATEGORY_FILTER_CLASS = 'categories__item-button--active';
  const ACTIVE_TECHNOLOGY_FILTER_CLASS = 'technologies__item-button--active';

  const projectsContainer = document.querySelector('.works__list');
  if (!projectsContainer) {
    return;
  }
  const filtersWrapper = document.querySelector('.works__filters');
  if (!filtersWrapper) {
    return;
  }
  const allProjectsInitialOrder = Array.from(projectsContainer.children);
  const categoryFiltersContainer = filtersWrapper.querySelector('.works__filters-categories');
  const technologyFiltersContainer = filtersWrapper.querySelector('.works__filters-technologies');
  const sortContainer = filtersWrapper.querySelector('.sorting');
  const categoryFilterButtons = categoryFiltersContainer.querySelectorAll('.categories__item-button');
  const technologyFilterButtons = technologyFiltersContainer.querySelectorAll('.technologies__item-button');

  let currentCategoryFilter = 'all';
  let currentTechnologyFilters = [];
  let currentSort = 'date-to-low';

  const updateProjectsDisplay = () => {
    let projectsToDisplay = [...allProjectsInitialOrder];

    projectsToDisplay = projectsToDisplay.filter(project => {
      if (currentCategoryFilter === 'all') {
        return true;
      }

      return project.dataset.category === currentCategoryFilter;
    });

    if (currentTechnologyFilters.length > 0) {
      projectsToDisplay = projectsToDisplay.filter(project => {
        const projectTechnologies = project.dataset.technologies ? project.dataset.technologies.split(',') : [];

        return currentTechnologyFilters.some(tech => projectTechnologies.includes(tech));
      });
    }

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
        projectsToDisplay.sort((a, b) => {
          const complexityA = parseInt(a.dataset.complexity);
          const complexityB = parseInt(b.dataset.complexity);

          if (complexityA === complexityB) {
            return new Date(a.dataset.date) - new Date(b.dataset.date);
          }

          return complexityA - complexityB;
        });
        break;
    }

    projectsContainer.innerHTML = '';
    projectsToDisplay.forEach(project => projectsContainer.appendChild(project));
  };

  categoryFilterButtons.forEach(button => {
    button.addEventListener('click', () => {
      const filterValue = button.dataset.filterValue;

      if (filterValue && filterValue !== currentCategoryFilter) {
        currentCategoryFilter = filterValue;
        categoryFilterButtons.forEach(btn => btn.classList.remove(ACTIVE_CATEGORY_FILTER_CLASS));
        button.classList.add(ACTIVE_CATEGORY_FILTER_CLASS);
        updateProjectsDisplay();
      }
    });
  });

  const defaultCategoryButton = categoryFiltersContainer.querySelector(`[data-filter-value="${currentCategoryFilter}"]`);

  if (defaultCategoryButton) {
    defaultCategoryButton.classList.add(ACTIVE_CATEGORY_FILTER_CLASS);
  }

  technologyFilterButtons.forEach(button => {
    button.addEventListener('click', () => {
      const filterValue = button.dataset.filterValue;

      if (filterValue) {
        const index = currentTechnologyFilters.indexOf(filterValue);
        if (index === -1) {
          currentTechnologyFilters.push(filterValue);
          button.classList.add(ACTIVE_TECHNOLOGY_FILTER_CLASS);
        } else {
          currentTechnologyFilters.splice(index, 1);
          button.classList.remove(ACTIVE_TECHNOLOGY_FILTER_CLASS);
        }
        updateProjectsDisplay();
      }
    });
  });


  const initSort = (container) => {
    const sortButton = container.querySelector('.sorting__current');
    const sortList = container.querySelector('.sorting__list');
    const sortOptions = sortList.querySelectorAll('.sorting__option');

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
