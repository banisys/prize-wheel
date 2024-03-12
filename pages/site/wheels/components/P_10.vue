<template>
  <div class="w-container">
    <template v-for="(item, index) in slices">
      <div :id="`s-${index + 1}`" class="slide">
        <p> {{ item.title }}</p>
      </div>
    </template>
    <!-- <img src="/360.png" alt="" style="width: 100%;position: absolute;top: 0"> -->
  </div>
</template>


<script>
import Layout from './Layout.vue'

export default {
  components: {
    Layout
  },
  props: ['slices'],
  data: () => ({
    numDeg: 1800,
  }),
  computed: {
    reverseSlides() {
      return this.slices.reverse()
    }
  },
  methods: {
    start() {
      //   document.querySelector('.w-container').style.transition = '10s cubic-bezier(.08, .46, .4, 1.01)'

      const standDeg = {
        0: [5, 6, 7, 8],
        1: [50, 51, 53, 54],
        2: [85, 86, 87, 88],
        3: [130, 131, 132, 133],
        4: [163, 164, 165, 166],
        5: [200, 201, 202, 203],
        6: [230, 231, 232, 233],
        7: [270, 271, 272, 273],
        8: [310, 311, 312, 313],
        9: [350, 351, 352, 353]
      }

      const stack = []

      this.reverseSlides.forEach((item, index) => {

        if (item.probability !== 0) {
          for (let i = 0; i < item.probability; i++) {
            stack.push(standDeg[index][Math.floor(Math.random() * 4)])
          }
        }

      })

      const random = stack[Math.floor(Math.random() * stack.length)]

      let winSlice =
        0 < random && random < 36 ? this.reverseSlides[9] :
          36 < random && random < 72 ? this.reverseSlides[8] :
            72 < random && random < 108 ? this.reverseSlides[7] :
              108 < random && random < 144 ? this.reverseSlides[6] :
                144 < random && random < 180 ? this.reverseSlides[5] :
                  180 < random && random < 216 ? this.reverseSlides[4] :
                    216 < random && random < 252 ? this.reverseSlides[3] :
                      252 < random && random < 288 ? this.reverseSlides[2] :
                        288 < random && random < 324 ? this.reverseSlides[1] : this.reverseSlides[0]

      this.numDeg = this.numDeg + random
      document.querySelector('.w-container').style.transform = `rotate(${this.numDeg}deg)`

      this.$emit('winSlice', winSlice)
    }
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
  position: relative;
  width: 500px;
  height: 500px;
  display: flex;
  justify-content: center;
  position: relative;
  transition: 10s cubic-bezier(.08, .46, .4, 1.01);
  rotate: 18deg;
}

.slide {
  width: 163px;
  height: 250px;
  clip-path: polygon(0 0, 50% 100%, 100% 0);
  clip: auto;
  position: absolute;
  opacity: 1;
  text-align: center;
}

#s-1 {
  /* background: red; */
}

#s-2 {
  /* background: green; */
  rotate: 36deg;
  transform-origin: bottom
}

#s-3 {
  /* background: rgb(0, 234, 255); */
  rotate: 72deg;
  transform-origin: bottom
}

#s-4 {
  /* background: green; */
  rotate: 108deg;
  transform-origin: bottom
}

#s-5 {
  /* background: red; */
  rotate: 144deg;
  transform-origin: bottom
}

#s-6 {
  /* background: green; */
  rotate: 180deg;
  transform-origin: bottom
}

#s-7 {
  /* background: red; */
  rotate: 216deg;
  transform-origin: bottom
}

#s-8 {
  /* background: green; */
  rotate: 252deg;
  transform-origin: bottom
}

#s-9 {
  /* background: red; */
  rotate: 288deg;
  transform-origin: bottom
}

#s-10 {
  /* background: green; */
  rotate: 324deg;
  transform-origin: bottom
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
