<template>
<div>
  <Hero />
  <Overview />
  <Problem />
  <Features />
  <Begin />
</div>
</template>

<script>
import Hero from './Hero.vue'
import Overview from './Overview.vue'
import Problem from './Problem.vue'
import Features from './Features.vue'
import Begin from './Begin.vue'


export default {
  components: {
    Hero,
    Overview,
    Problem,
    Features,
    Begin,
  }
}
</script>