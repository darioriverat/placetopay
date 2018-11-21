function number_format(number, decimals, decPoint, thousandsSep)
{
    decimals = decimals || 0;
    number = parseFloat(number);

    if(!decPoint || !thousandsSep){
        decPoint = '.';
        thousandsSep = ',';
    }

    var roundedNumber = Math.round( Math.abs( number ) * ('1e' + decimals) ) + '';
    var numbersString = decimals ? roundedNumber.slice(0, decimals * -1) : roundedNumber;
    var decimalsString = decimals ? roundedNumber.slice(decimals * -1) : '';
    var formattedNumber = "";

    while(numbersString.length > 3){
        formattedNumber += thousandsSep + numbersString.slice(-3)
        numbersString = numbersString.slice(0,-3);
    }

    return (number < 0 ? '-' : '') + numbersString + formattedNumber + (decimalsString ? (decPoint + decimalsString) : '');
}

$(function(){

    $("body").delegate(":not(form)[data-action='ajax-request']", "click", function(event)
    {
        event.preventDefault();

        var url = $(this).attr('href');
        var type = $(this).attr('data-type');
        var box = $(this).attr('data-response');
        var data = $(this).attr('data-object');

        var call = eval($(this).attr('data-callback')) || {};
        call.success = call.success || new Function();
        call.before = call.before || new Function();
        call.error = call.error || new Function();

        $.ajax({
            url: url,
            type: type,
            data: eval(data),
            beforeSend: function() {
                $(box).html("Cargando...");
                call.before();
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                $(box).html("<div class='alert alert-danger'>Ha ocurrido al procesar la petición!.</div>");

                var e = {};
                e.jqXHR = jqXHR;
                e.textStatus = textStatus;
                e.errorThrown = errorThrown;

                var traza = (e.jqXHR.readyState == 0) ? 'Request not initialized. ' : '';
                traza = traza + e.errorThrown;

                $("#message-fluid-bar").append("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Error!</strong> Ha ocurrido un error al procesar la petición. </em>. " + traza + " </div>");
                call.error(e);
            },
            success: function(data)
            {
                $(box).html(data);
                call.success();
            }
        });
    });

    $("body").delegate("[data-action='ajax-request-on-change']", "change", function(event)
    {
        var url = $(this).attr('href');
        var type = $(this).attr('data-type');
        var box = $(this).attr('data-response');
        var data = $(this).attr('data-object');

        var call = eval($(this).attr('data-callback')) || {};
        call.success = call.success || new Function();
        call.before = call.before || new Function();
        call.error = call.error || new Function();

        $.ajax({
            url: url,
            type: type,
            data: eval(data),
            beforeSend: function() {
                $(box).html("Cargando...");
                call.before();
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                $(box).html("<div class='alert alert-danger'>Ha ocurrido al procesar la petición!.</div>");

                var e = {};
                e.jqXHR = jqXHR;
                e.textStatus = textStatus;
                e.errorThrown = errorThrown;

                var traza = (e.jqXHR.readyState == 0) ? 'Request not initialized. ' : '';
                traza = traza + e.errorThrown;

                $("#message-fluid-bar").append("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Error!</strong> Ha ocurrido un error al procesar la petición. </em>. " + traza + " </div>");
                call.error(e);
            },
            success: function(data)
            {
                $(box).html(data);
                call.success();
            }
        });
    });

    $("body").delegate("[data-action='show-dialog']", "click", function(event)
    {
        event.preventDefault();

        var _url = $(this).attr('data-url');

        var _title = $(this).attr('data-title');
        var _id = $(this).attr('data-id');
        var _width = $(this).attr('data-width');
        var _footer = $(this).attr('data-footer');
        var _keyboard = $(this).attr('data-keyboard');
        var _overlay = $(this).attr('data-overlay');

        var _type = $(this).attr('data-type');
        var _data = $(this).attr('data-object');
        var frm  = $(this).attr('data-form');

        _width = (_width == "small") ? "modal-sm" : _width;
        _width = (_width == "large") ? "modal-lg" : _width;

        _footer = (_footer == "no") ? false : true;

        var keyboard = "data-keyboard='" + _keyboard + "'";

        if (!$('#'+_id).length)
        {
            var footer = (_footer) ? "<div class='modal-footer'></div>" : "";

            var modal = "<div id='" + _id + "' class='modal fade' tabindex='-1' role='dialog' " + keyboard + ">" +
                                "<div class='modal-dialog " + _width + "'>" +
                                    "<div class='modal-content'>" +
                                      "<div class='modal-header'>" +
                                        "<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" +
                                        "<h4 class='modal-title'>" + _title + "</h4>" +
                                      "</div>" +
                                      "<div class='modal-body'>" +
                                        "<p>Cargando...</p>" +
                                      "</div>" +
                                       footer +
                                    "</div>" +
                                  "</div>" +
                                "</div>";

            $("body").append(modal);
        }

        var box = $('#'+_id);

        box.modal();

        var call = eval($(this).attr('data-callback')) || {};
        call.success = call.success || new Function();
        call.before = call.before || new Function();
        call.error = call.error || new Function();

        var form_data = $(frm).serializeArray();

        var parsed = eval(_data);

        for (var i in parsed)
        {
            form_data.push({ name: i, value: parsed[i] });
        }

        $.ajax({
            url: _url,
            type: _type,
            data: form_data,
            beforeSend: function() {
                $(box).find(".modal-body").html("Cargando...");
                call.before();
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                $(box).html("<div class='alert alert-danger'>Ha ocurrido al procesar la petición!.</div>");

                var e = {};
                e.jqXHR = jqXHR;
                e.textStatus = textStatus;
                e.errorThrown = errorThrown;

                var traza = (e.jqXHR.readyState == 0) ? 'Request not initialized. ' : '';
                traza = traza + e.errorThrown;

                $("#message-fluid-bar").append("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Error!</strong> Ha ocurrido un error al procesar la petición. </em>. " + traza + " </div>");
                call.error(e);
            },
            success: function(data)
            {
                $(box).find(".modal-body").html(data);
                call.success();
            }
        });
    });

    $("body").delegate("[data-action='show-dialog-on-change']", "change", function(event)
    {
        event.preventDefault();

        var _url = $(this).attr('data-url');

        var _title = $(this).attr('data-title');
        var _id = $(this).attr('data-id');
        var _width = $(this).attr('data-width');
        var _overlay = $(this).attr('data-overlay');

        var _type = $(this).attr('data-type');
        var _data = $(this).attr('data-object');

        _width = (_width == "small") ? "modal-sm" : _width;
        _width = (_width == "large") ? "modal-lg" : _width;

        if (!$('#'+_id).length)
            $("body").append("<div id='" + _id + "' class='modal fade' tabindex='-1' role='dialog'>" +
                                "<div class='modal-dialog " + _width + "'>" +
                                    "<div class='modal-content'>" +
                                      "<div class='modal-header'>" +
                                        "<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" +
                                        "<h4 class='modal-title'>" + _title + "</h4>" +
                                      "</div>" +
                                      "<div class='modal-body'>" +
                                        "<p>Cargando...</p>" +
                                      "</div>" +
                                      "<div class='modal-footer'>" +
                                      "</div>" +
                                    "</div>" +
                                  "</div>" +
                                "</div>");

        var box = $('#'+_id);

        box.modal();

        var call = eval($(this).attr('data-callback')) || {};
        call.success = call.success || new Function();
        call.before = call.before || new Function();
        call.error = call.error || new Function();

        $.ajax({
            url: _url,
            type: _type,
            data: eval(_data),
            beforeSend: function() {
                $(box).find(".modal-body").html("Cargando...");
                call.before();
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                $(box).html("<div class='alert alert-danger'>Ha ocurrido al procesar la petición!.</div>");

                var e = {};
                e.jqXHR = jqXHR;
                e.textStatus = textStatus;
                e.errorThrown = errorThrown;

                var traza = (e.jqXHR.readyState == 0) ? 'Request not initialized. ' : '';
                traza = traza + e.errorThrown;

                $("#message-fluid-bar").append("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Error!</strong> Ha ocurrido un error al procesar la petición. </em>. " + traza + " </div>");
                call.error(e);
            },
            success: function(data)
            {
                $(box).find(".modal-body").html(data);
                call.success();
            }
        });
    });

    $("body").delegate("[data-action='show-dialog-on-submit']", "submit", function(event)
    {
        event.preventDefault();

        var _url = $(this).attr('action');

        var _title = $(this).attr('data-title');
        var _id = $(this).attr('data-id');
        var _width = $(this).attr('data-width');
        var _overlay = $(this).attr('data-overlay');

        var _type = $(this).attr('data-type');
        var _data = $(this).attr('data-object');

        _width = (_width == "small") ? "modal-sm" : _width;
        _width = (_width == "large") ? "modal-lg" : _width;

        if (!$('#'+_id).length)
            $("body").append("<div id='" + _id + "' class='modal fade' tabindex='-1' role='dialog'>" +
                                "<div class='modal-dialog " + _width + "'>" +
                                    "<div class='modal-content'>" +
                                      "<div class='modal-header'>" +
                                        "<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" +
                                        "<h4 class='modal-title'>" + _title + "</h4>" +
                                      "</div>" +
                                      "<div class='modal-body'>" +
                                        "<p>Cargando...</p>" +
                                      "</div>" +
                                      "<div class='modal-footer'>" +
                                      "</div>" +
                                    "</div>" +
                                  "</div>" +
                                "</div>");

        var box = $('#'+_id);

        box.modal();

        var call = eval($(this).attr('data-callback')) || {};
        call.success = call.success || new Function();
        call.before = call.before || new Function();
        call.error = call.error || new Function();

        var form_data = $(this).serializeArray();

        var parsed = eval(_data);

        for (var i in parsed)
        {
            form_data.push({ name: i, value: parsed[i] });
        }

        $.ajax({
            url: _url,
            type: _type,
            data: form_data,
            beforeSend: function() {
                $(box).find(".modal-body").html("Cargando...");
                call.before();
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                $(box).find(".modal-body").html("<div class='alert alert-danger'>Ha ocurrido al procesar la petición!.</div>");

                var e = {};
                e.jqXHR = jqXHR;
                e.textStatus = textStatus;
                e.errorThrown = errorThrown;

                var traza = (e.jqXHR.readyState == 0) ? 'Request not initialized. ' : '';
                traza = traza + e.errorThrown;

                $(box).find(".modal-body").append("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Error!</strong> Ha ocurrido un error al procesar la petición. </em>. " + traza + " </div>");
                call.error(e);
            },
            success: function(data)
            {
                $(box).find(".modal-body").html(data);
                call.success();
            }
        });
    });

    $("body").delegate("[data-role='ajax-request']", "submit", function(event)
    {
        event.preventDefault();

        var formObject = $(this);

        formObject.find("input").attr("readonly", "readonly");
        formObject.find("select").attr("readonly", "readonly");
        formObject.find("textarea").attr("readonly", "readonly");
        formObject.find("button[type='submit']").attr("disabled", "disabled");

        var url = $(this).attr('action');
        var type = $(this).attr('method');
        var box = $(this).attr('data-response');
        var data = $(this).attr('data-object');

        var call = eval($(this).attr('data-callback')) || {};
        call.success = call.success || new Function();
        call.before = call.before || new Function();
        call.error = call.error || new Function();

        var form_data = $(this).serializeArray();

        var parsed = eval(data);

        for (var i in parsed)
        {
            form_data.push({ name: i, value: parsed[i] });
        }

        $.ajax({
            url: url,
            type: type,
            data: form_data,
            beforeSend: function() {
                $(box).html("Cargando...");
                call.before();
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                $(box).html("<div class='alert alert-danger'>Ha ocurrido al procesar la petición!.</div>");

                var e = {};
                e.jqXHR = jqXHR;
                e.textStatus = textStatus;
                e.errorThrown = errorThrown;

                var traza = (e.jqXHR.readyState == 0) ? 'Request not initialized. ' : '';
                traza = traza + e.errorThrown;

                $("#message-fluid-bar").append("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Error!</strong> Ha ocurrido un error al procesar la petición. </em>. " + traza + " </div>");
                call.error(e);
            },
            success: function(data)
            {
                $(box).html(data);
                call.success();
            },
            complete: function(data)
            {
                formObject.find("input").removeAttr("readonly");
                formObject.find("select").removeAttr("readonly");
                formObject.find("textarea").removeAttr("readonly");
                formObject.find("button[type='submit']").removeAttr("disabled");
            }
        });
    });

    $('[data-toggle="tooltip"]').tooltip();

    $("body").delegate("form[data-role='ajax-push-request']", "submit", function(event)
    {
        event.preventDefault();

        var that = $(this);

        var url = that.attr('action');
        var box = that.attr('data-response');
        var _html = that.attr('data-html');
        var data = that.attr('data-object');

        var call = eval(that.attr('data-callback')) || {};
        call.success = call.success || new Function();
        call.error = call.error || new Function();
        call.before = call.before || new Function();

        var form_data = that.serializeArray();

        var parsed = eval(data);

        for (var i in parsed)
        {
            form_data.push({ name: i, value: parsed[i] });
        }

        call.before();
        var comet = new JScriptRender.jquery.Comet({ url: url });

        var settings = {
            url: url,
            data: form_data,
            callback: {
                success: function(data)
                {
                    // Connection established
                    if (typeof data != "object")
                       data = $.parseJSON(data);

                    if (_html == "append")
                        $(box).append(data.contents);
                    else
                        $(box).html(data.contents);

                    call.success(data);
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    var e = {};
                    e.jqXHR = jqXHR;
                    e.textStatus = textStatus;
                    e.errorThrown = errorThrown;

                    comet.disconnect();
                    call.error(e);
                },
                complete: function()
                {
                    // For each request
                },
                disconnect: function(){
                    console.info('disconnected');
                }
            }
        }

        comet.connect(settings);
    });

});