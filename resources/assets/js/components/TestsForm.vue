<template>
    <b-container>
        <b-card header="Добавление теста">
            <a :href="url">Отмена</a>
            <b-form :action="url" method="post">
                <b-button type="submit" variant="primary">Сохранить</b-button>
                <b-form-group label-for="test-name" label="Название теста">
                    <b-form-input id="test-name"
                                  name="name"
                                  v-model="form.name"
                                  type="text"
                                  placeholder="введите название теста"></b-form-input>
                </b-form-group>
                {{ errors.name }}

                <b-card header="Настройки" class="mb-4">
                    <b-form-group label-for="test-time" label="Время прохождения">
                        <b-form-input id="test-time"
                                      name="settings[time]"
                                      v-model="form.settings.time"
                                      type="time"
                                      placeholder="сколько длится тест"></b-form-input>
                    </b-form-group>
                    <b-form-group label-for="test-percent" label="Процент правильных ответов для сдачи">
                        <b-form-input id="test-percent"
                                      name="settings[percent]"
                                      v-model="form.settings.percent"
                                      type="number"
                                      placeholder="введите число"></b-form-input>
                    </b-form-group>
                    <b-form-group label-for="test-retake" label="Количество пересдач">
                        <b-form-input id="test-retake"
                                      name="settings[retake]"
                                      v-model="form.settings.retake"
                                      type="number"
                                      placeholder="введите число"></b-form-input>
                    </b-form-group>
                </b-card>

                <b-card header="Опции">
                    <b-form-checkbox-group id="options" name="options[]" v-model="form.settings.options">
                        <b-form-checkbox value="random_order">Случайный порядок вопросов</b-form-checkbox>
                        <b-form-checkbox value="allow_skip">Возможность пропускать вопросы</b-form-checkbox>
                        <b-form-checkbox value="allow_back">Возможность возвращаться назад</b-form-checkbox>
                    </b-form-checkbox-group>
                </b-card>
                <input type="hidden" :value="csrf" name="_token"/>
            </b-form>
        </b-card>
    </b-container>
</template>

<script>
  export default {
    props: ["url", "csrf", "errors"],
    data() {
      return {
        form: {
          name: '',
          settings: {
            time: '',
            percent: '',
            retake: '',
            options: []
          }
        }
      }
    }
  }
</script>