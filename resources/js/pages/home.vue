<template>
    <div class="home">
	  <card :title="$t('home')">
	    {{ $t('you_are_logged_in') }}
	  </card>
	  <card title="Привет админ" v-if="$can('система')">
	    тест
	  </card>
	</div>
</template>

<script>
export default {
  middleware: 'auth',

  metaInfo () {
    return { title: this.$t('home') }
  }
}
</script>
