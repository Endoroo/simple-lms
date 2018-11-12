<template>
    <b-container>
        <b-card :header="title">
            <b-button>{{ add }}</b-button>
            <b-table :items="items" :fields="fields"></b-table>
        </b-card>
    </b-container>
</template>

<script>
    export default {
        data() {
            return {
              items: [],
              fields: [],
              title: '',
              add: ''
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
