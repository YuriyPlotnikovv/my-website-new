<?php
global $LANG, $MESS;

$messages = [
    'FORM_STATE_SUCCESS' => $MESS['FORM_STATE_SUCCESS'],
    'FORM_STATE_SAVE_ERROR' => $MESS['FORM_STATE_SAVE_ERROR'],
    'ERROR_REQUIRED' => $MESS['ERROR_REQUIRED'],
    'ERROR_EMAIL' => $MESS['ERROR_EMAIL'],
    'ERROR_NAME_TOO_SHORT' => $MESS['ERROR_NAME_TOO_SHORT'],
    'ERROR_MESSAGE_TOO_SHORT' => $MESS['ERROR_MESSAGE_TOO_SHORT'],
    'ERROR_MESSAGE_TOO_LONG' => $MESS['ERROR_MESSAGE_TOO_LONG'],
];
?>

<section class="contacts-page__feedback section feedback">
    <div class="section__wrapper feedback__wrapper">
        <h2 class="section__title feedback__title">
            <?= $MESS['CONTACTS_FORM_TITLE'] ?>:
        </h2>

        <div class="feedback__form form" data-vue="feedback">
            <form class="form__wrapper"
                  name="feedback"
                  id="feedback"
                  action="/ajax/feedback.php"
                  @submit.prevent="checkCaptcha"
                  method="post"
                  autocomplete="on"
                  novalidate
            >
                <template v-if="!submitted && !error">
                    <div class="form__row">
                        <div class="form__field">
                            <label class="form__label" for="name">
                                <?= $MESS['FORM_LABEL_NAME'] ?>
                                <span class="form__label-required"> *</span>
                            </label>

                            <input class="form__input"
                                   :class="{'form__input--invalid': errorMap.name && touched.name}"
                                   type="text"
                                   name="name"
                                   id="name"
                                   minlength="3"
                                   v-model="form.name"
                                   @blur="handleBlur"
                                   @input="handleInput"
                                   placeholder="<?= $MESS['FORM_PLACEHOLDER_NAME'] ?>"
                                   autocomplete="name"
                                   aria-describedby="name-error"
                                   required
                            >

                            <div class="form__error" id="name-error" role="alert" aria-live="polite">
                                {{ errorMap.name }}
                            </div>
                        </div>

                        <div class="form__field">
                            <label class="form__label" for="email">
                                <?= $MESS['FORM_LABEL_EMAIL'] ?>
                                <span class="form__label-required">*</span>
                            </label>

                            <input class="form__input"
                                   :class="{'form__input--invalid': errorMap.email && touched.email}"
                                   type="email"
                                   name="email"
                                   id="email"
                                   v-model="form.email"
                                   @blur="handleBlur"
                                   @input="handleInput"
                                   placeholder="<?= $MESS['FORM_PLACEHOLDER_EMAIL'] ?>"
                                   autocomplete="email"
                                   aria-describedby="email-error"
                                   required
                            >

                            <div class="form__error" id="email-error" role="alert" aria-live="polite">
                                {{ errorMap.email }}
                            </div>
                        </div>
                    </div>

                    <div class="form__row">
                        <div class="form__field form__field--textarea">
                            <label class="form__label" for="message">
                                <?= $MESS['FORM_LABEL_MESSAGE'] ?>
                                <span class="form__label-required">*</span>
                            </label>

                            <textarea class="form__input form__input--textarea"
                                      :class="{'form__input--invalid': errorMap.message && touched.message}"
                                      name="message"
                                      id="message"
                                      v-model="form.message"
                                      @blur="handleBlur"
                                      @input="handleInput"
                                      cols="30"
                                      rows="5"
                                      minlength="10"
                                      maxlength="500"
                                      placeholder="<?= $MESS['FORM_PLACEHOLDER_MESSAGE'] ?>"
                                      aria-describedby="message-error"
                                      required
                            ></textarea>

                            <div class="form__error" id="message-error" role="alert" aria-live="polite">
                                {{ errorMap.message }}
                            </div>
                        </div>
                    </div>

                    <div class="form__footer">
                        <div class="form__field form__field--agreement">
                            <input class="form__input form__input--checkbox"
                                   :class="{'form__input--invalid': errorMap.agreement && touched.agreement}"
                                   type="checkbox"
                                   name="agreement"
                                   id="agreement"
                                   v-model="form.agreement"
                                   @blur="handleBlur"
                                   @input="handleInput"
                                   autocomplete="off"
                                   aria-describedby="agreement-error"
                                   required
                            >

                            <label class="form__label" for="agreement">
                                <?= $MESS['FORM_LABEL_AGREEMENT'] ?>
                            </label>

                            <div class="form__error" id="agreement-error" role="alert" aria-live="polite">
                                {{ errorMap.agreement }}
                            </div>
                        </div>

                        <button class="form__button button button--shadow"
                                type="submit"
                                :disabled="isFormInvalid || submittingForm"
                        >
                            <?= $MESS['FORM_SUBMIT'] ?>
                        </button>
                    </div>
                </template>

                <template v-if="submitted || error">
                    <div class="form__result" v-if="state === SUCCESS" role="alert" aria-live="polite">
                        <?= $MESS['FORM_STATE_SUCCESS'] ?>
                    </div>

                    <div class="form__result" v-if="state === CAPTCHA_ERROR" role="alert" aria-live="polite">
                        <?= $MESS['FORM_STATE_CAPTCHA_ERROR'] ?>
                    </div>

                    <div class="form__result" v-if="state === VALIDATION_ERROR" role="alert" aria-live="polite">
                        <?= $MESS['FORM_STATE_VALIDATION_ERROR'] ?>
                    </div>

                    <div class="form__result" v-if="state === SAVE_ERROR" role="alert" aria-live="polite">
                        <?= $MESS['FORM_STATE_SAVE_ERROR'] ?>
                    </div>
                </template>
            </form>
        </div>
    </div>
</section>

<script>
    window.MESSAGES = <?= json_encode($messages, JSON_UNESCAPED_UNICODE) ?>;
</script>