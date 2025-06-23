

function senderInfoVerification() {
	alert('sender verified')
	var firstErrorOccurance = '';
    var sender_name = $('#shipping-form [name="sender_name"]').val();
    var sender_mobile = $('#shipping-form [name="sender_mobile"]').val();
    var sender_email = $('#shipping-form [name="sender_email"]').val();
    var sender_pickuptown = $('#shipping-form [name="sender_pickuptown"]').val();
    var sender_pickupcity = $('#shipping-form [name="sender_pickupcity"]').val();
    var sender_postcode = $('#shipping-form [name="sender_postcode"]').val();
    var sender_thana = $('#shipping-form [name="sender_thana"]').val();
    var sender_address = $('#shipping-form [name="sender_address"]').val();
    var address_type = $('#shipping-form [name="address_type"]:checked').val();

    console.log(sender_name);

    if (
        address_type != "" && address_type != undefined && address_type != null &&
        sender_name != "" && sender_name != undefined && sender_name != null &&
        sender_mobile != "" && sender_mobile != undefined && sender_mobile != null &&
        sender_email != "" && sender_email != undefined && sender_email != null &&
        sender_pickuptown != "" && sender_pickuptown != undefined && sender_pickuptown != null &&
        sender_pickupcity != "" && sender_pickupcity != undefined && sender_pickupcity != null &&
        sender_postcode != "" && sender_postcode != undefined && sender_postcode != null &&
        sender_address != "" && sender_address != undefined && sender_address != null &&
        sender_thana != "" && sender_thana != undefined && sender_thana != null
    ) {

        var email_pattern = /^[^_.-][\w-._]+@[a-zA-Z_-]+?(\.[a-zA-Z]{2,26}){1,3}$/;
        var mobile_pattern = /^(?:\+?88)?01[13-9]\d{8}$/;
        if (sender_email.match(email_pattern) == null) {
            $('.sender_email_error').show();
			firstErrorOccurance = (firstErrorOccurance)?firstErrorOccurance : 'sender_email';
        } else if (sender_mobile.match(mobile_pattern) == null) {
            $('.sender_mobile_error').show();
			firstErrorOccurance = (firstErrorOccurance)?firstErrorOccurance : 'sender_mobile';
        } else {
            fbq('track', 'P2PSenderInfoReceived');
            $('.sender_name_error').hide();
            $('.sender_mobile_error').hide();
            $('.sender_email_error').hide();
            $('.sender_district_error').hide();
            $('.sender_area_error').hide();
            $('.sender_address_error').hide();
            //document.body.scrollTop = 0;
			//document.documentElement.scrollTop = 0;
			firstErrorOccurance = '';
            // slideItem('step-02');
            $('.slide-content').hide();
        }
    } else {
		
        if (sender_name == '' || sender_name == undefined || sender_name == null) {
            $('.sender_name_error').show();
			firstErrorOccurance = (firstErrorOccurance)?firstErrorOccurance : 'sender_name';
        } else {
            $('.sender_name_error').hide();
        }
		
		if (address_type == '' || address_type == undefined || address_type == null) {
            $('.sender_address_type_error').show();
			firstErrorOccurance = (firstErrorOccurance)?firstErrorOccurance : 'address_type';
        } else {
            $('.sender_address_type_error').hide();
        }
		
        if (sender_mobile == '' || sender_mobile == undefined || sender_mobile == null) {
            $('.sender_mobile_error').show();
			firstErrorOccurance = (firstErrorOccurance)?firstErrorOccurance : 'sender_mobile';
        } else {
            $('.sender_mobile_error').hide();
        }
		
        if (sender_email == '' || sender_email == undefined || sender_email == null) {
            $('.sender_email_error').show();
			firstErrorOccurance = (firstErrorOccurance)?firstErrorOccurance : 'sender_email';
        } else {
            $('.sender_email_error').hide();
        }
		
        if (sender_pickuptown == '' || sender_pickuptown == undefined || sender_pickuptown == null) {
            $('.sender_district_error').show();
			firstErrorOccurance = (firstErrorOccurance)?firstErrorOccurance : 'sender_district';
        } else {
            $('.sender_district_error').hide();
        }
		
        if (sender_pickupcity == '' || sender_pickupcity == undefined || sender_pickupcity == null) {
            $('.sender_area_error').show();
			firstErrorOccurance = (firstErrorOccurance)?firstErrorOccurance : 'sender_area';
        } else {
            $('.sender_area_error').hide();
        }
		
        if (sender_address == '' || sender_address == undefined || sender_address == null) {
            $('.sender_address_error').show();
			firstErrorOccurance = (firstErrorOccurance)?firstErrorOccurance : 'sender_address';
        } else {
            $('.sender_address_error').hide();
        }
    }
	
	if(firstErrorOccurance){
		$('[name="'+firstErrorOccurance+'"]').focus();
		$('html, body').animate({
			scrollTop: $('[name="'+firstErrorOccurance+'"]').offset().top - 100 + 'px'
		}, 'fast');
		
	}	
}

function gotoSenderDetail() {
    slideItem('step-01');
    $('#otp_section_booking').hide();
    $('.otp-input #otp0').val('');
    $('.otp-input #otp1').val('');
    $('.otp-input #otp2').val('');
    $('.otp-input #otp3').val('');
}

function slideItem(step) {
    // Hide all steps
    $('.slide-content').hide();
    
    // Show the selected step
    $('#' + step).show();

    // Update navigation indicators (if applicable)
    $('.step-nav ul li').removeClass('active');
    $('.step-nav ul li').each(function() {
        if ($(this).text().trim() === step) {
            $(this).addClass('active');
        }
    });
}


function checkMobileTypeRecipient(mobile) {
    var mobile_pattern = /^(?:((00)|\+)?(88))?(01)[13-9][0-9]{8}$/;
    if (mobile.match(mobile_pattern) == null) {
        $('.recipient_mobile_error').css('display', 'block');
    } else {
        $('.recipient_mobile_error').css('display', 'none');
    }
}

function checkMobileTypeSender(mobile) {
    var mobile_pattern = /^(?:((00)|\+)?(88))?(01)[13-9][0-9]{8}$/;
    if (mobile.match(mobile_pattern) == null) {
        $('.sender_mobile_error').css('display', 'block');
    } else {
        $('.sender_mobile_error').css('display', 'none');
    }
}

function checkEmailType(mobile) {

    var email_pattern = /^[^_.-][\w-._]+@[a-zA-Z_-]+?(\.[a-zA-Z]{2,26}){1,3}$/;
    if (mobile.match(email_pattern) == null) {
        $('.sender_email_error').show();
    } else {
        $('.sender_email_error').hide();
    }
}

function getSenderAddressInfo(){
	var address_type = $('#shipping-form [name="address_type"]:checked').val();
	if(address_type != "" && address_type != null && address_type != undefined){		
		
		$.ajax({
			url: "https://ecourier.com.bd/wp-content/themes/ecourier-2.0/p2p-sender-address-type.php?address_type=" +
                    address_type,
                headers: {
                    "Accept": "application/json"
                },
                crossDomain: true
        }).done(function(dataSet) {
			dataSet = JSON.parse(dataSet);
            if(dataSet.success){
				//console.log(dataSet.data.district)
				$('#shipping-form [name="sender_district"]').val(dataSet.data.district).trigger('change');
				$('#shipping-form [name="sender_area_loggin"]').val(dataSet.data.area);
				$('#shipping-form [name="sender_postcode"]').val(dataSet.data.postcode);
				$('#sender_postcode').html(dataSet.data.postcode);
				$('#shipping-form [name="sender_thana"]').val(dataSet.data.thana);
				$('#shipping-form [name="sender_address"]').val(dataSet.data.address);
			} else {
				//console.log('2');
				if(dataSet.status!='fail'){
					$('#shipping-form [name="sender_district"]').val('').trigger('change');;
					var option = '<option value="">Select Area</option>';
					$('#shipping-form [name="sender_area"]').html(option);
					$('#shipping-form [name="sender_area"]').val('').trigger('change');;
					$('#shipping-form [name="sender_postcode"]').val('');
					$('#shipping-form [name="sender_thana"]').val('');
					$('#shipping-form [name="sender_address"]').val('');
					$('#shipping-form [name="sender_area_loggin"]').val('');
					$('#sender_postcode').html('');
				}				
			}	 		
        });
	}         
}

function getPackagingService(){
	var packaging_service = $('#shipping-form [name="packaging_service"]:checked').val();
	var parcel_type = $('#shipping-form [name="parcel_type"]').val();
	var parcel_weight = $('#shipping-form [name="parcel_weight"]').val();
	var number_of_item = $('#shipping-form [name="number_of_item"]').val();
	
	if((packaging_service==1 && parcel_type == 'Parcel' && parcel_weight != null && parcel_weight != '' && parcel_weight != undefined)||(
	packaging_service==1 && parcel_type != 'Parcel' && number_of_item != null && number_of_item != '' && number_of_item != undefined && parcel_type != null && parcel_type != '' && parcel_type != undefined
	)){
		var parcelType = parcel_type.split('+')
		var quantity = 1;
		if(parcelType[0] == 'Parcel'){
			quantity = parcel_weight;
		} else {
			quantity = number_of_item;
		}
	
		$.ajax({
            url: url + '/package-charge?parcel_type='+parcelType[0]+'&quantity_or_weight=' + quantity,
        }).done(function(data) {
            if (data.success && data.data > 0) {
				$('.packaging-service-value').html('BDT '+data.data+' Added');
				$('#packaging_service_charge').val(data.data);
				$("#packaging_service_yes").prop("checked", true);
				$("#packaging_service_no").prop("checked", false);
				$('.packaging-service-value').show();
            } else {
				$('.packaging-service-value').hide();
				$('.packaging-service-value').html('BDT 0 Taka');
				$('#packaging_service_charge').val('0');
				$("#packaging_service_no").prop("checked", true);
				$("#packaging_service_yes").prop("checked", false);
            }
        });
		
	} else {
		$('.packaging-service-value').hide();
		$('.packaging-service-value').html('BDT 0 Added');
		$('#packaging_service_charge').val('0');
		$("#packaging_service_no").prop("checked", true);
		$("#packaging_service_yes").prop("checked", false);
	}	
}

function numberOfItemField(){
	getPackagingService();
}


function changeParcelWeight(){
	getPackagingService();
}
function checkNumeric(id) {
    var p = /\D/g;
    if (id.value.match(/\D/)){
		id.value = id.value.replace(p, "");
	} 
}