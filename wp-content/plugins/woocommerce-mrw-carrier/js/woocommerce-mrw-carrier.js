function showChangeAddress() {
    element = document.getElementById("address_shipment_data");
    check = document.getElementById("mrw_change_address");
    if (check.checked) {
        element.style.display='block';
    }
    else {
        element.style.display='none';
    }
}

//Get time slot value
function getTimeSlot(){

    //Get timeSlot
    var timeSlotValue=document.getElementsByName("tramo");

    for(var i=0;i<timeSlotValue.length;i++)
    {
        if(timeSlotValue[i].checked)
            resultado=timeSlotValue[i].value;
    }
        return resultado;
}

//Get frequency value
function getFrequency(){

    //Get frequency
    var frequencyValue=document.getElementsByName("frecuencia");
    var resultado = '1'; // Default value

    for(var i=0;i<frequencyValue.length;i++)
    {
        if(frequencyValue[i].checked)
            resultado=frequencyValue[i].value;
    }
        return resultado;
}

jQuery(document).ready(function($) { 

    if ($('#btn_generate_nac').length)
    {
        //Controls show timeslot on Ecommerce service
        var timeSlotElement = document.getElementById('show_select_timeSlot');
        if(timeSlotElement && document.getElementById('mrw_select_service')){
            var currentService = $('select[name=mrw_select_service]').val();
            if (currentService == '0800'){
                timeSlotElement.style.display='block';
            }
            else{
                timeSlotElement.style.display='none';
            }
        }

        //Controls show frequency on Urgente HOY service (0005)
        // Function to show/hide frequency based on service
        function toggleFrequencySection() {
            var currentService = $('#mrw_select_service').val();
            var frequencyElement = $('#show_select_frequency');
            if (currentService == '0005') {
                frequencyElement.show();
            } else {
                frequencyElement.hide();
            }
        }
        
        // Hide frequency section by default - force hide using both methods
        $('#show_select_frequency').each(function() {
            $(this).css('display', 'none').hide();
        });
        
        // Check initial service value after DOM is ready
        if($('#mrw_select_service').length){
            toggleFrequencySection();
        }

        $("select[name=mrw_select_service]").change(function(){

            ch_service = $(this).val();
            timeSlot = document.getElementById("show_select_timeSlot");
    
            if (ch_service == "0800") {
                if (timeSlot) {
                    timeSlot.style.display='block';
                }
            }
            else {
                if (timeSlot) {
                    timeSlot.style.display='none';
                }
            }

            // Show/hide frequency section based on service
            toggleFrequencySection();
        });

        jQuery("#btn_generate_nac").click(function (){

            //Get order variables
            var billing_phone       = $("#billing_phone").val();
            var billing_email       = $("#billing_email").val();
            var shipping_address_1  = $("#shipping_address_1").val();
            var shipping_address_2  = $("#shipping_address_2").val();
            var shipping_postcode   = $("#shipping_postcode").val();
            var shipping_first_name = $("#shipping_first_name").val();
            var shipping_last_name  = $("#shipping_last_name").val();
            var shipping_weight     = $("#shipping_weight").val();
            var shipping_city       = $("#shipping_city").val();
            var order_id            = $("#order_id").val();
            var select_franchised   = 'N';
            var select_saturdayd    = 'N';
            var select_return       = 'N';
            var select_reference    = $("#mrw_select_reference").val();
            var select_comments     = $("#mrw_select_comments").val();
            var select_npackages    = $("#mrw_select_npackages").val();  
            var select_total_weight = $("#mrw_select_weight").val();
            var select_service      = $("#mrw_select_service").val();
            var company_name        = $("#shipping_company").val();
            var time_slot           = getTimeSlot();
            var frequency           = getFrequency();
            var select_date         = $("#mrw_select_date").val();
    
            var shipping_address = shipping_address_1 + ' ' + shipping_address_2;
    
            //New address
            var check_address   = false;
            var select_name     = null;
            var select_street   = null;
            var select_number   = null;
            var select_pc       = null;
            var select_city     = null;   
            var select_phone    = null;
    
            //Check change address
            if ($("#mrw_change_address").is(":checked")){
                if ($("#mrw_select_name").val().length === 0 || $("#mrw_select_street").val().length === 0 || $("#mrw_select_number").val().length === 0 || $("#mrw_select_pc").val().length === 0 || $("#mrw_select_city").val().length === 0 || $("#mrw_select_phone").val().length === 0){
                    alert('Debe rellenar todos los campos de la dirección de recogida o desmarcar la opción!');
                    return;
                } else {
                    check_address   = true;
                    select_name     = $("#mrw_select_name").val();
                    select_street   = $("#mrw_select_street").val();
                    select_number   = $("#mrw_select_number").val();
                    select_pc       = $("#mrw_select_pc").val();
                    select_city     = $("#mrw_select_city").val();
                    select_phone    = $("#mrw_select_phone").val();
                }
            }
    
            //Check delivery on Saturday
            if ($("#mrw_select_saturdayd").is(":checked")){
            select_saturdayd = 'S';
            }
    
            //Check return
            if ($("#mrw_select_return").is(":checked")){
            select_return = 'S';
            }
    
            //Check delivery in Franchise
            if ($("#mrw_select_franchised").is(":checked")){
            select_franchised = 'E';
            }
    
            //Check if the number of packages is between 1 and 99
            if (select_npackages > 99 || select_npackages < 1 || isNaN(select_npackages)){
            select_npackages = 1;
            }
    
            $( "#mrw_tracking_info_no" ).css("display", "block");
            //$( "#mrw_address_info_no" ).css("display", "block");
            $( "#btn_generate_nac" ).remove();
            $( "#shipment_data" ).remove();
            $( "#address_shipment_data" ).remove();
            $( "#mrw_check_address" ).remove();
            $( "#msg_generate" ).css("display", "block");
    
            jQuery.ajax({ 
                data: {action: 'generate_mrw_label_nac',
                        billing_phone:billing_phone,
                        billing_email:billing_email,
                        shipping_address:shipping_address,
                        shipping_postcode:shipping_postcode,
                        shipping_first_name:shipping_first_name,
                        shipping_last_name:shipping_last_name,
                        shipping_weight:shipping_weight,
                        shipping_city:shipping_city,
                        order_id:order_id,
                        select_franchised:select_franchised,
                        select_saturdayd:select_saturdayd,
                        select_return:select_return,
                        select_npackages:select_npackages,
                        select_total_weight:select_total_weight,
                        select_reference:select_reference,
                        select_comments:select_comments,
                        select_service:select_service,
                        select_name:select_name,
                        select_street:select_street,
                        select_number:select_number,
                        select_pc:select_pc,
                        select_city:select_city,
                        select_phone:select_phone,
                        check_address:check_address,
                        company_name:company_name,
                        time_slot:time_slot,
                        frequency:frequency,
                        select_date:select_date
                        },
                type: 'post',
                dataType: 'json',
                url: "admin-ajax.php",
                success: function(data) {
                    //If the label is generated correctly
                    if (data.state == 1){
                        $( "#msg_generate" ).remove();
                        $( "#btn_download" ).attr("type", "button");
                        $( "#btn" ).attr("href", data.url_label);
                        $ ("#mrw_service").text(data.service);
                        $ ("#mrw_npackages").text(data.npack);
                        $ ("#mrw_franchisedel").text(data.frandel);
                        $ ("#mrw_saturdaydel").text(data.satdel);
                        $ ("#mrw_return").text(data.ret);
                        $ ("#mrw_timeslot").text(data.time_slot);
                        $ ("#mrw_reference").text(data.reference);
                        $ ("#mrw_comments").text(data.comm);
                        $ ("#mrw_tracking_num").text(data.mrw_tracking_number);
                        $ ("#mrw_tracking_info").text(data.message);
                        $ ("#mrw_timeslot").text(data.time_slot);
    
                        // Update tracking number field if it exists
                        if ($("#mrw_tracking_number_field").length) {
                            $("#mrw_tracking_number_field").val(data.mrw_tracking_number);
                        }
                        // Update tracking URL field if it exists
                        if ($("#mrw_tracking_url_field").length && data.url_label) {
                            $("#mrw_tracking_url_field").val(data.url_label);
                        }
    
                        if ( data.ad_check == 'true' ){
                            $( "#mrw_address_info_si" ).css("display", "block");
                            $ ("#mrw_addr_name").text(data.ad_name);
                            $ ("#mrw_addr_street").text(data.ad_street);
                            $ ("#mrw_addr_number").text(data.ad_number);
                            $ ("#mrw_addr_pc").text(data.ad_pc);
                            $ ("#mrw_addr_city").text(data.ad_city);
                            //$ ("#mrw_addr_phone").text(data.ad_phone);
                        }
    
                        alert(data.success);
                    }
                    //If there is any error generating the label
                    if (data.state == 0){
                        alert(data.nosuccess);
                    }
    
                    // Refresh the page to complete the order
                    window.location.reload();
                }
            });
        });
    }
    else if ($('#btn_generate_int').length)
    {
        jQuery("#btn_generate_int").click(function (){

            //Get order variables
            var billing_phone       = $("#billing_phone").val();
            var shipping_address_1  = $("#shipping_address_1").val();
            var shipping_address_2  = $("#shipping_address_2").val();
            var shipping_postcode   = $("#shipping_postcode").val();
            var shipping_first_name = $("#shipping_first_name").val();
            var shipping_last_name  = $("#shipping_last_name").val();
            var shipping_weight     = $("#shipping_weight").val();
            var shipping_city       = $("#shipping_city").val();
            var shipping_state       = $("#shipping_state").val();
            var shipping_country    = $("#shipping_country").val();
            var order_id            = $("#order_id").val();
            var select_npackages    = $("#mrw_select_npackages").val();
            var select_total_weight = $("#mrw_select_weight").val();  
            var select_service      = $("#mrw_select_service").val();
            var company_name        = $("#shipping_company").val();
            var reference           = $("#mrw_select_reference").val();
            var select_date         = $("#mrw_select_date").val();
    
            var shipping_address = shipping_address_1 + ' ' + shipping_address_2;
    
            //Check if the number of packages is between 1 and 99
            if (select_npackages > 99 || select_npackages < 1 || isNaN(select_npackages)){
            select_npackages = 1;
            }
    
            $( "#mrw_tracking_info_no" ).css("display", "block");
            //$( "#mrw_address_info_no" ).css("display", "block");
            $( "#btn_generate_int" ).remove();
            $( "#shipment_data" ).remove();
            $( "#address_shipment_data" ).remove();
            $( "#mrw_check_address" ).remove();
            $( "#msg_generate" ).css("display", "block");
    
            jQuery.ajax({ 
                data: {action: 'generate_mrw_label_int',
                        billing_phone:billing_phone,
                        shipping_address:shipping_address,
                        shipping_postcode:shipping_postcode,
                        shipping_first_name:shipping_first_name,
                        shipping_last_name:shipping_last_name,
                        shipping_weight:shipping_weight,
                        shipping_city:shipping_city,
                        shipping_state:shipping_state,
                        shipping_country:shipping_country,
                        order_id:order_id,
                        select_npackages:select_npackages,
                        select_total_weight:select_total_weight,
                        select_service:select_service,
                        company_name:company_name,
                        reference:reference,
                        select_date:select_date
                        },
                type: 'post',
                dataType: 'json',
                url: "admin-ajax.php",
                success: function(data) {
                    //If the label is generated correctly
                    if (data.state == 1){
                        $( "#msg_generate" ).remove();
                        $( "#btn_download" ).attr("type", "button");
                        $( "#btn" ).attr("href", data.url_label);
                        $ ("#mrw_service").text(data.service);
                        $ ("#mrw_npackages").text(data.npack);
                        $ ("#mrw_tracking_num").text(data.mrw_tracking_number);
                        $ ("#mrw_reference").text(data.reference);
                        $ ("#mrw_tracking_info").text(data.message);
    
                        // Update tracking number field if it exists
                        if ($("#mrw_tracking_number_field").length) {
                            $("#mrw_tracking_number_field").val(data.mrw_tracking_number);
                        }
                        // Update tracking URL field if it exists
                        if ($("#mrw_tracking_url_field").length && data.url_label) {
                            $("#mrw_tracking_url_field").val(data.url_label);
                        }
    
                        alert(data.success);
                    }
                    //If there is any error generating the label
                    if (data.state == 0){
                        alert(data.nosuccess);
                    }
    
                    // Refresh the page to complete the order
                    window.location.reload();
                }
            });
        });
    }
    else if ($('#btn_print_nac').length)
    {
        jQuery("#btn_print_nac").click(function (){

            //Get order variables
            var tracking_number       = $("#mrw_tracking_number").val();
            $( "#btn_print_nac" ).css("display", "none");
            jQuery.ajax({ 
                data: {action: 'print_mrw_label_nac',
                    tracking_number:tracking_number,
                      },
                type: 'post',
                dataType: 'json',
                url: "admin-ajax.php",
                success: function(data) {
                    //If the label is generated correctly
                    if (data.state == 1){

                        let binaryString = window.atob(data.blob);
                        let binaryLen = binaryString.length;
                        let bytes = new Uint8Array(binaryLen);
        
                        for (let i = 0; i < binaryLen; i++) {
                            let ascii = binaryString.charCodeAt(i);
                            bytes[i] = ascii;
                        }

                        var blob=new Blob([bytes], { type: "application/pdf" });
            
                        var link=document.createElement('a');
            
                        link.href=window.URL.createObjectURL(blob);
            
                        link.download=data.filename;
            
                        link.click();

                        
                    }
                    $( "#btn_print_nac" ).css("display", "inline-block");
                }
            });
        });
    }
    else if ($('#btn_print_int').length)
    {
        jQuery("#btn_print_int").click(function (){

            //Get order variables
            var tracking_number       = $("#mrw_tracking_number").val();
            $( "#btn_print_int" ).css("display", "none");
            
            jQuery.ajax({ 
                data: {action: 'print_mrw_label_int',
                    tracking_number:tracking_number,
                      },
                type: 'post',
                dataType: 'json',
                url: "admin-ajax.php",
                success: function(data) {
                    //If the label is generated correctly
                    if (data.state == 1){

                        let binaryString = window.atob(data.blob);
                        let binaryLen = binaryString.length;
                        let bytes = new Uint8Array(binaryLen);
        
                        for (let i = 0; i < binaryLen; i++) {
                            let ascii = binaryString.charCodeAt(i);
                            bytes[i] = ascii;
                        }

                        var blob=new Blob([bytes], { type: "application/pdf" });
            
                        var link=document.createElement('a');
            
                        link.href=window.URL.createObjectURL(blob);
            
                        link.download=data.filename;
            
                        link.click();
                    }
                    $( "#btn_print_int" ).css("display", "inline-block");
                }
            });
        });
    }if ($('.btn_print_nac_mas').length)
    {
        jQuery(".btn_print_nac_mas").click(function (){

            var tracking_number = $(this).attr('value');
            var object = $(this);
            object.css("display", "none");
            jQuery.ajax({ 
                data: {action: 'print_mrw_label_nac',
                    tracking_number:tracking_number,
                      },
                type: 'post',
                dataType: 'json',
                url: "admin-ajax.php",
                success: function(data) {
                    //If the label is generated correctly
                    if (data.state == 1){

                        let binaryString = window.atob(data.blob);
                        let binaryLen = binaryString.length;
                        let bytes = new Uint8Array(binaryLen);
        
                        for (let i = 0; i < binaryLen; i++) {
                            let ascii = binaryString.charCodeAt(i);
                            bytes[i] = ascii;
                        }

                        var blob=new Blob([bytes], { type: "application/pdf" });
            
                        var link=document.createElement('a');
            
                        link.href=window.URL.createObjectURL(blob);
            
                        link.download=data.filename;
            
                        link.click();

                        
                    }
                    object.css("display", "block");
                }
                
            });
            
        });
    }if ($('.btn_print_int_mas').length)
    {
        jQuery(".btn_print_int_mas").click(function (){

            var tracking_number = $(this).attr('value');
            var object = $(this);
            object.css("display", "none");
            jQuery.ajax({ 
                data: {action: 'print_mrw_label_int',
                    tracking_number:tracking_number,
                      },
                type: 'post',
                dataType: 'json',
                url: "admin-ajax.php",
                success: function(data) {
                    //If the label is generated correctly
                    if (data.state == 1){

                        let binaryString = window.atob(data.blob);
                        let binaryLen = binaryString.length;
                        let bytes = new Uint8Array(binaryLen);
        
                        for (let i = 0; i < binaryLen; i++) {
                            let ascii = binaryString.charCodeAt(i);
                            bytes[i] = ascii;
                        }

                        var blob=new Blob([bytes], { type: "application/pdf" });
            
                        var link=document.createElement('a');
            
                        link.href=window.URL.createObjectURL(blob);
            
                        link.download=data.filename;
            
                        link.click();

                        
                    }
                    object.css("display", "block");
                }
                
            });
            
        });
    }

});