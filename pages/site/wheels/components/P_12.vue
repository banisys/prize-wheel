<template>
  <div class="w-container" style="position: relative;">
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
    // numDeg: 0,
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
        3: [95, 100, 105, 110],
        4: [125, 130, 135, 140],
        5: [155, 160, 165, 170],
        6: [185, 190, 195, 200],
        7: [215, 220, 225, 230],
        8: [245, 250, 255, 260],
        9: [275, 280, 285, 290],
        10: [305, 310, 315, 320],
        11: [340, 345, 350, 353]
      }

      const stack = []

      this.reverseSlides.forEach((item, index) => {
        for (let i = 0; i < item.probability; i++) {
          stack.push(standDeg[index][Math.floor(Math.random() * 4)])
        }
      })

      const random = stack[Math.floor(Math.random() * stack.length)]

      let win =
        0 < random && random < 30 ? this.reverseSlides[11] :
          30 < random && random < 60 ? this.reverseSlides[10] :
            60 < random && random < 90 ? this.reverseSlides[9] :
              90 < random && random < 120 ? this.reverseSlides[8] :
                120 < random && random < 150 ? this.reverseSlides[7] :
                  150 < random && random < 180 ? this.reverseSlides[6] :
                    180 < random && random < 210 ? this.reverseSlides[5] :
                      210 < random && random < 240 ? this.reverseSlides[4] :
                        240 < random && random < 270 ? this.reverseSlides[3] :
                          270 < random && random < 300 ? this.reverseSlides[2] :
                            300 < random && random < 330 ? this.reverseSlides[1] : this.reverseSlides[0]

      this.numDeg = this.numDeg + random
      document.querySelector('.w-container').style.transform = `rotate(${this.numDeg}deg)`

      this.$emit('win', win)
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
  /* background: red; */
}

#s-2 {
  /* background: green; */
  rotate: 30deg;
  transform-origin: bottom
}

#s-3 {
  /* background: rgb(0, 234, 255); */
  rotate: 60deg;
  transform-origin: bottom
}

#s-4 {
  /* background: green; */
  rotate: 90deg;
  transform-origin: bottom
}

#s-5 {
  /* background: red; */
  rotate: 120deg;
  transform-origin: bottom
}

#s-6 {
  /* background: green; */
  rotate: 150deg;
  transform-origin: bottom
}

#s-7 {
  /* background: red; */
  rotate: 180deg;
  transform-origin: bottom
}

#s-8 {
  /* background: green; */
  rotate: 210deg;
  transform-origin: bottom
}

#s-9 {
  /* background: red; */
  rotate: 240deg;
  transform-origin: bottom
}

#s-10 {
  /* background: green; */
  rotate: 270deg;
  transform-origin: bottom
}

#s-11 {
  /* background: green; */
  rotate: 300deg;
  transform-origin: bottom
}

#s-12 {
  /* background: green; */
  rotate: 330deg;
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
