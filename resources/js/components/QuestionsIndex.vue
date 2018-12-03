<template>
    <b-container sm="auto">
        <div class="pt-4 pb-2"><strong>Список вопросов теста</strong></div>
        <b-list-group>
            <b-list-group-item v-for="(question, key) in items" :key="key">
                <div><strong>{{question.question }}</strong></div>
                <b-row>
                    <b-col><b-badge variant="secondary">{{ question.type }}</b-badge></b-col>
                    <b-col><b-badge variant="warning">{{ question.points }}</b-badge></b-col>
                    <b-col><b-btn variant="secondary">Редактировать</b-btn><b-btn variant="danger">Удалить</b-btn></b-col>
                </b-row>
            </b-list-group-item>
        </b-list-group>
    </b-container>
</template>

<script>
    export default {
        props: ['questions'],
        data() {
            return {
                items: this.questions,
            }
        }
    }
</script>

<style scoped>

</style>