<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid">
      <router-link :to="{ name: user ? 'home' : 'welcome' }" class="navbar-brand svg">
        <svg xmlns="http://www.w3.org/2000/svg" baseProfile="full" viewBox="0 0 220.28 103.34">
          <path fill="#0088e7" d="M22.53 45.26a2.37 2.37 0 01-2.37-2.31l-.06-2.22h-.14a12.49 12.49 0 01-10.32 5.13q-4.83 0-7.24-2.9c-1.61-1.93-2.42-5-2.42-9.17V16.25a2.38 2.38 0 012.38-2.37h1.17a2.37 2.37 0 012.37 2.37v16.94c0 2.85.45 4.84 1.36 5.94s2.4 1.66 4.49 1.66a7.32 7.32 0 005.61-3 10.52 10.52 0 002.54-7V16.25a2.37 2.37 0 012.37-2.37h1.29a2.37 2.37 0 012.37 2.37v26.63a2.38 2.38 0 01-2.37 2.38zm17.04-31.38a2.38 2.38 0 012.37 2.31l.06 2.22h.12a13.87 13.87 0 014.64-3.78 12.59 12.59 0 015.68-1.35q10.24 0 10.25 13.87v15.73a2.38 2.38 0 01-2.37 2.38h-1.17a2.38 2.38 0 01-2.37-2.38V27.76c0-3.66-.47-6.17-1.42-7.51s-2.62-2-5-2q-3.08 0-5.61 3.05a10.76 10.76 0 00-2.53 7.09v14.49a2.38 2.38 0 01-2.37 2.38h-1.29a2.38 2.38 0 01-2.37-2.38V16.25a2.37 2.37 0 012.37-2.37zM74.7 4.27V2.38A2.38 2.38 0 0177.07 0h1.65a2.39 2.39 0 012.38 2.38v1.89a2.38 2.38 0 01-2.38 2.37h-1.65a2.37 2.37 0 01-2.37-2.37zm0 38.61V16.25a2.37 2.37 0 012.37-2.37h1.65a2.38 2.38 0 012.38 2.37v26.63a2.39 2.39 0 01-2.38 2.38h-1.65a2.38 2.38 0 01-2.37-2.38zm21.26-29a2.39 2.39 0 012.27 1.69l7.87 26.07h.13l7.87-26.07a2.38 2.38 0 012.27-1.69h1.43a2.37 2.37 0 012.24 3.15l-9.22 26.63a2.38 2.38 0 01-2.24 1.6h-5.07a2.38 2.38 0 01-2.24-1.6l-9.22-26.63a2.37 2.37 0 012.24-3.15zm39.85 18.1a2.39 2.39 0 00-2.23 3.22 8.34 8.34 0 002.38 3.48 9.92 9.92 0 006.91 2.47 18.87 18.87 0 006.73-1.27 2.38 2.38 0 013.13 1.46 2.38 2.38 0 01-1.43 3 26.78 26.78 0 01-9 1.54 14.77 14.77 0 01-11.07-4.44q-4.3-4.43-4.31-11.85 0-7.61 4-12a14 14 0 0110.77-4.34c4.47 0 7.94 1.37 10.44 4.1s3.74 6.7 3.74 11.89v.41a2.35 2.35 0 01-2.37 2.3zm-.14-4.71h11.64a2.36 2.36 0 002.33-2.82 8.34 8.34 0 00-2-4.05 7.78 7.78 0 00-6.06-2.42 8.12 8.12 0 00-6.18 2.45 8.62 8.62 0 00-2.09 4 2.37 2.37 0 002.36 2.84zm49.02-11.47v.29a2.39 2.39 0 01-2.17 2.36 13.84 13.84 0 00-8.33 3.49 12.29 12.29 0 00-3.86 9.44v11.5a2.38 2.38 0 01-2.37 2.38h-1.41a2.38 2.38 0 01-2.37-2.38V16.25a2.37 2.37 0 012.37-2.37h1.15a2.37 2.37 0 012.38 2.32l.07 3.71h.12a17.71 17.71 0 0111.69-6.43 2.39 2.39 0 012.73 2.32zm21.27 22.22h.12l8.36-22.59a2.37 2.37 0 012.22-1.55h1.3a2.37 2.37 0 012.2 3.26l-16.2 39.91a2.39 2.39 0 01-2.2 1.48h-1.18a2.37 2.37 0 01-2.17-3.32l4-9.08a2.4 2.4 0 000-1.77l-10-27.29a2.38 2.38 0 012.23-3.19h1.54a2.38 2.38 0 012.25 1.62zM6.28 73.25a8.35 8.35 0 017.29-3.84 8.48 8.48 0 017 3.1c1.68 2.06 2.51 5.1 2.51 9.12s-.86 7-2.6 9.07a9.19 9.19 0 01-14-.25h-.09v11.09a1.81 1.81 0 01-1.81 1.81h-.91a1.81 1.81 0 01-1.81-1.81V71.67a1.81 1.81 0 011.81-1.81h.73a1.81 1.81 0 011.81 1.76v1.63zm12.36 8.38q0-4.3-1.63-6.45a5.42 5.42 0 00-4.57-2.15 5.29 5.29 0 00-4.39 2.24 9.81 9.81 0 00-1.72 6.13v.45a9.81 9.81 0 001.72 6.16 5.32 5.32 0 004.39 2.21 5.37 5.37 0 004.5-2.21 10.36 10.36 0 001.7-6.38zm12.32 9.95V61.26a1.81 1.81 0 011.8-1.78h1.2a1.81 1.81 0 011.81 1.81v30.29a1.81 1.81 0 01-1.81 1.81h-1.2a1.81 1.81 0 01-1.8-1.81zm30.71 1.81a1.81 1.81 0 01-1.81-1.76V90h-.09a9.36 9.36 0 01-7.74 3.84 6.64 6.64 0 01-5.43-2.17q-1.82-2.18-1.81-6.88V71.67a1.81 1.81 0 011.81-1.81h.81a1.81 1.81 0 011.81 1.81v12.67c0 2.14.34 3.63 1 4.46a4.11 4.11 0 003.37 1.24 5.47 5.47 0 004.21-2.24 7.87 7.87 0 001.9-5.27V71.67a1.81 1.81 0 011.81-1.81h.91a1.81 1.81 0 011.81 1.81v19.91a1.81 1.81 0 01-1.81 1.81zm18.29-20.36q-4.53 0-4.53 2.94a2.56 2.56 0 001.07 2.19 8.66 8.66 0 003.69 1.2c3.16.49 5.43 1.34 6.78 2.56a6.54 6.54 0 012 5.14 5.88 5.88 0 01-2.38 5 10.76 10.76 0 01-6.67 1.81 18.58 18.58 0 01-7.67-1.56 1.83 1.83 0 01-1-2.11 1.81 1.81 0 012.51-1.19 15.49 15.49 0 006.19 1.24c3 0 4.43-1 4.43-3.16a2.93 2.93 0 00-1-2.38 7.87 7.87 0 00-3.65-1.24q-4.65-.78-6.74-2.56A6.12 6.12 0 0170.91 76a5.68 5.68 0 012.35-4.77c1.57-1.2 3.8-1.79 6.7-1.79a18.48 18.48 0 016.65 1.16 1.82 1.82 0 011.12 2.14 1.84 1.84 0 01-2.45 1.24 15 15 0 00-5.32-.95z"/> 
        </svg>
      </router-link>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false">
        <span class="navbar-toggler-icon" />
      </button>

      <div id="navbarToggler" class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <li class="nav-item">
            <router-link :to="{ path: '/info' }" class="nav-link d-flex align-items-center">
                {{ $t('header_info') }}
            </router-link>
          </li>
          <li class="nav-item">
            <router-link :to="{ path: '/help' }" class="nav-link d-flex align-items-center">
                {{ $t('help') }}
            </router-link>
          </li>
          <li class="nav-item" v-if="$can('система')">
            <router-link :to="{ name: 'admin.home' }" class="nav-link d-flex align-items-center header-menu__admin">
                <fa icon="cog" fixed-width class="icon-cog mr-2"/>
                {{ $t('header_admin_link') }}
            </router-link>
          </li>
        </ul>

        <ul class="navbar-nav ml-auto">
          <!-- Authenticated -->
          <li v-if="user" class="nav-item dropdown">
            <a class="nav-link dropdown-toggle user-dropdown text-dark"
               href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            >
              <img :src="user.photo_url" class="rounded-circle profile-photo mr-1">
              {{ user.name }}
            </a>
            <div class="dropdown-menu">
              <router-link :to="{ name: 'settings.profile' }" class="dropdown-item pl-3">
                <fa icon="cog" fixed-width />
                {{ $t('settings') }}
              </router-link>

              <div class="dropdown-divider" />
              <a href="#" class="dropdown-item pl-3" @click.prevent="logout">
                <fa icon="sign-out-alt" fixed-width />
                {{ $t('logout') }}
              </a>
            </div>
          </li>
          <!-- Guest -->
          <template v-else>
            <li class="nav-item">
              <router-link :to="{ name: 'login' }" class="nav-link" active-class="active">
                {{ $t('login') }}
              </router-link>
            </li>
            <li class="nav-item">
              <router-link :to="{ name: 'register' }" class="nav-link" active-class="active">
                {{ $t('register') }}
              </router-link>
            </li>
          </template>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from 'vuex'

export default {

  name: 'navbarfluid',
  
  components: {

  },

  data: () => ({
    appName: window.config.appName
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      // Redirect to login.
      this.$router.push({ name: 'login' })
    }
  }
}
</script>

<style scoped>
.profile-photo {
  width: 2rem;
  height: 2rem;
  margin: -.375rem 0;
}
</style>
