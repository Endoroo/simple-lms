<template>
    <b-container>
        <b-alert v-if="internalMessage !== ''" show dismissible>{{ internalMessage }}</b-alert>
        <b-card :header="title">
            <a :href="addLink" class="d-block pb-4">{{ add }}</a>
            <b-table :items="items" :fields="fields">
                <template slot="actions" slot-scope="data">
                    <b-button variant="primary" @click="showForm(data.item.id)">Добавить вопрос</b-button>
                    <b-button variant="secondary" :href="'/tests/' + data.item.id + '/edit'">Редактировать</b-button>
                    <b-button variant="danger" @click="deleteTest(data.item.id)">Удалить</b-button>
                </template>
            </b-table>
        </b-card>

        <b-modal ref="addQuestion"
                 title="Добавить вопрос"
                 cancel-title="Отмена"
                 ok-title="Добавить"
                 @ok="addQuestion">
            <b-form @submit="addQuestion">
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
                                  :state="typeof question.settings.extensions === 'undefined' && error ? false : null">
                    </b-form-input>
                    <b-form-invalid-feedback id="extensionFieldInvalid">Не может быть пустым</b-form-invalid-feedback>
                </b-form-group>
            </b-form>
        </b-modal>
    </b-container>
</template>

<script>
    export default {
        props: ["addLink", "message"],
    data() {
        return {
            internalMessage: this.message,
            items: [],
            fields: [],
            title: '',
            add: '',
            test: null,
            error: false,
            qTypes: [
                { value: null, text: '...' },
                { value: 'list', text: 'Вопрос с несколькими ответами' },
                { value: 'file', text: 'Вопрос с загрузкой файла' },
                { value: 'text', text: 'Вопрос с текстовым ответом' },
            ],
            question: {}
        }
    },
    methods: {
        deleteTest: function (id) {
            axios.delete('/tests/' + id).then(response => {
                this.items = response.data.items
            })
        },
        showForm(id) {
            this.test = id;
            this.flushForm();
            this.$refs.addQuestion.show()
        },
        flushForm() {
            this.question = {
                question: '',
                points: 1,
                type: null,
                settings: {}
            }
        },
        setSettings(value) {
            switch (value) {
                case 'list':
                    this.question.settings = {
                        strict: 0,
                        variants: [{ variant: '', points: '0' }],
                    };
                    break;
                case 'file':
                    this.question.settings = {
                        extensions: ''
                    };
                    break;
            }
        },
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
        addRow() {
            this.error = false;
            this.question.settings.variants.push({
                text: '',
                points: '0'
            });
        },
        removeRow(key) {
            this.question.settings.variants = this.question.settings.variants.filter((item, i) => {
                return i !== key;
            })
        },
        addQuestion(event) {
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
            }

            if (correct) {
                this.question.test_id = this.test;

                axios.post('/questions', this.question).then(response => {
                    if (response.data.success) {
                        this.test = null;
                        this.flushForm();
                        this.error = false;
                        this.$refs.addQuestion.hide();
                        this.internalMessage = response.data.message
                    }
                });
            }
        }
    },
    mounted() {
        this.flushForm();
        axios.post('/tests/frontend').then(response => {
            this.title = response.data.title;
            this.add = response.data.add;
            this.fields = response.data.fields;
            this.items = response.data.items
            })
        }
    }
</script>
