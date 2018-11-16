<template>
    <b-container>
        <b-card header="Добавление теста">
            <a :href="url">Отмена</a>
            <b-form :action="url" method="post">
                <b-button type="submit" variant="primary">Сохранить</b-button>
                <b-form-group label-for="test-name" label="Название теста">
                    <b-form-input id="test-name"
                                  v-model="form.name"
                                  type="text"
                                  placeholder="введите название теста"></b-form-input>
                </b-form-group>
            </b-form>
        </b-card>
    </b-container>
</template>

<script>
  export default {
    props: ["url"],
    data() {
      return {
        form: {
          name: '',
        }
      }
    }
  }
</script>