(function() {

    RemoteManager = function(applicationPath) {

        var screenX;
        var screenY;

        var buttonPrototype = {
            x : null,
            y : null,
            height : null,
            width : null,
            imageId : null,
            //imagePath : null,
            shortcut : null
        };

        var phone = {};
        phone.element = $("#phone");
			
        phone.width = phone.element.width();
        phone.height = phone.element.height();		

        var editedButton;

        return {

            initEvents : function() {

                $(".button").live('click', $.proxy(this.selectButton, this));
                $("#addButton").click($.proxy(this.addButton, this));
                $("#removeButton").click($.proxy(this.removeButton, this));
                $("#saveRemote").click($.proxy(this.saveRemote, this));
                $("#editRemote").click($.proxy(this.editRemote, this));
                $("#buttonShortcutInput").keydown($.proxy(this.setShortcut, this));			
                $("#loadXml").click($.proxy(this.getXml, this));
                phone.element.click(this.unselectAllButtons);
                $("#form_file").change($.proxy(this.imageUpload, this));
                $("#buttonImageInput").click(function(){
                    $("#form_file").click();
                });
            },
            
            initEditMode :function() {
                
                $('.button').each(function(){
                    
                    var content = $(this).html();
                    $(this).html("");
                    
                    $(this).draggable({
                        //disabled: true,
                        containment : "#phone",
                        scroll : false,
                        padding : 0,
                        stop: function(event){
                            $(event.target).click();
                        }
                    }).resizable({
                        //disabled: true,
                        containment : "#phone"
                    }
                    ).append(content)
                    
                });
                
                
            },
            
            imageUpload : function() {

                $("#buttonImageInput").val($("#form_file").val());

                $("#preview").html('loading');
                $("#imageform").ajaxForm({
                    //target: '#preview',
                    success: this.imageUploaded
                }).submit();
            },
				
            imageUploaded : function(responseText, statusText, xhr, form) {

                if(editedButton != undefined) {
						
                    var jsonResp = jQuery.parseJSON(responseText);
                    
                    console.log(jsonResp.resp);
                    
                    if(jsonResp != null) {
                        
                        if(jsonResp.err != null) {
                            
                            alert("Invalid Image. Please submit a png, jpeg or jpg image.")
                            
                            console.error(jsonResp.err);
                        } else {                            
                            if(jsonResp.resp.path != undefined && jsonResp.resp.imageId != undefined) {
                              
                                editedButton.find('img.buttonImage').attr('src', jsonResp.resp.path);
                                $(".buttonImageId", editedButton).html(jsonResp.resp.imageId);
                                $(".buttonImagePath", editedButton).html($("#buttonImageInput").val());
                                
                                $("#preview").html('');
                            }
                        }                        
                    } else {
                        console.error('no response specified.');
                    }
                } else {
                    console.error("no editedButton defined.");
                }

            },
            
            setShortcut : function(event) {

                e = event.originalEvent;
                var k = KeyCode;
                var shortcut = k.hot_key(k.translate_event(e));
                $(event.currentTarget).val( shortcut );
                $(".buttonShortcut", editedButton).html( shortcut );
                
                return false;
            },
				
            saveRemote : function() {
              
                var remoteObject = this.buildRemote();
                this.createRemote(remoteObject.name, remoteObject.description, remoteObject.remote);
              
            },
            
            editRemote : function() {
                
                var remoteId = $("#remoteId").val();
              
                var remoteObject = this.buildRemote();
                this.updateRemote(remoteObject.name, remoteObject.description, remoteObject.remote, remoteId);
              
            },
            
            buildRemote : function() {
              
                if($("#loginStatus").html() != "true") {
                    alert('Please log in or register to save your remote.');
                    return;
                }

                var name = $("#remoteTitle").val();
                var description = $("#remoteDescription").val();
                var remote = [];
                
                $(".button", phone.element).each($.proxy(function(i, target){

                    var targetButton = $(target);
                    var buttonPosition = targetButton.position();
                    
                    var button = jQuery.extend({}, buttonPrototype);
                    
                    button.x = this.getXPercent(buttonPosition.left);
                    button.y = this.getYPercent(buttonPosition.top);
                    button.width = this.getXPercent(targetButton.width());
                    button.height = this.getYPercent(targetButton.height());
                    button.imageId = $(".buttonImageId", targetButton).html();
                    button.shortcut = $(".buttonShortcut", targetButton).html();
                                        
                    remote.push(button);
						
                },this));
                
                var remoteObject = {
                    "name" : name, 
                    "description" : description,
                    "remote" : remote
                };
                
                return remoteObject;
              
            },
            
            createRemote : function(name, description, remote) {

                var postData = new Object();
                postData.name = name;
                postData.description = description;
                postData.remote = JSON.stringify(remote, null, 2);
					
                $.ajax({
                    url: applicationPath + 'remote/save',
                    type: "POST",
                    data: postData,
                    success: function(resp) {
                       console.log(resp);
                    },
                    error: function(resp) {
                       console.error(resp);
                    }
                });

            },
            
            updateRemote : function(name, description, remote, remoteId) {

                var postData = new Object();
                postData.name = name;
                postData.description = description;
                postData.remote = JSON.stringify(remote, null, 2);
					
                $.ajax({
                    url: applicationPath + 'remote/' + remoteId + '/update',
                    type: "POST",
                    data: postData,
                    success: function(resp) {
                       console.log(resp);
                    },
                    error: function(resp) {
                       console.error(resp);
                    }
                });

            },
            
            addButton : function() {

                this.selectButton(
                    $('<div/>', {
                        class : 'button'
                    }).appendTo(phone.element).draggable({
                        //disabled: true,
                        containment : "#phone",
                        scroll : false,
                        padding : 0,
                        stop: function(event){
                            $(event.target).click();
                        }
                    }).resizable({
                        //disabled: true,
                        containment : "#phone"
                    }
                    ).append('<div class="buttonShortcut"></div>                                \
                            <img class="buttonImage" src="">                                                                    \
                            <div class="buttonImagePath" style="display: none;"></div>          \
                            <div class="buttonImageId" style="display: none;"></div>')
                    );

            },
            
            unselectAllButtons : function() {
					
                target = null;		
                editedButton = null;
					
                //$(".editedButton", phone.element).draggable( "disable" ).resizable( "disable" ).removeClass("editedButton");
                $(".editedButton", phone.element).removeClass("editedButton");
                $("#showEditButtonIntf input").val("");
                
                $("#showEditButtonIntf").hide();
            },
            
            removeButton : function() {
                
                
                if(editedButton != undefined) {
						
                    editedButton.remove();
                    
                } else {
                    console.error("no editedButton defined.");
                }
                
                this.unselectAllButtons();
            },
            
            selectButton : function(target) {
                
                this.unselectAllButtons();
               
                $("#showEditButtonIntf").show();
               
                target = target.currentTarget || target;
					
                editedButton = $(target);
                //editedButton.addClass("editedButton").draggable( "enable" ).resizable( "enable" );
                editedButton.addClass("editedButton");
                
                var shortcut = editedButton.find(".buttonShortcut").html();
                var Imagepath = editedButton.find(".buttonImagePath").html();
                
                $("#buttonShortcutInput").val(shortcut);
                $("#buttonImageInput").val(Imagepath);
            },


            getXPx : function(x) {
					
                return Math.round(((x * phone.width ) / 100)*100)/100;
            },
				
            getYPx : function(y) {

                return Math.round(((y * phone.height ) / 100)*100)/100;
            },
				
            getXPercent : function(x) {
					
                return Math.round(((x * 100 ) / phone.width)*100)/100;
            },
				
            getYPercent : function(y) {

                return Math.round(((y * 100 ) / phone.height)*100)/100;
            },
				
            getPhone : function () {
                return phone;
            }
        }
    };

}())