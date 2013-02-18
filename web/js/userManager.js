(function() {

    UserManager = function(applicationPath) {

        return {

            initEvents : function() {
                $(".sendTpPhoneButton").click(this.addRemoteToUser);
            },
            
            addRemoteToUser : function() {
                
                var remoteId = $(this).html("Please Wait").attr('remoteId');
                if(remoteId != null) {
                    
                    $.ajax({
                        url: applicationPath + 'user/addRemote/'+remoteId,
                        success: $.proxy(function(resp) {
                      
                            if(resp == "success") {
                                
                                this.remove();
                                
                            } else {
                                alert("Oups.");
                            }
                            
                        },$(this))
                    });
                } else alert("No id specified.");	
                
                
            }
        }
    };

}())