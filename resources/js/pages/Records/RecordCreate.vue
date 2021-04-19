<!-- レコードの新規投稿 / 編集用 -->
<template>
  <div class="p-record__edit">
    <div class="p-record__edit--inner">
      <h2 class="p-record__edit--title u-mb-xl">{{ pageTitle }}</h2>

      <!-- 紹介したいコースについてのフォーム -->
      <section class="p-record__edit--form">

        <form class="p-form">
          <div class="p-form__description">
            <p>"コースを追加する" からUdemyの講座を登録してください</p>
          </div>

          <!-- タイトル -->
          <label
              class="p-form__info"
              for="record_title">タイトル
          </label>
          <!-- エラーメッセージ -->
          <ul v-if="errors.errorsTitle">
            <li
                class="c-error"
                v-for="error in errors.errorsTitle"
                :key="error"
            >
              <span>{{ error }}</span>
            </li>
          </ul>
          <!-- 入力欄 -->
          <input
              type="text"
              id="record_title"
              class="p-form__title p-form__item c-form__input"
              v-model="createData.recordForm.title"
              placeholder="タイトルを入力してください"
          >

          <!-- 説明 -->
          <label
              class="p-form__info"
              for="record_description">
          </label>
          <!-- エラーメッセージ -->
          <ul v-if="errors.errorsDescription">
            <li
                class="c-error"
                v-for="error in errors.errorsDescription"
                :key="error"
            >
              <span>{{ error }}</span>
            </li>
          </ul>
          <!-- テキストエリア -->
          <textarea
              id="record_description"
              class="p-form__description p-form__item p-record__edit--textarea c-form__textarea"
              v-model="createData.recordForm.description"
              placeholder="説明文を入力してください"
          ></textarea>
        </form>
      </section>

      <!-- 現在追加されているコース-->
      <div>
        <SelectedCourse
            v-for="(Course, index) in createData.selectedCourses"
            :key="Course.id"
            :course="Course"
            :createflg="isCreateMode"
            :value="Course.description"
            @input="Course.description = $event"
            @deleteCourse="deleteCourseObject(index)"
        />
      </div>

      <!-- コースを追加する -->
      <div class="u-mb-3l">
        <button class="c-btn c-btn__addCourse" @click="toggleModalFlg">
          + コースを追加する
        </button>
      </div>

      <!-- 投稿する / 更新する -->
      <div class="u-mb-3l">
        <button
            class="c-btn c-btn__edit--submit"
            v-bind:disabled="!this.checkAllClear"
            v-on:click="this.submitCourse">{{ submitButton }}
        </button>
      </div>

      <!-- レコードを削除する(編集時のみ) -->
      <div
          v-if="!isCreateMode"
          class="p-record__delete"
      >
        <button
            class="c-btn c-btn--delete"
            @click="deleteRecord"
        >
          <i class="far fa-trash-alt p-mypage__record-list--icon"></i>
          このレコードを削除する
        </button>
      </div>
      <!-- モーダルがONになったら表示される -->
      <div v-if="modalFlg">
        <SearchModal
            @pushCourseObjToSelectedCoursesArr="pushCourseObjToSelectedCoursesArr"
            @toggleModal="toggleModalFlg"
        />
      </div>
    </div>
  </div>
</template>

<script>
import {CREATED, FORBIDDEN, OK, UDEMY_BASE_URL, UNPROCESSABLE_ENTITY} from "../../util";
import Loading from '../../components/Loading.vue';
import SelectedCourse from './SelectedCourse.vue';
import SearchModal from './SearchModal.vue';

export default {
  // このpropsはレコードの編集時のみ受け取る
  props: {
    id: {
      type: String,
    }
  },
  data() {
    return {
      loading: true,
      modalFlg: false,
      errors: {
        errorsTitle: '',
        errorsDescription: '',
      },
      createData: {
        selectedCourses: [],
        recordForm: {
          title: '',
          description: ''
        },
      }
    }
  },
  computed: {
    isCreateMode() {
      return this.id === undefined
    },
    pageTitle() {
      return this.isCreateMode ? 'レコードの新規登録' : 'レコードの編集'
    },
    submitButton() {
      return this.isCreateMode ? '投稿する' : '更新する'
    },
    checkVoidSelectedCourses() {
      return !this.createData.selectedCourses.length
    },
    checkExistTitle() {
      return this.createData.recordForm.title !== ''
    },
    checkExistDescription() {
      return this.createData.recordForm.description !== ''
    },
    // 投稿/更新に必要な全ての条件を満たしているかのチェック
    checkAllClear() {
      return !this.checkVoidSelectedCourses
      && !this.checkVoidCourseDescription()
      && this.checkExistTitle
      && this.checkExistDescription
    }
  },
  methods: {
    // レコードの情報をDBから取得(編集時のみ)
    async fetchRecord() {
      if (!this.isCreateMode) {
        // レコード情報を取得
        const response = await axios.get(`/record/${this.id}/${true}`)

        // オーナー以外が閲覧した場合(403)、詳細画面に戻す
        if (response.status === FORBIDDEN) {
          this.$router.push(`/records/${this.id}`);
          return false
        }

        // エラー時の処理
        if (response.status !== OK) {
          this.$store.commit('error/setCode', response.status);
          return false
        }
        console.log(response.data)
        // 格納
        this.createData.recordForm.title = response.data.title
        this.createData.recordForm.description = response.data.description
        this.createData.selectedCourses = response.data.courses
      }
      this.loading = false
    },
    // モーダルフラグを切り替え
    toggleModalFlg() {
      this.modalFlg = !this.modalFlg;
    },
    // 選択済みコースにコースオブジェクトを追加する
    pushCourseObjToSelectedCoursesArr(e) {
      if (this.isCreateMode) {
        this.createData.selectedCourses.push(e);
      }else{
        // 編集モードの場合は形式を変更
        console.log('EditMode change')
        const cObj = e.courseObject
        const updateObj = {
          course_id : cObj.id,
          image_url : cObj.image_240x135,
          instructor: cObj.visible_instructors[0]['title'],
          title: cObj.title,
          url: cObj.url,
        }
        console.log(updateObj)
        this.createData.selectedCourses.push(updateObj);
      }
    },
    // 全てのコースオブジェクトの説明が入力されているかチェック
    checkVoidCourseDescription () {
      // someでdescriptionが空欄のものが1つでもあればtrueを返す
      return this.createData.selectedCourses.some(e => e.description === '')
    },
    // オブジェクトを削除する
    deleteCourseObject(index) {
      this.createData.selectedCourses.splice(index, 1)
    },
    // コースのレコードを投稿 or 上書きする
    async submitCourse() {

      // 以下、disabledを無理やり突破して来た場合に警告を出す
      // タイトルが未入力の時
      if (!this.checkExistTitle) {
        alert('【！】タイトルを入力してください')
        return false
      }
      // 説明文が未入力の時
      if (!this.checkExistDescription) {
        alert('【！】コースを1つ以上追加してください')
        return false
      }
      // コースが1つもない場合
      if (this.checkVoidSelectedCourses) {
        alert('【！】コースを1つ以上追加してください')
        return false
      }
      // コースの中でdescriptionが未入力のものがある場合
      if (this.checkVoidCourseDescription()) {
        alert('説明が未入力のコースがあります')
        return false
      }

      // 新規作成か更新かで処理先を分ける
      if (this.isCreateMode) {
        const response = await axios.post(`../records/create`, this.createData);

        console.log(response)
        // バリデーションエラー
        if (response.status === UNPROCESSABLE_ENTITY) {
          this.errors = response.data.errors;
          return false
        }
        // 作成完了
        if (response.status !== CREATED) {
          this.$store.commit('error/setCode', response.status)
          return false
        }
        // 投稿後にその詳細ページへ遷移させる
        this.$router.push(`/records/${response.data.id}`)

      }else{
        const response = await axios.post(`/record/${this.id}/update`, this.createData);

        console.log(response.data)

        if (response.status !== OK) {
          this.$store.commit('error/setCode', response.status)
          return false
        }
        // 詳細ページへ戻す
        this.$router.push(`/records/${this.id}`)
      }
    },
    // このレコードを削除する
    async deleteRecord() {
      if(this.isCreateMode) {
        return false;
      }
      if (confirm('【レコードを削除します】\n削除すると復元することはできなくなります。よろしいですか？',)) {
        console.log('deleteRecord: ' + this.id)
        const response = await axios.post(`/record/${this.id}/delete`);

        // エラー時処理
        if (response.status !== OK) {
          this.$store.commit('error/setCode', response.status)
          return false
        }

        // 詳細ページへ戻す
        this.$router.push(`/mypage/${this.$store.getters['auth/username']}`)
      }
      return false
    }
  },
  components: {
    Loading,
    SelectedCourse,
    SearchModal
  },
  watch: {
    $route: {
      async handler() {
        await this.fetchRecord()
      },
      immediate: true
    }
  }
}

</script>

<style scoped>

</style>