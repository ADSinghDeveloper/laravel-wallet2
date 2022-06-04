$(document).ready(function () {
    $(".view-link").on("click", function(){
		location.href = $(this).attr("view-link");
    });
    $(".del").on("click", function(e){
        e.preventDefault();
        e.stopPropagation();
        // var parentTr = $(this).parents("tr");
        // var conf = confirm("Are you sure, you want to delete the transaction of " + $("#currency .curr").html() + $("#amount").val() + "?");
        var conf = confirm("Are you sure, you want to delete this?");

        if(!conf)return;
        
        $("form#" + $(this).attr("del_form_id")).submit();
    });

    $(".multiple").each(function(){
        $(this).find(".select-value input[type=\"checkbox\"]").on("click",function(){
            var thiss = $(this);
            var prevElem = thiss.prev("span");
            var elemColor = prevElem.attr("color-code");
            if(thiss.is(":checked")){
                prevElem.removeAttr("style");
                prevElem.css("background-color",elemColor);
            }else{
                prevElem.removeAttr("style");
                prevElem.css("color",elemColor);
            }
        });
    });

    var typeElem = $("#type");
    var transTypeElem = $("#trans-type");
    var typeIcon = $("#typeIcon i.type");

    $(".bmd-form-group").removeClass("bmd-form-group-lg");
    $(".type-btn").on("click", function(){
        $(".type-btn").removeClass("active");
        $(this).addClass("active");
        typeElem.val($(this).data("value"));
    });
    function transType($this){
        if($this.parents(".togglebutton").hasClass("disabled")) return;

        if($this.is(":checked")){
            typeElem.val($this.data("inc"));
            typeIcon.hide(function(){
                if($(this).hasClass("inc")){
                    $(this).show();
                }
            });
        }else{
            typeElem.val($this.data("exp"));
            typeIcon.hide(function(){
                if($(this).hasClass("exp")){
                    $(this).show();
                }
            });
        }
    }
    if(transTypeElem.length > 0){
        transType(transTypeElem);
        transTypeElem.on("click", function(){
            transType($(this));
        });
    }
    if(typeIcon.length > 0){
        typeIcon.on("click", function(){
            transTypeElem.trigger('click');
        });
    }
    $("#no-type").on("click", function(){
        if($(this).is(":checked")){
            $("#type-toggle").addClass("disabled");
            typeElem.attr("disabled",true);
        }else{
            $("#type-toggle").removeClass("disabled");
            typeElem.removeAttr("disabled");
            transType(transTypeElem);
        }
    });
    var statusElem = $("#status");
    function statusOnLoad($this){
        if($this.is(":checked")){
            $this.val($this.data("en"));
        }else{
            $this.val($this.data("dis"));
        }
    }
    if(statusElem.length > 0){
        statusOnLoad(statusElem);
        statusElem.on("click", function(){
            statusOnLoad($(this));
        });
    }
    $(".select-field").each(function(){
        var dataField = $(this).data("field");
        var dataSelected = $(this).data("selected");
        var thiss = $(this);
        $(this).find(".select-value").on("click",function(){
            $(dataField).val($(this).data("value"));
            $(dataSelected).html($(this).clone());
            var bgColor = $(this).find("span").css('background-color');
            if(thiss.hasClass("accounts") && bgColor != ''){
                thiss.closest(".modal").prevAll(".card").find(".card-header").css({"background": bgColor});
            }
            $(this).closest(".modal").find(".btn[data-dismiss=\"modal\"]").trigger("click");
        });
    });

    $(".clear").on("click",function(){
        $(this).prev().val('').focus();
    });

    $("a[data-target=\"#viewFilter\"], a[data-target=\"#saveFilter\"]").on("click", function(){
        $($(this).data("target")).find(".filter").html($("#forViewFilter").html());
    });

    $('#instantFilterBtn').on("click", function(){
        var instantFilter = $("#instantFilter");
        var filter = $('#filter');
        var filters = $('#filters');
        if($(this).is(":checked")){
            filter.attr('disabled',true);
            instantFilter.find("input").attr('disabled',false);
            instantFilter.slideDown(400);
            filters.slideUp(400);
        }else{
            filter.attr('disabled',false);
            filters.slideDown(400);
            instantFilter.slideUp(400);
            instantFilter.find("input").attr('disabled',true);
        }
    });

    $('#all_trans').on("click", function(){
        var fromTill = $("#from, #till");
        if($(this).is(":checked")){
            fromTill.attr("disabled",true);
        }else{
            fromTill.attr("disabled",false);
        }
    });

    $("#activeAccountsOnly").on("click", function(){
        var thiss = $(this);
        $(".accounts.multiple > label").each(function(){
            var labelElem = $(this);
            var inputElem = labelElem.find('input');
            if(inputElem.attr('status') == 0 && thiss.is(":checked")){
                inputElem.attr('disabled',true);
                labelElem.addClass('disabled');
            }else{
                inputElem.attr('disabled',false);
                labelElem.removeClass('disabled');
            }
        });
    });

    function focusTabIndex(){
        var tabIndex1 = $("input[tabindex=\"1\"]");
        var tabIndex1val = tabIndex1.val();
        tabIndex1.focus();
        if(tabIndex1val){
            tabIndex1.val('');
            tabIndex1.val(tabIndex1val);
        }
    }

    focusTabIndex();

    $('a[data-toggle="modal"]').on("click", function(){
        setTimeout(focusTabIndex,1000);
    });

    $("#print_pdf_btn").on('click', function(e){
        e.preventDefault();
        $("#print_pdf").submit();
    });
});
