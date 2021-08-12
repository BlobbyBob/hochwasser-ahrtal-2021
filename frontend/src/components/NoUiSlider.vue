<template>
  <div>
    <div :id="sliderId" class="vue-nouislider">
    </div>
  </div>
</template>

<script lang="js">
/*
  Copyright 2021 Sitronik

  Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
  documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
  rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit
  persons to whom the Software is furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
  Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
  WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
  OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
import { defineComponent } from 'vue'
import { create } from 'nouislider'

export default defineComponent({
  name: 'NoUiSlider',
  props: {
    config: {
      type: Object,
      required: true
    },
    id: {
      type: String,
      default () {
        return Math.random().toString(36).substr(2, 4)
      }
    }
  },
  emits: ['update'],
  data () {
    return {
      init: false,
      values: this.config.start
    }
  },
  computed: {
    sliderId () {
      if (this.id === undefined) {
        return this.uniqueId()
      }

      return this.id
    },
    slider () {
      return document.getElementById(this.id)
    }
  },
  mounted () {
    create(this.slider, this.config)
    this.slider.noUiSlider.on('update', this.updateValue)
  },
  methods: {
    updateValue (value, handle) {
      this.values[handle] = value[handle]
      this.$emit('update', this.values)
    },
    uniqueId () {
      function s4 () {
        return Math.floor((1 + Math.random()) * 0x10000)
          .toString(16)
          .substring(1)
      }

      return `vue-nouislider-${s4()}${s4()}`
    }
  }
})
</script>

<style scoped lang="scss">
@import '~nouislider/dist/nouislider.min.css';
</style>
