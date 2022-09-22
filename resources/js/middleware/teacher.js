import store from '~/store'

export default (to, from, next) => {

	if (store.getters['auth/user'].user_role != null && typeof store.getters['auth/user'].user_role !== "undefined") {
        if (store.getters['auth/user'].user_role.indexOf('преподователь') == -1) {
            next({ name: 'home' })
	    } else {
	        next()
	    }
	} else next({ name: 'home' })

	
}