export const variables = { 
  methods: {
    isInt: function (value) {
      return !isNaN(value) && parseInt(Number(value)) == value && !isNaN(parseInt(value, 10));
    },
    empty(e) {
	    switch (e) {
		    case "":
		    case 0:
		    case "0":
		    case null:
		    case false:
		    case typeof this == "undefined":
		      return true;
		    default:
		      return false;
	    }
	},
	isLength(e){
		if(!this.empty(e)){
			if(Object.keys(e).length){
        return true
			} else return false
		} else return false;
	}
  }
}