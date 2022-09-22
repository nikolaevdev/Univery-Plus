<template>
  <div class="main-layout">
    <navbar />

    <div class="mh-full-screen">
      <child />
    </div>

    <footer_app/>
  </div>
</template>

<script>
import NavbarFluid from '~/components/NavbarFluid'
import FooterFluid from '~/components/FooterFluid'

export default {
  name: 'MainLayout',

  components: {
    navbar: NavbarFluid, footer_app: FooterFluid
  }
}
</script>
