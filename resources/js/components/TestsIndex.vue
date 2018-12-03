<template>
    <b-container>
        <b-alert v-if="message !== ''" show dismissible>{{ message }}</b-alert>
        <b-card :header="title">
            <a :href="addLink">{{ add }}</a>
            <b-table :items="items" :fields="fields">
                <template slot="actions" slot-scope="data">
                    <b-button variant="primary" @click="showForm">Добавить вопрос</b-button>
                    <b-button variant="secondary" :href="'/tests/' + data.item.id + '/edit'">Редактировать</b-button>
                    <b-button variant="danger" @click="deleteTest(data.item.id)">Удалить</b-button>
                </template>
            </b-table>
        </b-card>

        <b-modal ref="addQuestion"
                 title="Добавить вопрос"
                 cancel-title="Отмена"
                 ok-title="Добавить">
            <p>1233</p>
        </b-modal>
    </b-container>
</template>

<script>
    export default {
        props: ["addLink", "message"],
    data() {
        return {
            items: [],
            fields: [],
            title: '',
            add: '',
        }
    },
    methods: {
        deleteTest: function (id) {
            axios.delete('/tests/' + id).then(response => {
                this.items = response.data.items
            })
        },
        showForm() {
            console.log(123);
            console.log(this.$refs.addQuestion.show());
            console.log(312);
        }
    },
    mounted() {
        axios.post('/tests/frontend').then(response => {
            this.title = response.data.title;
            this.add = response.data.add;
            this.fields = response.data.fields;
            this.items = response.data.items
            })
        }
    }
</script>
