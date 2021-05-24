<template>
  <ul
    class="pagination"
    v-if="shouldPaginate"
  >
    <li
      v-show="prevUrl"
      style="margin-right:3px;"
    >
      <a
        href="#"
        aria-label="Previous"
        rel="prev"
        @click.prevent="page=1"
        class="btn btn-info btn-sm"
      >
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <li
      v-show="prevUrl"
      style="margin-right:3px;"
    >
      <a
        href="#"
        aria-label="Previous"
        rel="prev"
        @click.prevent="page--"
        class="btn btn-info btn-sm"
      >
        ‹
      </a>
    </li>
    <li
      v-for="n in pageRange"
      :key="n"
      style="margin-right:3px;"
    >
      <span
        class="btn btn-default btn-sm"
        v-if="n=='...'"
      >...</span>
      <button
        aria-label="n"
        rel="n"
        @click="page=n"
        class="btn btn-info btn-sm"
        v-else
      >
        <span aria-hidden="true">{{n}}</span>
      </button>
    </li>
    <li v-show="nextUrl">
      <a
        href="#"
        aria-label="Next"
        rel="next"
        @click.prevent="page++"
        class="btn btn-info btn-sm"
      >
        ›
      </a>
    </li>
    <li v-show="nextUrl">
      <a
        href="#"
        aria-label="Last"
        rel="next"
        @click.prevent="page=dataSet.last_page"
        class="btn btn-info btn-sm"
      >
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</template>

<script>
export default {
  props: ['dataSet'],
  data() {
    return {
      page: location.search.match(/page=(\d+)/) ? parseInt(location.search.match(/page=(\d+)/)[1]) : 1,
      prevUrl: false,
      nextUrl: false,
      pageRange: [],
    }
  },
  watch: {
    dataSet() {
      this.page = this.dataSet.current_page;
      this.prevUrl = this.dataSet.prev_page_url;
      this.nextUrl = this.dataSet.next_page_url;
      this.pageRange = this.pagination(this.dataSet.current_page, this.dataSet.last_page);
    },
    page() {
      this.broadcast().updateUrl();
    }
  },
  computed: {
    shouldPaginate() {
      return !!this.prevUrl || !!this.nextUrl;
    }
  },
  methods: {
    broadcast() {
      return this.$emit('page_changed', this.page);
    },
    updateUrl() {
      history.pushState(null, null, '?page=' + this.page);
    },
    pagination(c, m) {
      var current = c,
        last = m,
        delta = 2,
        left = current - delta,
        right = current + delta + 1,
        range = [],
        rangeWithDots = [],
        l;
      for (let i = 1; i <= last; i++) {
        if (i == 1 || i == last || i >= left && i < right) {
          range.push(i);
        }
      }
      for (let i of range) {
        if (l) {
          if (i - l === 2) {
            rangeWithDots.push(l + 1);
          } else if (i - l !== 1) {
            rangeWithDots.push('...');
          }
        }
        rangeWithDots.push(i);
        l = i;
      }
      return rangeWithDots;
    }
  }
}
</script>
<style scoped>
.child-right {
  margin-left: auto;
}
</style>