<script>
    export default {
        template: require('../../templates/TestIndex.html'),
        props: ["baseUrl", "message", "tests", "questionTypes", "title", "add", "fields"],
        data() {
            return {
                internalMessage: this.message,
                items: this.tests,
                test: null,
                error: false,
                qTypes: this.questionTypes,
                question: {}
            }
        },
        methods: {
            deleteTest: function (id) {
                axios.delete(this.baseUrl + '/tests/' + id).then(response => {
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
                    case 'text':
                        correct = true;
                        break;
                }

                if (correct) {
                    this.question.test_id = this.test;

                    axios.post(this.baseUrl + '/questions', this.question).then(response => {
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
        }
    }
</script>
