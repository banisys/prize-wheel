<template>
  <div class="w-container">

    <template v-for="(item, index) in slices">
      <div :id="`s-${index + 1}`" class="slide">
        <p> {{ item.title }}</p>
      </div>
    </template>

  </div>
</template>w-container

<script>
import Layout from './Layout.vue'

export default {
  components: {
    Layout
  },
  props: ['slices'],
  data: () => ({
    numDeg: 1800,
    slides: [
      {
        id: 1,
        title: 1,
        probability: 40
      },
      {
        id: 2,
        title: 2,
        probability: 40
      },
      {
        id: 3,
        title: 3,
        probability: 20
      },
      {
        id: 4,
        title: 4,
        probability: 0
      },
      {
        id: 5,
        title: 5,
        probability: 0
      },
      {
        id: 6,
        title: 6,
        probability: 0
      },
      {
        id: 7,
        title: 7,
        probability: 0
      },
      {
        id: 8,
        title: 8,
        probability: 0
      },
      {
        id: 9,
        title: 9,
        probability: 0
      },
      {
        id: 10,
        title: 10,
        probability: 0
      },
      {
        id: 11,
        title: 11,
        probability: 0
      },
      {
        id: 12,
        title: 12,
        probability: 0
      },
    ]
  }),
  computed: {
    reverseSlides() {
      return this.slides.reverse()
    }
  },
  methods: {

  },
  mounted() {

    // console.log(this.slices);

  }
}

</script>

<style scoped>
.layout {
  display: flex;
  justify-content: center;
}

.w-container {
  width: 500px;
  height: 500px;
  display: flex;
  justify-content: center;
  position: relative;
  transition: 10s cubic-bezier(.08, .46, .4, 1.01);
  rotate: 18deg;
}

.slide {
  width: 134px;
  height: 250px;
  clip-path: polygon(0 0, 50% 100%, 100% 0);
  clip: auto;
  position: absolute;
  opacity: 1;
  text-align: center;
}

#s-1 {
  /* background: rgb(225, 225, 225); */
}

#s-2 {
  /* background: rgb(186, 186, 186); */
  rotate: 30deg;
  transform-origin: bottom
}

#s-3 {
  /* background: rgb(225, 225, 225); */
  rotate: 60deg;
  transform-origin: bottom
}

#s-4 {
  /* background: rgb(186, 186, 186); */
  rotate: 90deg;
  transform-origin: bottom
}

#s-5 {
  /* background: rgb(225, 225, 225); */
  rotate: 120deg;
  transform-origin: bottom
}

#s-6 {
  /* background: rgb(186, 186, 186); */
  rotate: 150deg;
  transform-origin: bottom
}

#s-7 {
  /* background: rgb(225, 225, 225); */
  rotate: 180deg;
  transform-origin: bottom
}

#s-8 {
  /* background: rgb(186, 186, 186); */
  rotate: 210deg;
  transform-origin: bottom
}

#s-9 {
  /* background: rgb(225, 225, 225); */
  rotate: 240deg;
  transform-origin: bottom
}

#s-10 {
  /* background: rgb(186, 186, 186); */
  rotate: 270deg;
  transform-origin: bottom;

}

#s-11 {
  /* background: rgb(186, 186, 186); */
  rotate: 300deg;
  transform-origin: bottom;

}

#s-12 {
  /* background: rgb(186, 186, 186); */
  rotate: 330deg;
  transform-origin: bottom;

}

.w-container div {
  border: 1px solid rgb(227, 227, 227);
}

.slide p {
  transform: rotate(-90deg);
  margin-top: 60%;
  margin-bottom: 0;
}
</style>
