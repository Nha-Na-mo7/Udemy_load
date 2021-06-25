<template>
<div>
  <Hero />
  <Overview />
  <Features />
  <Begin />
</div>
</template>

<script>
import Hero from './Hero.vue'
import Overview from './Overview.vue'
import Features from './Features.vue'
import Begin from './Begin.vue'


export default {
  components: {
    Hero,
    Overview,
    Features,
    Begin,
  }
}
</script>