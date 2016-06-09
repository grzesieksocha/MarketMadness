jQuery(document).ready(function() {
    var shareButton = $('.shareTransactionButton');

    shareButton.on('click', function (e) {
        e.preventDefault();
        var transactionId = $(this).data('id');
        var path = Routing.generate('shareTransaction', {transactionId: transactionId});
        console.log(path);
        if ($(this).data('isshared') == 0) {
            $(this).text("Unshare");
            $(this).data('isshared', 1);
            $.ajax({
                type: 'POST',
                url: path,
                data: {action: 'share'},
                dataType: 'json',
                success: function (result) {
                    console.log(result);
                },
                error: function () {
                    console.log('ERROR')
                }
            });
            
        } else if ($(this).data('isshared') == 1) {
            $(this).text("Share");
            $(this).data('isshared', 0);
            $.ajax({
                type: 'POST',
                url: path,
                data: {action: 'unshare'},
                dataType: 'json',
                success: function (result) {
                    console.log(result);
                },
                error: function () {
                    console.log('ERROR')
                }
            });

        }
    })
});
