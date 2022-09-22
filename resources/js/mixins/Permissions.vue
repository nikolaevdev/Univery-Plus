<script>
	import store from '~/store'

	export default {
	    methods: {
	      $can(permissionName) {
	      	if (store.getters['auth/permissions']) {
	      		try {
                    return store.getters['auth/permissions'].indexOf(permissionName) !== -1;
			    } catch (e) { }
			} else return false;
	      }
	    },
	};
</script>