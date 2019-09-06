<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Auto Complete Search Using Jquery UI - Tutsmake.com</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

    <div class="row">
        <div class="col-sm-8 offset-sm-2">
            <h1 class="display-3">Add a customers for Events</h1>
            <div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div><br />
                @endif
                <form method="post" action="{{ route('form.store') }}">
                    @csrf
                    <div id="custom-search-input">
                    <div class="input-group">
                        <label for="name"> Name:</label>
                        <input type="text" id="search" oninput="complite()" data-phone="phone_0" class="form-control search ui-autocomplete-input" name="visitor[0][name]"/>
                    </div>
                    </div>
                    <div class="input-group">
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone_0" class="form-control phone ui-autocomplete-input" name="phone"/>
                    </div>
                    <input type="hidden" name="event" value="{{$event}}">
                    <button type="button" id="addVisitor">
                        Add visitor
                    </button>

                    <button type="submit" class="btn btn-primary-outline">Save</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        $("#phone_0").change(function () {
            var regPhone = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;
            var phone = $('#phone_0')
           if ( regPhone.test(phone) == true) {
               phone.addClass('succes');
               phone.removeClass('err');
           } else  {
               phone.removeClass('succes');
               phone.addClass('err');
           }
        });


        var iterator = 1;
        $('#addVisitor').on('click',  function () {
            let fields = ' <div class="form-group-body"> ' +
                '  <div class="form-group">\n' +
                '                        <label for="name"> Name:</label>\n' +
                '                        <input type="text" oninput="complite()"  data-phone="phone_'+iterator+'" class="form-control search" name="visitor['+iterator+'][name]"/>\n' +
                '                    </div>\n' +
                '\n' +
                '                    <div class="form-group">\n' +
                '                        <label for="phone">Phone:</label>\n' +
                '                        <input  type="text" id="phone_'+iterator+'" class="form-control" name="visitor['+iterator+'][phone]"/>\n' +
                '                    </div>' +
                ' </div>  ';
            iterator++;
            $(this).after(fields);
        });



        function complite() {
            $( ".search" ).autocomplete({

                source: function(request, response) {
                    $.ajax({
                        url: "{{url('autocomplete')}}",
                        data: {
                            term : request.term
                        },
                        dataType: "json",
                        success: function(data){
                            response( $.map( data, function( item ) {
                                return {
                                    label: item.name,
                                    value: item.phone,
                                    data : item
                                }
                            }));
                        }
                    });
                },
                minLength: 1,
                autoFocus: true,
                select:function(event, ui)  {
                    let data_phone = $(this).attr('data-phone');
                    $(this).val(ui.item.label);
                    $('#' + data_phone).val(ui.item.value);
                },

            });
        }
    </script>

<style>
    .succes {
        border: 1px solid green;
    }
    .err {
        border: 1px solid red;
    }
    .form-group-body {
        margin-top: 15px;
        border-top: 1px solid #000;
        padding-top: 15px;
    }
    .input-group {
        margin-bottom: 15px;
        align-items: center;
    }
    .input-group label {
        margin-right: 15px;
    }


</style>
