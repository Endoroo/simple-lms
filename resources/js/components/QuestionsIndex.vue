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
                        <b-btn variant="secondary">Редактировать</b-btn>
                        <b-btn variant="danger" @click="showModal(question.id)">Удалить</b-btn>
                    </b-col>
                </b-row>
            </b-list-group-item>
        </b-list-group>

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
        props: ['questions', 'baseUrl', 'testId'],
        data() {
            return {
                items: this.questions,
                error: false,
                questionId: null
            }
        },
        methods: {
            showModal(id) {
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

<style scoped>

</style>