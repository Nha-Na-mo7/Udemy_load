<template>
<div>
  <Hero />
  <Overview />
  <Problems />
  <Features />
  <Begin />
</div>
</template>

<script>
import Hero from './Hero.vue'
import Overview from './Overview.vue'
import Problems from './Problems.vue'
import Features from './Features.vue'
import Begin from './Begin.vue'


export default {
  components: {
    Hero,
    Overview,
    Problems,
    Features,
    Begin,
  }
}
</script>