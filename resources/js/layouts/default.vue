<template>
  <div class="main-layout">
    <navbar />

    <div class="container mt-4 mh-full-screen">
      <child />
    </div>

    <footer_app/>
  </div>
</template>

<script>
import Navbar from '~/components/Navbar'
import Footer from '~/components/Footer'

export default {
  name: 'MainLayout',

  components: {
    navbar: Navbar, footer_app: Footer
  }
}
</script>
