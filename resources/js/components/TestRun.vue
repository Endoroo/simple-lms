<template>
    <b-container v-if="currentQ && time">
        <b-card :header="'Вопрос №' + currentQ.number">
            <p>{{ currentQ.question }}</p>
            <div v-if="currentQ.type === 'file'" class="mb-3">
                <b-form-file v-model="answer" :state="Boolean(answer)" placeholder="Выберите файл..."></b-form-file>
                <div class="mt-2" v-if="answer">Выбранный файл: {{answer && answer.name}}</div>
            </div>
            <div v-else-if="currentQ.type === 'text'" class="mb-3">
                <b-form-textarea v-model="answer"
                                 placeholder="Наберите какой-нибудь текст..."
                                 :rows="3"
                                 :max-rows="6"></b-form-textarea>
            </div>
            <div v-els class="mb-3">
                <b-list-group>
                    <b-list-group-item v-for="item in currentQ.settings.variants" href="#">{{ item.variant }}</b-list-group-item>
                </b-list-group>
            </div>
            <b-btn @click="next">Продолжить</b-btn>
        </b-card>
        <p>{{ time }}</p>
    </b-container>
</template>

<script>
  export default {
    props: ['test', 'url'],
    data() {
      return {
        answer: null,
        currentQ: null,
        state: this.test,
        time: this.test.remain_time
      }
    },
    methods: {
      next() {
        axios.post(this.url + '/next').then(response => {
          if (typeof response.errors === 'undefined') {
            this.currentQ = response.data.question
          }
        });
      }
    },
    mounted() {
      this.currentQ = this.state.questions[this.state.current_question];
      setInterval(function(){
        let text = this.time.split(':');
        for (let i = 0; i < text.length; i++) {
          text[i] = parseInt(text[i]);
        }
        if(text[0] || text[1] || text[2]) {
          if (!text[2] && !text[1]) {
            text[2] = 59;
            text[1] = 59;
            text[0] = text[0] - 1
          } else {
            if (!text[2]) {
              text[2] = 59;
              text[1] = text[1] - 1
            } else
              text[2] = text[2] - 1;
          }
          for (let i = 0; i < text.length; i++)
            if (text[i] < 10) text[i] = '0' + text[i];
          this.time = text.join(':');
        }
        else {
          location.reload()
        }
      }.bind(this), 1000)
    }
  }
</script>

<style scoped>

</style>