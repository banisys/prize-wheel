<script>
import Layout from './Layout.vue'

export default {
  components: {
    Layout
  },
  data: () => ({
    numDeg: 1800,
    slides: [
      {
        id: 1,
        title: 1,
        priority: 40
      },
      {
        id: 2,
        title: 2,
        priority: 40
      },
      {
        id: 3,
        title: 3,
        priority: 20
      },
      {
        id: 4,
        title: 4,
        priority: 0
      },
      {
        id: 5,
        title: 5,
        priority: 0
      },
      {
        id: 6,
        title: 6,
        priority: 0
      },
      {
        id: 7,
        title: 7,
        priority: 0
      },
      {
        id: 8,
        title: 8,
        priority: 0
      },
      {
        id: 9,
        title: 9,
        priority: 0
      },
      {
        id: 10,
        title: 10,
        priority: 0
      },
    ]
  }),
  computed: {
    reverseSlides() {
      return this.slides.reverse()
    }
  },
  methods: {
    start() {
      document.querySelector('.w-container').style.transition = '10s cubic-bezier(.08, .46, .4, 1.01)'

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
        for (let i = 0; i < item.priority; i++) {
          stack.push(standDeg[index][Math.floor(Math.random() * 4)])
        }
      })

      const random = stack[Math.floor(Math.random() * stack.length)]

      let win =
        0 < random && random < 36 ? this.reverseSlides[0] :
          36 < random && random < 72 ? this.reverseSlides[1] :
            72 < random && random < 108 ? this.reverseSlides[2] :
              108 < random && random < 144 ? this.reverseSlides[3] :
                144 < random && random < 180 ? this.reverseSlides[4] :
                  180 < random && random < 216 ? this.reverseSlides[5] :
                    216 < random && random < 252 ? this.reverseSlides[6] :
                      252 < random && random < 288 ? this.reverseSlides[7] :
                        288 < random && random < 324 ? this.reverseSlides[8] : this.reverseSlides[9]

      this.numDeg = this.numDeg + random
      document.querySelector('.w-container').style.transform = `rotate(${this.numDeg}deg)`

      console.log(win)
    },
    reset() {
      document.querySelector('.w-container').style.transition = 'none'
      document.querySelector('.w-container').style.transform = 'none'
    }
  },
  mounted() {

  }
}

</script>

<template>
  <div class="layout mt-20">
    <div class="w-container">
      <div id="s-1" class="slide">1</div>
      <div id="s-2" class="slide">2</div>
      <div id="s-3" class="slide">3</div>
      <div id="s-4" class="slide">4</div>
      <div id="s-5" class="slide">5</div>
      <div id="s-6" class="slide">6</div>
      <div id="s-7" class="slide">7</div>
      <div id="s-8" class="slide">8</div>
      <div id="s-9" class="slide">9</div>
      <div id="s-10" class="slide">10</div>
    </div>
  </div>
  <button @click="start">click</button>
  <button @click="reset">reset</button>
</template>

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
  width: 163px;
  height: 250px;
  clip-path: polygon(0 0, 50% 100%, 100% 0);
  clip: auto;
  position: absolute;
  opacity: 1;
  text-align: center;
}

#s-1 {
  background: red;
}

#s-2 {
  background: green;
  rotate: 36deg;
  transform-origin: bottom
}

#s-3 {
  background: rgb(0, 234, 255);
  rotate: 72deg;
  transform-origin: bottom
}

#s-4 {
  background: green;
  rotate: 108deg;
  transform-origin: bottom
}

#s-5 {
  background: red;
  rotate: 144deg;
  transform-origin: bottom
}

#s-6 {
  background: green;
  rotate: 180deg;
  transform-origin: bottom
}

#s-7 {
  background: red;
  rotate: 216deg;
  transform-origin: bottom
}

#s-8 {
  background: green;
  rotate: 252deg;
  transform-origin: bottom
}

#s-9 {
  background: red;
  rotate: 288deg;
  transform-origin: bottom
}

#s-10 {
  background: green;
  rotate: 324deg;
  transform-origin: bottom
}
</style>
