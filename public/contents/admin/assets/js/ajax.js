$(function() {
    // create request
	$(document).on('submit', '#add-form', function (event) {
		event.preventDefault();

        var formdata = new FormData($(this)[0]);
        var btnText = $('.btn-submit').text();
        var btn = $('.btn-submit');
        console.log(this.action);

        $.ajax({
            url: this.action,
            type: this.method,
            data: formdata,
            dataType: "JSON",
            contentType: false,
            processData: false,
            cache: false,
            beforeSend:function() {
                console.log("sending....");
                console.log(this.action);
                // $('.btn-submit').attr("disabled", true);
                btn.attr("disabled", true).html("<span class='spinner-border spinner-border-sm'></span> Loading...");
            },
            success(data) {
                if(data.success) {
                    $("#add-form")[0].reset();
                    $('.invalid-feedback').remove();
                    return successAlert(data.success);
                }else{
                    return errorAlert(data.error);
                }
            },
            error: function(data){
                var errors = data.responseJSON.errors;
                var errorField = Object.keys(errors)[0];
                var inputField = $('input[name="'+ errorField +'"], select[name="'+ errorField +'"], textarea[name="'+ errorField +'"]');
                var errorMessage = errors[errorField][0];
            

                // Show error message
                if(inputField.next().length == 0){
                    inputField.focus().after('<div class="invalid-feedback"> <strong>'+ errorMessage +'</strong> </div>');
                }else{
                    inputField.focus();
                }

                // Remove error message
                inputField.on('keydown, change', function() {
                    inputField.next().remove();
                });

                return errorAlert(errorMessage);

            },
            complete:function() {
                btn.attr("disabled", false).html(btnText);
            }
        });
    });

    // update request
    $(document).on('submit', '#update-form', function (event) {
        event.preventDefault();

        var formdata = new FormData($(this)[0]);
        var btnText = $('.btn-submit').text();
        var btn = $('.btn-submit');
        var btn2 = $('.btn-submit-extra');
        
        console.log(this.action);

        $.ajax({
            url: this.action,
            type: this.method,
            data: formdata,
            dataType: "JSON",
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function() {
                console.log("sending....");
                btn2.attr("disabled", true).html("<span class='spinner-border spinner-border-sm'></span> Loading...");
                btn.attr("disabled", true).html("<span class='spinner-border spinner-border-sm'></span> Loading...");
                // $('.btn-submit').attr("disabled", true);
            },
            error: function(data){
                var errors = data.responseJSON.errors;
                var errorField = Object.keys(errors)[0];
                var inputField = $('input[name="'+ errorField +'"], select[name="'+ errorField +'"], textarea[name="'+ errorField +'"]');
                var errorMessage = errors[errorField][0];

                // Show error message
                if(inputField.next().length == 0){
                    inputField.focus().after('<div class="invalid-feedback"> <strong>'+ errorMessage +'</strong> </div>');
                }else{
                    inputField.focus();
                }

                // Remove error message
                inputField.on('keydown', function() {
                    inputField.next().remove();
                });

                return errorAlert(errorMessage);
            },
            success(data) {
                console.log("success....");
                if(data.success) {
                    $('.invalid-feedback').remove();
                    return successAlert(data.success);
                }else{
                    return errorAlert(data.error);
                }
            },
            complete: function() {
                console.log("complete....");
                btn.attr("disabled", false).html(btnText);
                btn2.attr("disabled", false).html("Update");
            }
        });
    
    });

    
    if(performance.navigation.type == 1) {
        $(".btn-delete, .btn-restore").attr('disabled', true);
        // console.info( "This page is reloaded" );
    } 

    // check all checkbox
    $(document).on('click', '.check-all', function(){
        $('.delete-checkbox').not(this).prop('checked', this.checked);
    });

    // input checked or not
    $(document).on('click','.delete-checkbox, .check-all', function(){
        if($(this).is(":checked")){
            $(".btn-delete, .btn-restore").attr('disabled', false);
        }

        // Uncheck checkbox
        if($('.delete-checkbox:checked').length < $('.delete-checkbox').length){
            $('.check-all').prop("checked", false);
        }else{
            $('.check-all').prop("checked", true);
        }

        // Button disable if check length is 0
        if($('.delete-checkbox:checked').length == 0){
            $(".btn-delete, .btn-restore").attr('disabled', true);
        }
    });


    // $(".btn-delete, .btn-restore .delete-category").attr('disabled', true);



    // soft Delete multiple data 
    $(document).on('click', '.btn-delete', function() {
        var id = [];
        var url = $(this).data('url');
        console.log(url);
        $('.delete-checkbox:checked').each(function() {
            id.push($(this).val());
        });

        id.push($(this).data('id'));

        if(id.length == 0){
            $.toast({
                heading: 'Error',
                text: 'Please select at least one data',
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'error',
                hideAfter: 3000, 
                stack: 6
            });
            return;
        }

        console.log(id);

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {

                Pace.restart();
                Pace.track(function () {

                    $.ajax({
                        url: url,
                        data: {id:id},
                        type: "DELETE",
                        dataType: "JSON",
                        success(data) {
                            if(data.success) {
                                $('#dataTable').DataTable().ajax.reload();
                                $(".btn-delete").attr('disabled', true);
                                $(".btn-restore").attr('disabled', true);
                                $('.check-all').prop("checked", false);
                                return successAlert(data.success);
                            }else{
                                return errorAlert(data.error);
                            }
                        },
                        error(error) {
                            return errorStatusText(error.statusText);
                        },
                        complete: function() {
                            $(".btn-delete, .btn-restore").attr('disabled', true);
                        }
                    });
                });
            }
        });
    });







    
    
    // restore multiple data 
    $(document).on('click', '.btn-restore', function() {       
        var id = [];
        var url = $(this).data('url');
        console.log(url);
        
        $('.delete-checkbox:checked').each(function() {
            id.push($(this).val());
        });

        id.push($(this).data('id'));

        if(id.length == 0){
            $.toast({
                heading: 'Error',
                text: 'Please select at least one data',
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'error',
                hideAfter: 3000, 
                stack: 6
            });
            return;
        }

        console.log(id);

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, restore it!'
        }).then((result) => {
            if (result.value) {

                Pace.restart();
                Pace.track(function () {

                    $.ajax({
                        url: url,
                        data: {id:id},
                        type: "GET",
                        dataType: "JSON",
                        success(data) {
                            if(data.success) {
                                $('#dataTable').DataTable().ajax.reload();
                                $(".btn-delete").attr('disabled', true);
                                $(".btn-restore").attr('disabled', true);
                                $('.check-all').prop("checked", false);
                                return successAlert(data.success);
                            }else{
                                return errorAlert(data.error);
                            }
                        },
                        error(error) {
                            return errorStatusText(error.statusText);
                        },
                        complete: function() {
                            $(".btn-delete, .btn-restore").attr('disabled', true);
                        }
                    });
                });
            }
        });
    });


    // change status
    $(document).on("change", "input.editor-active",function() {
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var id = $(this).data('id'); 
        var url = $(this).data('url');
        console.log(status + "-----" + id);
        console.log(url);

        Pace.restart();
        Pace.track(function () {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "JSON",
                data: {'status': status, 'id': id},
                success: function(data) {
                    if(data.success) {
                        return successAlert(data.success);
                    }else{
                        return errorAlert(data.error);
                    }
                },
                error(error) {
                    return errorStatusText(error.statusText);
                }
            });
        });
    });





    

    // show success message
    function successAlert(message) {
        $.toast({
            heading: 'Success',
            text: message,
            position: 'top-right',
            loaderBg:'#ff6849',
            icon: 'success',
            hideAfter: 3000, 
            stack: 6
        });
    };

    // show error message
    function errorAlert(message) {
        $.toast({
            heading: 'Error',
            text: message,
            position: 'top-right',
            loaderBg:'#CC0000',
            icon: 'error',
            hideAfter: 3000, 
            stack: 6
        });
    };

    // show error ststus
    function errorStatusText(message) {
        $.toast({
            heading: 'Error',
            text: message,
            position: 'top-right',
            loaderBg:'#CC0000',
            icon: 'error',
            hideAfter: 3000, 
            stack: 6
        });
    }
});



