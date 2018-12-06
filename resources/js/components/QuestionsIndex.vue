<template>
    <b-container sm="auto" v-if="questions.length">
        <b-alert :show="error" dismissible>Произошла ошибка</b-alert>
        <div class="pt-4 pb-2"><strong>Список вопросов теста</strong></div>
        <b-list-group>
            <b-list-group-item v-for="(question, key) in items" :key="key">
                <div><strong>{{question.question }}</strong></div>
                <b-row>
                    <b-col><b-badge variant="secondary">{{ question.type }}</b-badge></b-col>
                    <b-col><b-badge variant="warning">{{ question.points }}</b-badge></b-col>
                    <b-col>
                        <b-btn variant="secondary" @click="showEditModal(question.object)">Редактировать</b-btn>
                        <b-btn variant="danger" @click="showDelModal(question.id)">Удалить</b-btn>
                    </b-col>
                </b-row>
            </b-list-group-item>
        </b-list-group>

        <b-modal ref="update"
                 title="Редактировать вопрос"
                 cancel-title="Отмена"
                 ok-title="Обновить"
                 @ok="update">
            <b-form @submit="updateQuestion">
                <b-form-group label="Вопрос">
                    <b-form-input type="text"
                                  v-model="question.question"
                                  aria-describedby="questionFieldInvalid"
                                  :state="question.question === '' && error ? false : null">
                    </b-form-input>
                    <b-form-invalid-feedback id="questionFieldInvalid">Не может быть пустым</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Баллы">
                    <b-form-input type="number"
                                  v-model="question.points"
                                  aria-describedby="pointsFieldInvalid"
                                  :state="!question.points && error ? false : null">
                    </b-form-input>
                    <b-form-invalid-feedback id="pointsFieldInvalid">Не может быть пустым</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Тип вопроса">
                    <b-form-select :options="qTypes"
                                   v-model="question.type"
                                   @change="setSettings"
                                   aria-describedby="typeFieldInvalid"
                                   :state="question.type === null && error ? false : null">
                    </b-form-select>
                    <b-form-invalid-feedback id="typeFieldInvalid">Выберите тип вопроса</b-form-invalid-feedback>
                </b-form-group>

                <div v-if="question.type === 'list'">
                    <b-form-checkbox v-model="question.settings.strict"
                                     value="1"
                                     unchecked-value="0">Учитывать только полностью правильный ответ</b-form-checkbox>
                    <b-row v-for="(params, key) in question.settings.variants" :key="key">
                        <b-col>
                            <b-form-group label="Вариант">
                                <b-form-input type="text"
                                              v-model="params.variant"
                                              :aria-describedby="'variantFieldInvalid' + key"
                                              :state="params.variant === '' && error ? false : null">
                                </b-form-input>
                                <b-form-invalid-feedback :id="'variantFieldInvalid' + key">Не может быть пустым</b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group label="Баллы">
                                <b-form-input type="text"
                                              v-model="params.points"
                                              :aria-describedby="'pointsFieldInvalid' + key"
                                              :state="(params.points = clearFloat(params.points)) === '' && error ? false : null">
                                </b-form-input>
                                <b-form-invalid-feedback :id="'pointsFieldInvalid' + key">Некорректное значение</b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group label="&nbsp;" v-if="key !== question.settings.variants.length - 1">
                                <b-btn size="sm" @click="removeRow(key)" variant="danger">-</b-btn>
                            </b-form-group>
                            <b-form-group label="&nbsp;" v-if="key === question.settings.variants.length - 1">
                                <b-btn size="sm"
                                       @click="addRow()"
                                       variant="success"
                                       :class="!(question.settings.variants.length - 1) && error ? 'is-invalid' : ''">+</b-btn>
                            </b-form-group>
                        </b-col>
                    </b-row>
                </div>

                <b-form-group label="Расширения файла"
                              description="ведите расширения файлов через запятую: <i>doc,pdf</i>"
                              v-if="question.type === 'file'">
                    <b-form-input type="text"
                                  v-model="question.settings.extensions"
                                  aria-describedby="extensionFieldInvalid"
                                  :state="question.settings.extensions === '' && error ? false : null">
                    </b-form-input>
                    <b-form-invalid-feedback id="extensionFieldInvalid">Не может быть пустым</b-form-invalid-feedback>
                </b-form-group>
            </b-form>
        </b-modal>

        <b-modal ref="confirmDeletion"
                 title="Удаление вопроса"
                 cancel-title="Отмена"
                 ok-title=" OK"
                 @ok="remove()">
            <p><strong>Вы уверены?</strong></p>
        </b-modal>
    </b-container>
</template>

<script>
    export default {
        props: ['questions', 'questionTypes', 'baseUrl', 'testId'],
        data() {
            return {
                items: this.questions,
                error: false,
                qTypes: this.questionTypes,
                questionId: null,
                question: {
                    question: '',
                    points: 1,
                    type: null,
                    settings: {}
                }
            }
        },
        methods: {
            clearFloat(value) {
                if (typeof value !== 'undefined') {
                    if (value.toString().indexOf(',') >= 0) {
                        value = value.replace(',', '.');
                    }
                    value = value.toString().replace(/[^\d\.]+/g, '');
                    return value === '' ? '' : parseFloat(value)
                }
                return value;
            },
            showEditModal(question) {
                this.error = false;
                this.question = question;
                if (this.question.type === 'list') {
                    this.question.settings.variants.push({ variant: '', points: '0' });
                }
                this.$refs.update.show();
            },
            updateQuestion() {
                event.preventDefault();
                this.error = true;
                let correct = false;

                switch (this.question.type) {
                    case 'list':
                        let count = this.question.settings.variants.length - 1;
                        if (count > 0) {
                            let correctItems = 0;
                            this.question.settings.variants.forEach(function(item, key) {
                                if (key !== count) {
                                    correctItems += (item.variant !== '' && this.clearFloat(item.points) !== '');
                                }
                            }.bind(this));
                            correct = correctItems === count;
                            if (correct) {
                                this.question.settings.variants.pop();
                            }
                        }
                        break;
                    case 'file':
                        correct = this.question.settings.extensions !== '';
                        break;
                    case 'text':
                        correct = true;
                        break;
                }

                if (correct) {
                    this.question.test_id = this.testId;

                    axios.post(this.baseUrl + '/questions/' + this.questionId, this.question).then(response => {
                        if (response.data.success) {
                            this.test = null;
                            this.flushForm();
                            this.error = false;
                            this.$refs.addQuestion.hide();
                            this.internalMessage = response.data.message
                        }
                    });
                }
            },

            showDelModal(id) {
                this.error = false;
                this.questionId = id;
                this.$refs.confirmDeletion.show();
            },
            remove() {
                axios.delete(this.baseUrl + '/questions/' + this.questionId).then(response => {
                    if (typeof response.data.error === 'undefined') {
                        location.href = this.baseUrl + '/tests/' + this.testId + '/edit'
                    } else {
                        this.error = true
                    }
                })
            }
        }
    }
</script>