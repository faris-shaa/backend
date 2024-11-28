$Ajax = function (method, oVals) {
    oVals = JSON.stringify(oVals);

    console.log(base_url + '/ajax', method)
    $.ajax({
        type: "POST",
        url: base_url + '/ajax',
        data: {method: method, oVals: oVals, _token: _token},
        cache: false,
        success: function (html) {
            $('body').append(html);
        }
    });
};
