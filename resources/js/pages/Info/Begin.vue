<!--無料で今すぐはじめよう-->
<template>
  <section class="p-landing__section p-landing__section--bg">
    <div class="p-landing__container p-landing__container--flex">
      <div class="p-landing__section--info">
        <h1 class="p-landing__section--title">
          ロードマップを作りましょう
        </h1>
        <p class="p-landing__section--text u-mb-3l">
          <span>登録は無料。早速学習ロードマップをみんなで共有しましょう。</span>
        </p>
      </div>
      <div class="u-text--center u-mt-xl">
        <button v-if="isNotLogin" class="c-btn u-color--info u-font--bold">
          <a class="" href="/login">
            今すぐはじめる
          </a>
        </button>
        <button v-else class="c-btn u-color--info u-font--bold">
          <a class="" href="/records/new">
            今すぐロードマップを作成
          </a>
        </button>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  computed: {
    isNotLogin() {
      return !this.$store.getters['auth/check'];
    },
  },
}
</script>