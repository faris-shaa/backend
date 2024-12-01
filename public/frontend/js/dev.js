$Ajax = function (method, oVals, page = null) {
    oVals = JSON.stringify(oVals);

    $.ajax({
        type: "POST",
        url: `${base_url}/ajax${page ? `?page=${page}` : ''}`,
        data: {method: method, oVals: oVals, _token: _token},
        cache: false,
        success: function (html) {
            $('body').append(html);
        }
    });
};
