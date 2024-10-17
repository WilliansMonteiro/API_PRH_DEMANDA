var helper = {

    construct : function() {
    },


    alertSuccess : function(text){
        Swal.fire({
            icon: 'success',
            title: 'Aviso',
            text: text,
        })
    },

    alertError : function(text){
        Swal.fire({
            icon: 'error',
            title: 'Alerta',
            text: text,
        })
    },

    alertWarning : function(text){
        Swal.fire({
            icon: 'warning',
            title: 'Alerta',
            text: text,
        })
    },

    alertInformation : function(text){
        Swal.fire({
            icon: 'info',
            title: 'Aviso',
            text: text,
        })
    },


    dataBrToDate : function(input, full) {
        var output = false;
        if (/\d{2}\/\d{2}\/\d{4}/.test(input)) {
            output = new Date(input.substr(6, 4), (input.substr(3,2) - 1), input.substr(0,2));
        }
        if (typeof full == 'boolean' && full === true ) {
            output = new Date(input.substr(6, 4), (input.substr(3,2) - 1), input.substr(0,2));
            output.setUTCHours(input.substr(11, 2));
            output.setUTCMinutes(input.substr(14, 2));
            output.setUTCSeconds(input.substr(17, 2));

        }
        return output;
    },

    isDate: function(txtDate){
        var currVal = txtDate;
        if(currVal == ''){
            return false;
        }
        var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; //Declare Regex
        var dtArray = currVal.match(rxDatePattern); // is format OK?

        if (dtArray == null) {
            return false;
        }

        dtDay = dtArray[1];
        dtMonth = dtArray[3];
        dtYear = dtArray[5];

        if (dtMonth < 1 || dtMonth > 12){
            return false;
        }else if (dtDay < 1 || dtDay> 31){
            return false;
        }else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31){
            return false;
        }else if (dtMonth == 2) {
            var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
            if (dtDay> 29 || (dtDay ==29 && !isleap))
                return false;
        }

        return true;
    },

};


$(document).ready(function(){
    helper.construct();
});




