<!--ヒーローバナー-->
<template>
  <section class="p-landing__hero p-landing__section p-landing__information">
    <div class="p-landing__container p-landing__container--hero">
      <div class="p-landing__section--info u-text--left">
        <h1 class="p-landing__section--title p-landing__section--title--logo">
          Udemyの講座を組み合わせて<br />
          あなただけの学習ロードマップを作ろう
        </h1>
      </div>
      <div class="u-text--center u-mt-50">
        <button v-if="isNotLogin" class="c-btn u-color--info u-font--bold u-mb-xl">
          <a class="" href="/login">
            今すぐはじめる
          </a>
        </button>
        <br />
        <button class="c-btn u-color--info u-font--bold">
          <a class="" href="/recordlist">
            みんなのロードマップを見る
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