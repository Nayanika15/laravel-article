 $("#category-form").validate({
  rules:{
    name:{
    required: true,
    maxlength: 25,
    },
  },
  messages: {
  	name: {
		required: "Please enter your category name.",
		maxlength: "Length cannot exceed 25 character."
			}
		}
 	
});

  $("#login-form").validate({
  rules: {
    email: {
    required: true,
    email: true,
    },
    password: "required"
	
  },
  messages: {
  	email: {
		required: "Enter your email-address."
			},
	password: {
		required: "Enter your password."
	},
	email: "Enter a valid email address."
		}
});