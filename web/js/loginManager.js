(function() {

    LoginManager = function(applicationPath) {

        return {

            initEvents : function() {
                $("#submitLogin").live('click', this.submitLoginForm);
                $("#logOut").live('click', $.proxy(this.logOut, this));
            },
            
            submitLoginForm : function() {

                $("#rp_loginForm").ajaxForm({
                    target: '#rp_loginForm'
                }).submit();
            },
            
            logOut : function() {

                $.ajax({
                    url: applicationPath + 'logout',
                    success: $.proxy(function() {
                        
                        this.refreshLoginHeader();
                        
                    },this)
                });
            },
            
            refreshLoginHeader : function() {

               $.ajax({
                    url: applicationPath + 'loginHeader',
                    success: function(html) {
                        
                        $("#loginHeader").html(html);
                    }
                });
            }
        }
    };

}())