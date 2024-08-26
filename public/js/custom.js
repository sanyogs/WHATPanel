$(document).ready(function () {

    // Resize current autoresizable textareas
   $('textarea.js-auto-size').textareaAutoSize();
   
   // select2 
   if ($.fn.select2) {
       $(".select2-option").select2();
       $("#select2-tags").select2({
         tags:["red", "green", "blue"],
         tokenSeparators: [",", " "]}
       );
   }
   
   $('#add-translation').on('click', function () {
       var lang = $('#add-language').val();
       window.location.href = base_url+'settings/translations/add/'+lang+'/?settings=translations';
   });
   
   
   $('.span12').addClass('col-lg-12').removeClass('span12');
   if (!$('button').prop('type')) {
       $(this).attr('type', 'button');
   }

   $.fn.showCategoryFields = function (selectObject) {

      if(selectObject.value == 9) {
           $('#qty').val('1');
           $('#price').val('0');
           $('#generic_group, #domain_group').hide(500); 
           $('#hosting_group').show(300);
      }

      else if(selectObject.value == 8) {
           $('#qty').val('1');
           $('#price').val('0');
           $('#generic_group, #hosting_group').hide(500);
           $('#domain_group').show(300);        
       }

       else {
           $('#generic_group').show(300);
           $('#domain_group, #hosting_group').hide(500); 
       }
   }

   //disable field added by wysihtml5
$('input[name=_wysihtml5_mode]').prop("disabled", true);

});

function textarea_resize(el) {
   var lines = $(el).val().split(/\r\n|\r|\n/).length;
   var height = ((lines * 34) - ((lines - 1) * 10));
   $(el).css('height', height + 'px');
}


var domain_name = '';
var type = '';
var domain_name = '';
var error = false;
var name = '';


$('.vertical li:has(ul)').addClass('parent');
$('.horizontal li:has(ul)').addClass('parent');


// $('#Transfer').on('click', function (e) {
//    e.preventDefault();
//     // alert(1);exit;

//    name = $('#searchBar').val();
//    if (checkName(name)) {
//        type = $('#Transfer').attr('data');
//        if (name != '') {
//            var ext = $('#ext').find('option:selected').val();
//            domain_name = name + "." + ext;
//            $(this).hide();
//            $('#checking').show();
//            checkAvailability();

//        }
//        else {
//            swal("Empty Search!", "Please enter a domain name", "warning");
//        }
//    }

// });


$(document).on('submit', '.btn', function(){
   Pace.Start; 
});



// $('#btnSearch, #Search').on('click', function (e) {
//    e.preventDefault();
//    name = $('#searchBar').val();
//    if (checkName(name)) {
//        type = $('#Search').attr('data');

//        if(type == undefined) {
//         type = $('#btnSearch').attr('data');
//        }

//        if (name != '') {
//            var ext = $('#ext').find('option:selected').val();
//            domain_name = name + "." + ext;
//            $(this).hide();
//            $('#checking').show();
//            checkAvailability();
//        }
//        else {
//            swal("Empty Search!", "Please enter a domain name", "warning");
//        }
//    }

// });




$.fn.searchAgain = function () {
   $('#response').hide(500);
}



//The actual request to check availability





function checkName(name) {
   if (name.indexOf('.') !== -1) {
       swal("Invalid Domain!", "Please enter the name only and select the extension", "warning");
       $('#checking').hide();
       $('#btnSearch').show();
       $('#Search').show();
       $('#Transfer').show();
       return false;
   }

   return true;

}



$('#cart').on('click', '#add_available', function () {
   $('#cart').submit();
});





$.fn.continueOrder = function () {
    
    if (window.location.href == base_url + 'cart/domain') {
         $('.search_form').submit();
     }
  
     else {
         $.ajax({
             url: base_url + 'cart/add_domain',
             type: 'post',
             data: $("#search_form").serialize(),
             success: function (data) {
                 $('#response').slideUp(500);
                 $('#continue').slideDown(500);
             },
             error: function (data) {
  
             }
         });
     }
  }


//v1.5
var price = $('#domain_price').find('option:selected').val();
    $('#price').val(price);

$(document).on('change','#domain_price',function(){
    var price = $('#domain_price').find('option:selected').val();
    $('#price').val(price);
}); 

var registrar = $('#registrar').find('option:selected').val();
    $('#registrar_val').val(registrar);

$(document).on('change','#registrar',function(){
    var registrar = $('#registrar').find('option:selected').val();
    $('#registrar_val').val(registrar);
}); 

(function($){
"use strict"; 
        jQuery.extend( jQuery.fn.dataTableExt.oSort, {
            "currency-pre": function (a) {
                a = (a==="-") ? 0 : a.replace( /[^\d\-\.]/g, "" );
                return parseFloat( a ); },
            "currency-asc": function (a,b) {
                return a - b; },
            "currency-desc": function (a,b) {
                return b - a; }
        });
        $.fn.dataTableExt.oApi.fnResetAllFilters = function (oSettings, bDraw/*default true*/) {
                for(var iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                        oSettings.aoPreSearchCols[ iCol ].sSearch = '';
                }
                oSettings.oPreviousSearch.sSearch = '';

                if(typeof bDraw === 'undefined') bDraw = true;
                if(bDraw) this.fnDraw();
        }

        $(document).ready(function() {

        $.fn.dataTable.moment('<?=$sort?>');
        $.fn.dataTable.moment('<?=$sort?> HH:mm');

        var oTable1 = $('.AppendDataTables').dataTable({
        "bProcessing": true,
        "sDom": "<'row'<'col-sm-4'l><'col-sm-8'f>r>t<'row'<'col-sm-4'i><'col-sm-8'p>>",
        "sPaginationType": "full_numbers",
			"iDisplayLength": 10,
        "oLanguage": {
                "sProcessing": "<?=lang('processing')?>",
                "sLoadingRecords": "<?=lang('loading')?>",
                "sLengthMenu": "<?=lang('show_entries')?>",
                "sEmptyTable": "<?=lang('empty_table')?>",
                "sInfo": "<?=lang('pagination_info')?>",
                "sInfoEmpty": "<?=lang('pagination_empty')?>",
                "sInfoFiltered": "<?=lang('pagination_filtered')?>",
                "sInfoPostFix":  "",
                "sSearch": "<?=lang('search')?>:",
                "sUrl": "",
                "oPaginate": {
                        "sFirst":"<?=lang('first')?>",
                        "sPrevious": "<?=lang('previous')?>",
                        "sNext": "<?=lang('next')?>",
                        "sLast": "<?=lang('last')?>"
                }
        },
        "tableTools": {
                    "sSwfPath": "<?=base_url()?>resource/js/datatables/tableTools/swf/copy_csv_xls_pdf.swf",
              "aButtons": [
                      {
                      "sExtends": "csv",
                      "sTitle": "<?=config_item('company_name').' - '.lang('invoices')?>"
                  },
                      {
                      "sExtends": "xls",
                      "sTitle": "<?=config_item('company_name').' - '.lang('invoices')?>"
                  },
                      {
                      "sExtends": "pdf",
                      "sTitle": "<?=config_item('company_name').' - '.lang('invoices')?>"
                  },
              ],
        },
        "aaSorting": [],
        "aoColumnDefs":[{
                    "aTargets": ["no-sort"]
                  , "bSortable": false
              },{
                    "aTargets": ["col-currency"]
                  , "sType": "currency"
              }]
        });
            $("#table-tickets").dataTable().fnSort([[0,'desc']]);
            $("#table-tickets-archive").dataTable().fnSort([[1,'desc']]);           
            $("#table-files").dataTable().fnSort([[2,'desc']]);
            $("#table-links").dataTable().fnSort([[0,'asc']]);
            $("#table-clients").dataTable().fnSort([[0,'asc']]);
            $("#table-client-details-1").dataTable().fnSort([[1,'asc']]);
            $("#table-client-details-2").dataTable().fnSort([[2,'desc']]);
            $("#table-client-details-3").dataTable().fnSort([[0,'asc']]);
            $("#table-client-details-4").dataTable().fnSort([[1,'asc']]);
            $("#table-templates-1").dataTable().fnSort([[0,'asc']]);
            $("#table-templates-2").dataTable().fnSort([[0,'asc']]);
            $("#table-invoices").dataTable().fnSort([[0,'desc']]);
            $("#table-payments").dataTable().fnSort([[0,'desc']]);
            $("#table-users").dataTable().fnSort([[4,'desc']]);
            $("#table-rates").dataTable().fnSort([[0,'asc']]);
            $("#table-stuff").dataTable().fnSort([[0,'asc']]);
            $("#pages").dataTable().fnSort([[0,'asc']]);
            $("#table-activities").dataTable().fnSort([[0,'desc']]);
             $("#table-strings").DataTable().page.len(-1).draw();
            if ($('#table-strings').length == 1) { $('#table-strings_length, #table-strings_paginate').remove(); $('#table-strings_filter input').css('width','200px'); }


            $('#save-translation').on('click', function (e) {
            e.preventDefault();
            oTable1.fnResetAllFilters();
            $.ajax({
                url: base_url+'settings/translations/save/?settings=translations',
                type: 'POST',
                data: { json : JSON.stringify($('#form-strings').serializeArray()) },
                success: function() {
                    toastr.success("<?=lang('translation_updated_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        $('#table-translations').on('click','.backup-translation', function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            $.ajax({
                url: target,
                type: 'GET',
                data: {},
                success: function() {
                    toastr.success("<?=lang('operation_successful')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        $("#table-translations").on('click', '.restore-translation', function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            $.ajax({
                url: target,
                type: 'GET',
                data: {},
                success: function() {
                    toastr.success("<?=lang('translation_restored_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        $('#table-translations').on('click','.submit-translation', function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            $.ajax({
                url: target,
                type: 'GET',
                data: {},
                success: function() {
                    toastr.success("<?=lang('translation_submitted_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        $("#table-translations").on('click','.active-translation',function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            var isActive = 0;
            if (!$(this).hasClass('btn-success')) { isActive = 1; }
            $(this).toggleClass('btn-success').toggleClass('btn-default');
            $.ajax({
                url: target,
                type: 'POST',
                data: { active: isActive },
                success: function() {
                    toastr.success("<?=lang('translation_updated_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });

        $(".menu-view-toggle").on('click',function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            var role = $(this).attr('data-role');
            var vis = 1;
            if ($(this).hasClass('btn-success')) { vis = 0; }
            $(this).toggleClass('btn-success').toggleClass('btn-default');
            $.ajax({
                url: target,
                type: 'POST',
                data: { visible: vis, access: role },
                success: function() {},
                error: function(xhr) {}
            });
        });

        $(".cron-enabled-toggle").on('click',function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            var role = $(this).attr('data-role');
            var ena = 1;
            if ($(this).hasClass('btn-success')) { ena = 0; }
            $(this).toggleClass('btn-success').toggleClass('btn-default');
            $.ajax({
                url: target,
                type: 'POST',
                data: { enabled: ena, access: role },
                success: function() {},
                error: function(xhr) {}
            });
        });


        $('[data-rel=tooltip]').tooltip();
});
})(jQuery);