<template>
  <footer class="footer-app bg-white text-dark font-weight-bold border-top">
    <div class="container">
        <div class="align-items-center row">
          <div class="text-center text-md-left col-md-6">
            <p class="text-sm mb-md-0">2021, {{appName}}.</p>
            <locale-dropdown />
          </div>
          <div class="col-md-6">
            <ul class="list-inline mb-0 mt-2 mt-md-0 text-center text-md-right ml-3">
              <li class="list-inline-item">
                <a href="#"><img src="/img/svg/sn/vk.svg" alt="VK" class="w-1_75rem"></a>
              </li>
              <li class="list-inline-item">
                <a href="#"><img src="/img/svg/sn/instagram.svg" alt="Instagram" class="w-1_75rem"></a>
              </li>
              <li class="list-inline-item">
                <a href="#"><img src="/img/svg/sn/facebook.svg" alt="Facebook" class="w-1_75rem"></a>
              </li>
            </ul>
          </div>
        </div>
    </div>
  </footer>
</template>

<script>
import LocaleDropdown from './LocaleDropdown'

export default {
  components: {
    LocaleDropdown
  },
  data: () => ({
    appName: window.config.appName
  }),

  methods: {
  }
}
</script>

