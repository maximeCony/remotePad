(function() {

    RegisterManager = function(applicationPath) {

        return {

            initEvents : function() {
                $("#submitRegister").live('click', this.submitRegisterForm);
            },
            
            submitRegisterForm : function() {

                $("#rp_registerForm").ajaxForm({
                    target: '#rp_registerFormContainer'
                }).submit();
            }
        }
    };

}())