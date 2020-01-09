$(".validate-form").validate({
  rules:{
    name:{
      required: true,
      maxlength: 25,
      aphabet: true
    },
    email:{
      required: true,
      email: true,
    },
    password:{
      required: true,
      minlength: 5,
    },
    con:{
      required: true,
      minlength: 5,
    },
    code:{
      required: true
    },
    password_confirmation:{
      required: true,
      minlength: 5,
    }
  }
});
jQuery.validator.addMethod("aphabet", function(value, element) {
  return this.optional(element) || /^[a-z]+$/i.test(value);
}, "Should only include alphabets."); 