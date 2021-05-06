<template>
  <nav id="sidebar">
    <div class="sidebar-header">
      <a href="/">
        <h3>{{ settings['site.title'] }}</h3>
      </a>
    </div>

    <ul class="list-unstyled components">
      <li>
        <a
          href="/admins"
          :class="{'active': ['/admins'].includes(pathname)}"
        >
          {{ $t("admin.admin") }}
        </a>
      </li>

      <li>
        <a
          href="/clients"
          :class="{'active': ['/clients'].includes(pathname)}"
        >
          {{ $t("admin.client") }}
        </a>
      </li>

      <li>
        <a
          href="/pages"
          :class="{'active': ['/pages'].includes(pathname)}"
        >
          {{ $t("admin.pages") }}
        </a>
      </li>

      <li>
        <a
          href="/notes"
          :class="{'active': ['/notes'].includes(pathname)}"
        >
          {{ $t("admin.notes") }}
        </a>
      </li>

      <li>
        <a
          href="/translations"
          :class="{'active': ['/translations'].includes(pathname)}"
        >
          {{ $t("admin.translations") }}
        </a>
      </li>

      <li>
        <a
          href="/settings"
          :class="{'active': ['/settings'].includes(pathname)}"
        >
          {{ $t("admin.settings") }}
        </a>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  beforeCreate() {
    if (navigator.onLine) {
      // if (!window.sessionStorage.getItem("settingsUpdated"))
      this.$store.dispatch("fetchSettings");
    }
  }
}
</script>

<style>
</style>