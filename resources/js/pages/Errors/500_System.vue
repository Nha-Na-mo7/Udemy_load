<template>
  <div class="p-error__container">
    <div class="p-error">
      <div class="p-error__statusCode">
        <span class="p-error__statusCode--code">500</span>
        <span class="p-error__statusCode--text">Server Error</span>
      </div>
      <div class="p-error__description">
        <p>サーバーで問題が発生しました。</p>
        <p>お手数ですが、しばらく時間を置いてからやり直してください。</p>
      </div>
      <!-- 戻るボタン -->
      <div>
        <a class="c-btn" href="/"> レコード一覧へ戻る </a>
      </div>
    </div>
  </div>
</template>
