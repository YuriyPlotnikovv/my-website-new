document.addEventListener('DOMContentLoaded', () => {
  const FORM_STATE = {
    SUCCESS: 'SUCCESS',
    CAPTCHA_ERROR: 'CAPTCHA_ERROR',
    VALIDATION_ERROR: 'VALIDATION_ERROR',
    SAVE_ERROR: 'SAVE_ERROR',
  };
  const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  const interpolate = (str, vars) => {
    return str.replace(/\{(\w+)}/g, (_, key) => vars[key] ?? '');
  };

  const validationRules = {
    name: [
      (value) => {
        if (value.trim().length === 0) {
          return window.MESSAGES.ERROR_REQUIRED;
        }
        return true;
      },
      (value, config) => {
        if (value.trim().length < config.minLength) {
          return interpolate(window.MESSAGES.ERROR_NAME_TOO_SHORT, {min: config.minLength});
        }
        return true;
      },
    ],
    email: [
      (value) => {
        if (value.trim().length === 0) {
          return window.MESSAGES.ERROR_REQUIRED;
        }
        return true;
      },
      (value) => {
        if (!EMAIL_REGEX.test(value)) {
          return window.MESSAGES.ERROR_EMAIL;
        }
        return true;
      },
    ],
    message: [
      (value) => {
        if (value.trim().length === 0) {
          return window.MESSAGES.ERROR_REQUIRED;
        }
        return true;
      },
      (value, config) => {
        if (value.trim().length < config.minLength) {
          return interpolate(window.MESSAGES.ERROR_MESSAGE_TOO_SHORT, {min: config.minLength});
        }
        return true;
      },
      (value, config) => {
        if (value.trim().length > config.maxLength) {
          return interpolate(window.MESSAGES.ERROR_MESSAGE_TOO_LONG, {max: config.maxLength});
        }
        return true;
      },
    ],
    agreement: [
      (value) => {
        if (!value) {
          return window.MESSAGES.ERROR_REQUIRED;
        }
        return true;
      },
    ],
  };

  const initialize = (element) => {
    const app = Vue.createApp({
      setup() {
        const {ref, reactive, onMounted} = Vue;

        const form = reactive({
          name: '',
          email: '',
          message: '',
          agreement: false,
          captchaResponse: '',
          submitUrl: '',
        });

        const submitted = ref(false);
        const submittingForm = ref(false);
        const error = ref(false);
        const state = ref('');

        const errorMap = reactive({
          name: '',
          email: '',
          message: '',
          agreement: '',
        });

        const touched = reactive({
          name: false,
          email: false,
          message: false,
          agreement: false,
        });

        const fieldConfigs = reactive({});

        const validateField = (fieldName) => {
          let isValid = true;
          errorMap[fieldName] = '';

          const rules = validationRules[fieldName];
          if (!rules) return true;

          const value = form[fieldName];
          const config = fieldConfigs[fieldName] || {};

          for (const rule of rules) {
            const result = rule(value, config);
            if (result !== true) {
              errorMap[fieldName] = result;
              isValid = false;
              break;
            }
          }
          return isValid;
        };

        const validateAllFields = () => {
          let allFieldsValid = true;

          Object.keys(validationRules).forEach(fieldName => {
            const fieldValid = validateField(fieldName);
            if (!fieldValid) {
              allFieldsValid = false;
            }
          });

          return allFieldsValid;
        };

        const captchaId = ref(null);

        const waitForCaptcha = () => {
          return new Promise(resolve => {
            if (window.smartCaptcha) {
              resolve();
            } else {
              const timer = setInterval(() => {
                if (window.smartCaptcha) {
                  clearInterval(timer);
                  resolve();
                }
              }, 100);
            }
          });
        };

        const initCaptcha = () => {
          const containerId = `captcha-${element.dataset.vue}`;
          let container = document.getElementById(containerId);

          if (!container) {
            container = document.createElement('div');
            container.id = containerId;
            const formFooter = element.querySelector('.form__footer');
            if (formFooter) {
              formFooter.before(container);
            }
          }

          captchaId.value = window.smartCaptcha.render(containerId, {
            sitekey: window.captchaPublicKey,
            invisible: true,
            callback: token => {
              form.captchaResponse = token || '';
              if (form.captchaResponse) {
                submit();
              }
            },
            hideShield: true,
          });
        };

        const checkCaptcha = () => {
          Object.keys(validationRules).forEach(key => touched[key] = true);
          const allFieldsValid = validateAllFields();

          if (!allFieldsValid) {
            return;
          }

          if (captchaId.value !== null && window.smartCaptcha) {
            submittingForm.value = true;
            window.smartCaptcha.execute(captchaId.value);
          } else {
            submitted.value = false;
            error.value = true;
            state.value = FORM_STATE.CAPTCHA_ERROR;
            submittingForm.value = false;
          }
        };

        const submit = () => {
          const formData = new FormData();
          Object.entries(form).forEach(([key, value]) => formData.append(key, value));

          fetch(
            form.submitUrl,
            {
              method: 'POST',
              body: formData,
            },
          )
            .then(response => {
              if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
              }
              return response.json();
            })
            .then(response => {
              submitted.value = response.success;
              error.value = !response.success;
              state.value = response.state;

              if (response.state === FORM_STATE.CAPTCHA_ERROR) {
                if (captchaId.value !== null && window.smartCaptcha) {
                  window.smartCaptcha.reset(captchaId.value);
                }
              }
            })
            .catch(error => {
              console.error('Error submitting form:', error);
              submitted.value = false;
              error.value = true;
              state.value = FORM_STATE.SAVE_ERROR;
            })
            .finally(() => {
              submittingForm.value = false;
            });
        };

        onMounted(async () => {
          const formElement = element.querySelector('form');
          if (formElement) {
            form.submitUrl = formElement.action;

            Object.keys(validationRules).forEach(fieldName => {
              const input = formElement.querySelector(`[name="${fieldName}"]`);
              if (input) {
                fieldConfigs[fieldName] = {
                  minLength: parseInt(input.getAttribute('minlength')) || 0,
                  maxLength: parseInt(input.getAttribute('maxlength')) || Infinity,
                };
              }
            });
          }

          await waitForCaptcha();
          initCaptcha();
        });

        const handleBlur = (event) => {
          const target = event.target;
          const name = target.getAttribute('name');

          if (name && touched[name]) {
            validateField(name);
          }
        };

        const handleInput = (event) => {
          const target = event.target;
          const name = target.getAttribute('name');

          if (name) {
            touched[name] = true;
          }
        };

        return {
          form,
          submitted,
          submittingForm,
          error,
          state,
          errorMap,
          touched,
          SUCCESS: FORM_STATE.SUCCESS,
          CAPTCHA_ERROR: FORM_STATE.CAPTCHA_ERROR,
          VALIDATION_ERROR: FORM_STATE.VALIDATION_ERROR,
          SAVE_ERROR: FORM_STATE.SAVE_ERROR,
          checkCaptcha,
          handleBlur,
          handleInput,
        };
      },
    });

    app.mount(element);
  };

  const forms = document.querySelectorAll('[data-vue]');

  forms.forEach(element => {
    initialize(element);
  });
});
