// ====================
// エラーコードを格納するストア
// ====================

// state
// ===============
const state = () => ({
  code: null
});

// ===============
// getter
// ===============
const getters = {};

// ===============
// mutations
// ===============
const mutations = {
  // ステータスコードをセット
  setCode(state, code) {
    state.code = code
  }
};

// ===============
// actions
// ===============
const actions = {};

// ================
// export default
// ================
export default {
  namespaced: true,
  state,
  mutations,
};
