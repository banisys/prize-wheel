<script>
import Layout from '../Components/Layout.vue'

export default {
  components: {
    Layout
  }
}
</script>

<template>
  <div class="layout">
    <div class="w-container">
      <div id="s-1" class="slide"></div>
      <div id="s-2" class="slide"></div>
      <div id="s-3" class="slide"></div>
      <div id="s-4" class="slide"></div>
      <div id="s-5" class="slide"></div>
      <div id="s-6" class="slide"></div>
      <div id="s-7" class="slide"></div>
      <div id="s-8" class="slide"></div>
      <div id="s-9" class="slide"></div>
      <div id="s-10" class="slide"></div>
      <div id="s-11" class="slide"></div>
      <div id="s-12" class="slide"></div>
      <div id="s-13" class="slide"></div>
      <div id="s-14" class="slide"></div>
      <div id="s-15" class="slide"></div>
    </div>
  </div>
</template>

<style scoped>
.layout {
  display: flex;
  justify-content: center;
}

.w-container {
  display: flex;
  justify-content: center;
  position: relative;
}

.slide {
  width: 106px;
  height: 250px;
  clip-path: polygon(0 0, 50% 100%, 100% 0);
  clip: auto;
  position: absolute;
}

#s-1 {
  background: red;
}

#s-2 {
  background: green;
  rotate: 24deg;
  transform-origin: bottom
}

#s-3 {
  background: green;
  rotate: 48deg;
  transform-origin: bottom
}

#s-4 {
  background: green;
  rotate: 72deg;
  transform-origin: bottom
}

#s-5 {
  background: red;
  rotate: 96deg;
  transform-origin: bottom
}

#s-6 {
  background: green;
  rotate: 120deg;
  transform-origin: bottom
}

#s-7 {
  background: red;
  rotate: 144deg;
  transform-origin: bottom
}

#s-8 {
  background: green;
  rotate: 168deg;
  transform-origin: bottom
}

#s-9 {
  background: red;
  rotate: 192deg;
  transform-origin: bottom
}

#s-10 {
  background: green;
  rotate: 216deg;
  transform-origin: bottom
}

#s-11 {
  background: red;
  rotate: 240deg;
  transform-origin: bottom
}

#s-12 {
  background: green;
  rotate: 264deg;
  transform-origin: bottom
}

#s-13 {
  background: red;
  rotate: 288deg;
  transform-origin: bottom
}

#s-14 {
  background: green;
  rotate: 312deg;
  transform-origin: bottom
}

#s-15 {
  background: rgb(253, 233, 54);
  rotate: 336deg;
  transform-origin: bottom
}
</style>
