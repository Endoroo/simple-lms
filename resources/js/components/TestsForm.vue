<template>
    <b-container>
        <b-alert v-if="message !== ''" show dismissible>{{ message }}</b-alert>
        <b-card :header="header">
            <a :href="baseUrl" class="d-block pb-4">Отмена</a>
            <b-form :action="action" method="post">
                <b-button type="submit" variant="primary">Сохранить</b-button>
                <b-form-group label-for="test-name" label="Название теста">
                    <b-form-input id="test-name"
                                  name="name"
                                  v-model="form.name"
                                  type="text"
                                  placeholder="введите название теста"
                                  :state="check(form.name, 'name')"></b-form-input>
                    <b-form-invalid-feedback id="test-name">{{ errors.name }}</b-form-invalid-feedback>
                </b-form-group>

                <b-card header="Настройки" class="mb-4">
                    <b-form-group label-for="test-time" label="Время прохождения">
                        <b-form-input id="test-time"
                                      name="settings[time]"
                                      v-model="form.settings.time"
                                      type="time" step="2"
                                      description="ЧЧ:ММ:СС - сколько длится тест"
                                      :state="check(form.settings.time, 'settings.time')"></b-form-input>
                        <b-form-invalid-feedback id="test-time"
                                                 v-if="typeof errors['settings.time'] !== 'undefined'">{{ errors['settings.time'] }}</b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group label-for="test-percent" label="Процент правильных ответов для сдачи">
                        <b-form-input id="test-percent"
                                      name="settings[percent]"
                                      v-model="form.settings.percent"
                                      type="number"
                                      placeholder="введите число"
                                      :state="check(form.settings.percent, 'settings.percent')"></b-form-input>
                        <b-form-invalid-feedback id="test-percent"
                                                 v-if="typeof errors['settings.percent'] !== 'undefined'">{{ errors['settings.percent'] }}</b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group label-for="test-retake" label="Количество пересдач">
                        <b-form-input id="test-retake"
                                      name="settings[retake]"
                                      v-model="form.settings.retake"
                                      type="number"
                                      placeholder="введите число"
                                      description="0 - бесконечное число попыток"
                                      :state="check(form.settings.retake, 'settings.retake')"></b-form-input>
                        <b-form-invalid-feedback id="test-time"
                                                 v-if="typeof errors['settings.retake'] !== 'undefined'">{{ errors['settings.retake'] }}</b-form-invalid-feedback>
                    </b-form-group>
                </b-card>

                <b-card header="Опции">
                    <b-form-checkbox-group id="options" name="settings[options][]" v-model="form.settings.options">
                        <b-form-checkbox value="random_order">Случайный порядок вопросов</b-form-checkbox>
                        <b-form-checkbox value="allow_skip">Возможность пропускать вопросы</b-form-checkbox>
                        <b-form-checkbox value="allow_back">Возможность возвращаться назад</b-form-checkbox>
                        <b-form-checkbox value="show_user_results">Показывать пользователю результаты</b-form-checkbox>
                    </b-form-checkbox-group>
                </b-card>
                <input type="hidden" :value="csrf" name="_token"/>
                <input v-if="method === 'put' "name="_method" type="hidden" value="PUT">
            </b-form>
        </b-card>
    </b-container>
</template>

<script>
    export default {
        props: ["baseUrl", "csrf", "errors", "message", "default", "test"],
        data() {
            return {
                action: this.baseUrl,
                header: '',
                method: 'post',
                form: {
                    name: '',
                    settings: {
                        time: '02:00:00',
                        percent: 75,
                        retake: 0,
                        options: []
                    }
                }
            }
        },
        methods: {
            check: function(value, key) {
                if (!value) {
                    return typeof this.errors[key] !== 'undefined' ? false : null;
                }
                return null
            }
        },
        mounted() {
            if (Object.getOwnPropertyNames(this.test).length > 1) {
                this.header = "Редактирование теста";
                this.form = this.test;
                this.method = 'put';
                this.action = this.baseUrl + '/' + this.test.id
            }
            if (Object.getOwnPropertyNames(this.default).length > 1) {
                this.header = "Добавление теста";
                this.form = this.default
            }
        }
    }
</script>